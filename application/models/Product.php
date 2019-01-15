<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Model {


	public function list($queryParams=array()){
		if(!empty($queryParams) && array_key_exists("quantity", $queryParams))
			return $this->db->get_where("products",array("inventory_count <="=>$queryParams["quantity"]))->result();
		else
			return $this->db->get("products")->result();
	}

	public function details($id){
		return $this->db->get_where("products",array("id"=>$id))->row();
	}

	public function addToCart($data){
		$this->db->insert("cart",$data);
		if($this->db->affected_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}

	public function updateCart($id,$data){
		$this->db->where("session_id",$id);
		$this->db->update("cart",$data);
		if($this->db->affected_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}

	public function emptyCart($id){
		$this->db->where("session_id",$id);
		$this->db->delete("cart");
		if($this->db->affected_rows() > 0)
			return TRUE;
		else
			return FALSE;
	}

	public function listCart($sessionId){
		return $this->db->get_where("cart",array("session_id"=>$sessionId))->row();
	}


	public function createOrder($data){
		$this->db->insert("orders",$data);
		return $this->db->insert_id();
	}

	public function checkProductAvailability($productId,$quantity){
		$this->db->where("id = ".$productId." and (inventory_count > 0 and inventory_count >= ".$quantity.")");
		return $result = $this->db->get("products")->row();
	}

	public function insertOrderItems($data){
		$this->db->insert_batch("orders_meta",$data);
		if($this->db->affected_rows() > 0){
			foreach ($data as $key => $value) {
				$this->db->set("inventory_count","inventory_count - ".$value["quantity"],FALSE);
				$this->db->where("id",$value["product_id"]);
				$this->db->update("products");
			}
			return TRUE;
		}
		else{
			return FALSE;
		}
	}


}