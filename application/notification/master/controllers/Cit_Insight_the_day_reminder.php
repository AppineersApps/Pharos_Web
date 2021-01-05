<?php
  

/**
 * Description of check_subscription_status_v1 Extended Controller
 * 
 * @module Extended check_subscription_status_v1
 * 
 * @class Cit_Check_subscription_status_v1.php
 * 
 * @path application
otification\master\controllers\Cit_Check_subscription_status_v1.php
 * 
 * @author CIT Dev Team
 * 
 * @date 27.04.2020
 */        

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
 
Class Cit_Insight_the_day_reminder extends Insight_the_day_reminder {
        public function __construct()
{
    parent::__construct();
}
public function sendPushNotification($input_params = array()){
    $return_arr =array();   
    $return_arr['success'] = '0';
    
    if(!empty($input_params['fetch_the_business_users'])) {
        foreach($input_params['fetch_the_business_users'] as $data) {


          $device_id = $data["u_device_token"];
            $code = "USER";
            $sound = "";
            $badge = "";
            $title = "";
            $send_vars = array(
                 array(
                    "key" => "type",
                    "value" =>"insight",
                    "send" => "Yes",
                ),
                
                array(
                    "key" => "business_id",
                    "value" => $data["business_id"],
                    "send" => "Yes",
                )
            );
            $push_msg = "".$data["u_business_name"]." Insight the day.";
            $push_msg = $this->general->getReplacedInputParams($push_msg, $input_params);
            $send_mode = "runtime";

            $send_arr = array();
            $send_arr['device_id'] = $device_id;
            $send_arr['code'] = $code;
            $send_arr['sound'] = $sound;
            $send_arr['badge'] = intval($badge);
            $send_arr['title'] = $title;
            $send_arr['message'] = $push_msg;
            $send_arr['variables'] = json_encode($send_vars);
            $send_arr['send_mode'] = $send_mode;
           
            $uni_id = $this->general->insertPushNotification($send_arr);
            if (!$uni_id)
            {
                throw new Exception('Failure in insertion of push notification batch entry.');
            }

            $success = 1;
            $message = "Push notification send succesfully.";
            
        }
    }
    return $return_arr;
}
}
