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
class Products extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key

        $this->load->model("product");
    }

    /**
     * @api { get } /products/ Products
     *
     * @apiName Products List
     * @apiDescription Get list of products. Filter products by availability. Pass query parameter quantity and the amount of items required
     *
     * @apiSuccess {Boolean} status Status of request.
     * @apiSuccess {Array} data  List of products.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "data": [
     *          {
     *              "id": "1",
     *              "title": "Product 1",
     *              "price": "100.00",
     *              "inventory_count": "10"
     *          }
     *       ]
     *     }
     *
     * @apiGroup Products
     */
    public function index_get(){
        $queryParams = $this->get();
        $products = $this->product->list($queryParams);
        $response["status"] = TRUE;
        $response["data"] = $products;
        $this->response($response,REST_Controller::HTTP_OK);
    }

    /**
     * @api { get } /products/:id/details Product Details
     *
     * @apiName Product Details
     * @apiDescription Get details of product specified by id
     * @apiParam {Number} id  Product ID.
     *
     * @apiSuccess {Boolean} status Status of request.
     * @apiSuccess {Object} data  Product details.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "status": true,
     *       "data": {
     *              "id": "1",
     *              "title": "Product 1",
     *              "price": "100.00",
     *              "inventory_count": "10"
     *        }
     *     }
     *
     * @apiGroup Products
     */
    public function details_get($id){
        $product = $this->product->details($id);
        $response["status"] = TRUE;
        $response["data"] = $product;
        $this->response($response,REST_Controller::HTTP_OK);
    }
   
}