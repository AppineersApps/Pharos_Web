<?php
/**
 * Description of Resend Otp Extended Controller
 * 
 * @module Extended Resend Otp
 * 
 * @class Cit_Resend_otp.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Resend_otp.php
 * 
 * @author CIT Dev Team
 * 
 * @date 18.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Products extends Products {
  public function __construct()
  {
      parent::__construct();
  }
  public function checkProductsExist($input_params=array()){
      $return_arr['message']='';
     	$return_arr['status']='1';
     	 if(false == empty($input_params['product_id']))
     	 {
          $this->db->select('iProductId');
          $this->db->from('product');
          //print_r($input_params['review_id']); exit;
          $this->db->where("iProductId", $input_params['product_id']);
          $this->db->where("eStatus", 'Active');
          $product_data=$this->db->get()->result_array();
          if(true == empty($product_data)){
             $return_arr['message']="No products available";
             $return_arr['status'] = "0";
          }else{
          	$return_arr['product_id']=$product_data;
          }
      }
      return $return_arr;
    
  }
}
?>
