<?php

   
/**
 * Description of Edit Profile Extended Controller
 * 
 * @module Extended Edit Profile
 * 
 * @class Cit_Edit_profile.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_Edit_profile.php
 * 
 * @author CIT Dev Team
 * 
 * @date 25.09.2019
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Edit_about_business extends Edit_about_business {
        public function __construct()
{
    parent::__construct();
}
public function checkBusinessId($input_params=array()){
    $return_arr['message']='';
    $return_arr['status']='1';
    if(!empty($input_params['business_id'])){
      $this->db->select('iBusinessId');
      $this->db->from('business');
      $this->db->where('iBusinessId',$input_params['business_id']);
       $this->db->where('eStatus','Active');
     $business_data=$this->db->get()->result_array();
          if(true == empty($business_data)){
             $return_arr['message']="No data available";
             $return_arr['status'] = "0";
          }else{
            $return_arr['business_id']=$business_data;
          }
     
    }
   return  $return_arr; 
    
}
}
