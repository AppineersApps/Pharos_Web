<?php

   
/**
 * Description of User Sign Up Email Extended Controller
 * 
 * @module Extended User Sign Up Email
 * 
 * @class Cit_User_sign_up_email.php
 * 
 * @path application\webservice\basic_appineers_master\controllers\Cit_User_sign_up_email.php
 * 
 * @author CIT Dev Team
 * 
 * @date 10.02.2020
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Get_near_business_v1 extends Get_near_business_v1 {
        public function __construct()
{
    parent::__construct();
}
public function prepareDistanceQuery($input_params=array()){

       
 
      $user_latitude    =   $input_params['Latitude'];
      $user_longitude   =   $input_params['Longitude'];
      if(!empty($user_longitude) && !empty($user_latitude))
      {

        $distance = "
            3959 * acos (
              cos ( radians($user_latitude) )
              * cos( radians( bl.dLatitude ) )
              * cos( radians( bl.dLongitude ) - radians($user_longitude))
              + sin ( radians($user_latitude) )
              * sin( radians( bl.dLatitude ))
            )";
        
      }else{
           //distance filter
        $distance= 'IF(1=1,"","")'; 
      }
      
      $return_arr['distance']=$distance;

  
    
      return $return_arr;
}

}
