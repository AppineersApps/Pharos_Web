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
 
Class Cit_Rated_business extends Rated_business {
  public function __construct()
  {
      parent::__construct();
  }
  public function checkRatingsExist($input_params=array()){
      $return_arr['message']='';
     	$return_arr['status']='1';
     	 if(false == empty($input_params['user_id']))
     	 {
          $this->db->select('iBusinessId');
          $this->db->from('business_rating');
          //print_r($input_params['review_id']); exit;
          $this->db->where("iUserId", $input_params['user_id']);
          //$this->db->where("eStatus", 'Active');
          $business_data=$this->db->get()->result_array();
          if(true == empty($business_data)){
             $return_arr['message']="No ratings available";
             $return_arr['status'] = "0";
          }else{
          	$return_arr['business_id']=$business_data;
          }
      }
      return $return_arr;
    
  }
}
?>
