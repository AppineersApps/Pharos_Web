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
 
Class Cit_Edit_business extends Edit_business {
        public function __construct()
{
    parent::__construct();
}
public function checkUniqueUser($input_params=array()){

    $return_arr['message']='';
    $return_arr['status']='1';
    $auth_header = $this->input->get_request_header('AUTHTOKEN');


    if ($auth_header != "") {
        $req_token = $auth_header;
    } else {
        $req_token = $input_params['user_access_token'];
    }
    $userid=0;
    if($req_token)
    {
        
        $access = $req_token;
        $this->db->select('u.iUserId,u.eStatus,b.iBusinessId');
        $this->db->from('users as u');
        $this->db->join("business AS b", "u.iUserId = b.iUserId", "left");
        $this->db->where('u.vAccessToken',$access);
        $this->db->where('u.eStatus','Active');
        $result = $this->db->get()->result_array();
        $return_arr['user_id'] = $result[0]['iUserId']; 
         $return_arr['business_id'] = $result[0]['iBusinessId']; 
           $return_arr['eStatus'] = $result[0]['eStatus']; 
          
    }
    if(!empty($return_arr['business_id']) && $return_arr['eStatus'] =='Active'){         
        $request_arr['user_id']=$return_arr['user_id'];
        $request_arr['business_id']=$return_arr['business_id'];
    }else if(!empty($return_arr['business_id']) && $return_arr['eStatus'] =='Inactive'){
        $return_arr['code'] = "401";
         $return_arr['status'] = '0'; 
        $return_arr['message'] = "Your account is deactivated. Please contact administrator.";

    }else{
        $return_arr['code'] = "401";
         $return_arr['status'] = '0'; 
        $return_arr['message'] = "Your business id is valid.";
        
    }  
   
   return  $return_arr; 
    
}

}
