<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Cart extends REST_Controller {

    

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->sessionId = "1234567890";

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model("product");
    }

    public function index_get(){
        $results = $this->product->listCart($this->sessionId);
        if(empty($results)){
            $response["status"] = TRUE;
            $response["message"] = "Sorry you have no items in your cart";
            $response["data"] = [];
        }
        else{
            $response["status"] = TRUE;
            $items = json_decode($results->items);
            $total = 0.00;
            foreach ($items as $key => $item) {
                $total += $item->total_price;
            }
            $response["total_price"] = $total; 
            $response["data"] = $items;
        }

        $this->response($response,REST_Controller::HTTP_OK);

    }

    public function add_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->load->library("form_validation");
        $this->form_validation->set_rules('id','id','required|integer');
        $this->form_validation->set_rules('quantity','quantity','required|integer|greater_than[0]');
        $this->form_validation->set_error_delimiters('','');

        if($this->form_validation->run() === FALSE){
            $response["status"] = FALSE;
            $response["error"] = validation_errors();
            $this->response($response,REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            // check if product id exists and is available
            $product = $this->product->details($this->post('id'));
            if(empty($product)){
                // product not found
                $response["status"] = FALSE;
                $response["message"] = "Not found";
                $this->response($response,REST_Controller::HTTP_NOT_FOUND);
            }
            else{
                if($product->inventory_count < 1){
                    // out of stock
                    $response["status"] = FALSE;
                    $response["message"] = "Sorry product is no longer in stock";
                    $this->response($response,REST_Controller::HTTP_FORBIDDEN); 
                }
                else{

                    if($this->post("quantity") > $product->inventory_count){
                        // out of stock
                        $response["status"] = FALSE;
                        $response["message"] = "Sorry only". $product->inventory_count ."left in stock";
                        $this->response($response,REST_Controller::HTTP_FORBIDDEN); 
                    }

                    $itemToAdd = array("id"=>$this->post("id"),"quantity"=>$this->post("quantity"),"price"=>$product->price,"total_price"=> ($this->post("quantity") * $product->price));

                    
                    // check if items are in cart
                    $cart = $this->product->listCart($this->sessionId);

                    if(empty($cart)){
                        // no items. create new cart
                        $data = array(
                            "session_id" => $this->sessionId,
                            "items" => json_encode(array($itemToAdd)),
                            "created_at" => date("Y-m-d H:i:s")
                        );

                        if($this->product->addToCart($data)){
                            $response["status"] = TRUE;
                            $response["message"] = "Product successfully added to cart";
                            $response["data"] = $this->product->listCart($this->sessionId);
                            $this->response($response,REST_Controller::HTTP_OK);
                        }
                        else{
                            $response["status"] = FALSE;
                            $response["message"] = "Unable to add item to cart"; 
                            $this->response($response,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }
                    }
                    else{
                        // add to cart
                        $items = json_decode($cart->items);
                        $pushItem = true;

                        foreach ($items as $key => $item) {
                            //check if product id exists in items
                            if($item->id == $this->post("id")){
                               $item->quantity = $item->quantity + $this->post("quantity");
                               $item->total_price =  $item->quantity * $item->price;
                               $pushItem = false;
                               if($item->quantity > $product->inventory_count){
                                    // out of stock
                                    $response["status"] = FALSE;
                                    $response["message"] = "Sorry you can only purchase ". $product->inventory_count ." of this item";
                                    $this->response($response,REST_Controller::HTTP_FORBIDDEN); 
                                }
                            }
                        }

                        if($pushItem){
                            // insert into items
                            array_push($items, $itemToAdd);
                        }

                        // update items in cart.
                        if($this->product->updateCart($this->sessionId,array("updated_at"=>date("Y-m-d H:i:s"),"items"=>json_encode($items)))){
                            $response["status"] = TRUE;
                            $response["message"] = "Product successfully added to cart";
                            $response["data"] = $this->product->listCart($this->sessionId);
                            $this->response($response,REST_Controller::HTTP_OK);
                        }
                        else{
                            $response["status"] = FALSE;
                            $response["message"] = "Unable to add item to cart"; 
                            $this->response($response,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                        }
                    }
                        
                }
            }
        }
            
    }

    public function remove_post(){
        $_POST = json_decode(file_get_contents("php://input"), true);
        $this->load->library("form_validation");
        $this->form_validation->set_rules('id','id','required|integer');
        $this->form_validation->set_rules('quantity','quantity','required|integer|greater_than[0]');
        $this->form_validation->set_error_delimiters('','');

        if($this->form_validation->run() === FALSE){
            $response["status"] = FALSE;
            $response["error"] = validation_errors();
            $this->response($response,REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            $cart = $this->product->listCart($this->sessionId);

            if(empty($cart)){
                $response["status"] = FALSE;
                $response["error"] = "You don't have any item in cart";
                $this->response($response,REST_Controller::HTTP_NOT_FOUND);
            }
            else{
                $items = json_decode($cart->items);

                foreach ($items as $key => $item) {
                    if($this->post("id") == $item->id){
                        $item->quantity = $item->quantity - $this->post("quantity");
                        if($item->quantity < 0){
                            $response["status"] = FALSE;
                            $response["error"] = "Invalid quantity provided";
                            $this->response($response,REST_Controller::HTTP_FORBIDDEN);
                            exit();
                        }
                        elseif($item->quantity == 0){
                            unset($items[$key]);
                        }
                        else{
                            $item->total_price = $item->quantity * $item->price;
                        }
                    }
                }

                if(empty($items)){
                    $this->product->emptyCart($this->sessionId);
                    $response["status"] = TRUE;
                    $response["message"] = "Cart empty";
                    $this->response($response,REST_Controller::HTTP_OK);
                }
                else{
                    // update items in cart.
                    if($this->product->updateCart($this->sessionId,array("updated_at"=>date("Y-m-d H:i:s"),"items"=>json_encode($items)))){
                        $response["status"] = TRUE;
                        $response["message"] = "Item successfully removed from cart";
                        $response["data"] = $this->product->listCart($this->sessionId);
                        $this->response($response,REST_Controller::HTTP_OK);
                    }
                    else{
                        $response["status"] = FALSE;
                        $response["message"] = "Unable to remove item from cart"; 
                        $this->response($response,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                }

            }
        }
    }

    public function empty_delete(){
        if($this->product->emptyCart($this->sessionId)){
            $response["status"] = TRUE;
            $response["message"] = "Cart empty";
            $this->response($response,REST_Controller::HTTP_OK);
        }
        else{
            $response["status"] = FALSE;
            $response["error"] = "Unable to complete request";
            $this->response($response,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkout_get($sessionId){
        // check if cart exists for sessionId
        $cart = $this->product->listCart($sessionId);

        if(empty($cart)){
            $response["status"] = FALSE;
            $response["error"] = "Not found";
            $this->response($response,REST_Controller::HTTP_NOT_FOUND);
        }

        $items = json_decode($cart->items);

        $amount = 0;

        foreach ($items as $key => $item) {
            $product = $this->product->checkProductAvailability($item->id,$item->quantity);

            if(empty($product)){
                $response["status"] = FALSE;
                if($product->quantity == 0){
                    $response["error"] = "Sorry. We have ".$product->quantity." of ".$product->title." left";
                }
                else{
                   $response["error"] = "Sorry. ".$product->title." is out of stock"; 
                }
                $this->response($response,REST_Controller::HTTP_FORBIDDEN);
            }
            else{  
                $amount += $item->total_price;
            }
        }

        $reference = time();
        $data = array(
            "reference_no" => $reference,
            "created_at" => date("Y-m-d H:i:s"),
            "amount" => $amount
        );

        $this->db->trans_start();
        $orderId = $this->product->createOrder($data);
        $myOrders = array();
        foreach ($items as $key => $item) {
            $list["order_id"] = $orderId;
            $list["product_id"] = $item->id;
            $list["quantity"] = $item->quantity;
            $list["total_price"] = $item->total_price;
            array_push($myOrders, $list);
        }

        if($this->product->insertOrderItems($myOrders)){
            // delete cart
            $this->product->emptyCart($sessionId);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
                // generate an error... or use the log_message() function to log your error
            $response["status"] = FALSE;
            $response["error"] = log_message();
            $this->response($response,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response["status"] = TRUE;
        $response["message"] = "Transaction completed. Order Id :".$reference;
        $response["reference"] = $reference;
        $this->response($response,REST_Controller::HTTP_OK);
    }
        
}