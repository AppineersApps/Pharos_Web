<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Post a Feedback Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Set store review
 *
 * @class set_store_review.php
 *
 * @path application\webservice\basic_appineers_master\controllers\Set_store_review.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Favorites_business extends Cit_Controller
{
    public $settings_params;
    public $output_params;
    public $single_keys;
    public $multiple_keys;
    public $block_result;

    /**
     * __construct method is used to set controller preferences while controller object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->settings_params = array();
        $this->output_params = array();
        $this->single_keys = array(
            "set_products",
            "get_favorites_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('Favorites_business_model');
    }

   

    /**
     * start_set_store_review method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_favorites_business($request_arr = array())
    {
              // get the HTTP method, path and body of the request
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();

        switch ($method) {
          case 'GET':
           $output_response =  $this->get_favorites($request_arr);
           return  $output_response;
             break;  
           
          case 'POST':
              $output_response =  $this->add_favorites($request_arr);
           return  $output_response;
             break;
       
        }
    }
    
    /**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_favorites($request_arr = array())
    {
            if(true == empty($request_arr['product_id']))
                {
                    $valid_arr = array(            
                        "user_id" => array(
                            array(
                                "rule" => "required",
                                "value" => TRUE,
                                "message" => "user_id_required",
                            )
                        )
                    );
                }
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_favorites");

        return $valid_res;
    }

    /**
     * start_set_store_review method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function get_favorites($request_arr = array(), $inner_api = FALSE)
    {
      try
        {
            $validation_res = $this->rules_get_favorites($request_arr);
            if ($validation_res["success"] == "-5")
            {
                if ($inner_api === TRUE)
                { 
                    return $validation_res;
                }
                else
                { 
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
            }
            $output_response = array();
            $input_params = $validation_res['input_params'];
            $output_array = $func_array = array();



            $condition_res = $this->check_favorites_exist($input_params);

            if ($condition_res["status"])
            {
                $input_params = $this->get_all_favorites($input_params);

                $output_response = $this->get_favorites_finish_success($input_params);

                return $output_response;
            }
            else
            {
                $output_response = $this->get_favorites_finish_success_1($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }

   
    /**
     * checkuniqueusername method is used to process custom function.
     * @created priyanka chillakuru | 25.09.2019
     * @modified saikumar anantham | 08.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function check_favorites_exist($input_params = array())
    {

        if (!method_exists($this, "checkFavoritesExist"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkFavoritesExist($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["checkRatingsexist"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }



    /**
     * get_review_details method is used to process review block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_all_favorites($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $arrResult = array();
            $user_id=$input_params['user_id'];

            //$arrResult['business_id']  = isset($input_params["business_id"]) ? $input_params["business_id"] : "";
            $arrResult['user_id']  = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
               
            $this->block_result = $this->Favorites_business_model->get_favorites_details($arrResult);
           // print_r($this->block_result); exit;
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
           if (is_array($result_arr) && count($result_arr) > 0)
            {
                $i = 0;
                foreach ($result_arr as $data_key => $data_arr)
                {
 
           

                    $data = $data_arr["business_profile"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="pharos/business_profile";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["business_profile"] = $data;

                   


                    $i++;
                }
                $this->block_result["data"] = $result_arr;
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_all_favorites"] = $this->block_result["data"];
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

       return $input_params;
    }


     /**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_add_favorites($request_arr = array())
    {        
        $valid_arr = array(
          "business_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "business_id_required",
                )
            ),

           "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                )
            ),

            
        );
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "add_favorites");

        return $valid_res;
    }




     
    public function add_favorites($request_arr = array(), $inner_api = FALSE)
    {
      try
        {

           

            $validation_res = $this->rules_add_favorites($request_arr);
            if ($validation_res["success"] == "-5")
            {
                if ($inner_api === TRUE)
                { 
                    return $validation_res;
                }
                else
                { 
                    $this->wsresponse->sendValidationResponse($validation_res);
                }
            }
            $output_response = array();
            $input_params = $validation_res['input_params'];
            $output_array = $func_array = array();

              $condition_res = $this->check_make_favorites_exist($input_params);

              //unfavorite business
              if($condition_res["status"]==1)
              {

                    $input_params = $this->delete_existing_user_favorites($input_params);

                   if($input_params['affected_rows']==1)
                   {
                      $output_response = $this->business_unfavorites_finish_success($input_params);
                    return $output_response;

                   }else
                   {
                  $output_response = $this->business_unfavorites_finish_success($input_params);
                    return $output_response;

                   }
               
               
              }else
              {///--- //add favorite business
                 $input_params = $this->set_favorites($input_params);

                $condition_res = $this->is_posted($input_params);

                if ($condition_res["success"])
                {


                $input_params = $this->get_user_device_token($input_params);

                $input_params = $this->get_user_details($input_params);

                $input_params = $this->notification_entry($input_params);


             

                if ($input_params["u_device_token"]!=''){ 

                      $input_params = $this->push_notification($input_params);
                 }


                    $output_response = $this->business_favorites_finish_success($input_params);
                    return $output_response;
                }

                else
                {

                    $output_response = $this->business_Favorites_finish_success_1($input_params);
                    return $output_response;
                }
                
              }
            
         } 
            
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }



    public function check_make_favorites_exist($input_params = array())
    {

        if (!method_exists($this, "checkMakeFavoritesExist"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkMakeFavoritesExist($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["checkMakeFavoritesExist"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }


    /**
     * get_user_device_token method is used to process query block.
     * @created Devangi Nirmal | 05.06.2019
     * @modified Devangi Nirmal | 27.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_user_device_token($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $business_id = isset($input_params["business_id"]) ? $input_params["business_id"] : "";
            $this->block_result = $this->Favorites_business_model->get_user_device_token($business_id);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_user_device_token"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }




      /**
     * push_notification method is used to process mobile push notification.
     * @created Devangi Nirmal | 05.06.2019
     * @modified Devangi Nirmal | 30.07.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function push_notification($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $device_id = $input_params["u_device_token"];
            $code = "USER";
            $sound = "";
            $badge = "";
            $title = "";
            $send_vars = array(
                 array(
                    "key" => "type",
                    "value" =>"favorite",
                    "send" => "Yes",
                ),
                array(
                    "key" => "user_id",
                    "value" => $input_params["user_id"],
                    "send" => "Yes",
                )
            );
            $push_msg = "".$input_params["user_name"]." favorite your business.";
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
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        $input_params["push_notification"] = $this->block_result["success"];

        return $input_params;
    }





      /**
     * get_user_device_token method is used to process query block.
     * @created Devangi Nirmal | 05.06.2019
     * @modified Devangi Nirmal | 27.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_user_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $user_id = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
            $this->block_result = $this->Favorites_business_model->get_user_details($user_id);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["get_user_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }



    /**
     * delete_like_and_dislike method is used to process query block.
     * @created Chetan Dvs | 16.05.2019
     * @modified Chetan Dvs | 16.05.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function delete_existing_user_favorites($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $user_id = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
            $business_id = isset($input_params["business_id"]) ? $input_params["business_id"] : "";
          
            $this->block_result = $this->Favorites_business_model->delete_existing_user_favorites($user_id, $business_id);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["delete_existing_user_favorites"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }



      /**
     * notification_entry method is used to process query block.
     * @created Devangi Nirmal | 05.06.2019
     * @modified Devangi Nirmal | 19.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function notification_entry($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = array();
            $params_arr["_vmessage"] = "'".$input_params["user_name"]." favorite your Business'";
            if (isset($input_params["b_user_id"]))
            {
                $params_arr["b_user_id"] = $input_params["b_user_id"];
            }
            $params_arr["_enotificationtype"] = 'favorite';
            $params_arr["_dtaddedat"] = "NOW()";
            $params_arr["_estatus"] = "active";
            if (isset($input_params["user_id"]))
            {
                $params_arr["user_id"] = $input_params["user_id"];
            }
            $this->block_result = $this->Favorites_business_model->notification_entry($params_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["notification_entry"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }


  
     /**
     * set_store_review method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function set_favorites($input_params = array())
    {
      
        $this->block_result = array();
        try
        {
            $params_arr = array();
            
            $user_id=$input_params['user_id'];
             
            $params_arr["user_id"] = $input_params["user_id"];
        
            $params_arr["_dtaddedat"] ="NOW()";
                 
            $params_arr["_estatus"] = "active"; 

            if (isset($input_params["business_id"]))
            {
                $params_arr["business_id"] = $input_params["business_id"];
            }

           
            
            $this->block_result = $this->Favorites_business_model->set_favorites($params_arr);

            if (!$this->block_result["success"])
            {
                throw new Exception("Insertion failed.");
            }
            $data_arr = $this->block_result["data"];
            $business_id=$data_arr["0"]["business_id"];
            

        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["set_Favorites"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
        return $input_params;
    }

    /**
     * is_posted method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    

     public function is_posted($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["insert_id"];
            $cc_ro_0 = 0;

            $cc_fr_0 = ($cc_lo_0 > $cc_ro_0) ? TRUE : FALSE;
            if (!$cc_fr_0)
            {
                throw new Exception("Some conditions does not match.");
            }
            $success = 1;
            $message = "Conditions matched.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        return $this->block_result;
    }


    /**
     * user_review_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function business_unfavorites_finish_success($input_params = array())
    {
        $output_arr['settings']['success'] = "1";
        $output_arr['settings']['message'] = "Unfavorites Business successfully";
        $output_arr['data'] = "";
        $responce_arr = $this->wsresponse->sendWSResponse($output_arr, array(), "set_favorites");

        return $responce_arr;
    }


    /**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function business_unfavorites_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "business_unfavorites_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "set_favorites";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }


    /**
     * user_review_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function business_favorites_finish_success($input_params = array())
    {
        $output_arr['settings']['success'] = "1";
        $output_arr['settings']['message'] = "Favorites added successfully";
        $output_arr['data'] = "";
        $responce_arr = $this->wsresponse->sendWSResponse($output_arr, array(), "set_favorites");

        return $responce_arr;
    }

    /**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function business_favorites_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "business_favorites_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "set_favorites";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
     /**
     * user_review_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function get_favorites_finish_success($input_params = array())
    {
       //print_r($input_params); exit;
        $setting_fields = array(
            "success" => "1",
            "message" => "get_favorites_finish_success",
            "total_count"=> count($input_params["get_all_favorites"])
        );
        $output_fields = array(
            "business_id",
            "business_name",
            "business_type",
            "address",
            "business_profile",
            "average_rating",
            
        );
        $output_keys = array(
            'get_all_favorites',
        );
        $ouput_aliases = array(
            "business_id"=>"business_id",
            "business_name"=>"business_name",
            "business_type" => "business_type",
             "address" => "address",
               "business_profile" => "business_profile",
                 "average_rating" => "average_rating",
          
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "get_all_favorites";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);
        
        return $responce_arr;
    }

    /**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function get_favorites_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_favorites_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_all_favorites";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

     /**
     * user_review_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function get_update_finish_success($input_params = array())
    {
       
        $setting_fields = array(
            "success" => "1",
            "message" => "get_update_finish_success"
        );
        $output_fields = array(
            /*"Products_id",
            "Products_name",
            "total_amount",
            "payable_amount",
            "vendors",
            "user_id"*/
            "product_id",
            "business_id",
            "product_name",
            "product_price",
            "product_description",
            "image_1"
        );
        $output_keys = array(
            'get_all_Products',
        );
        $ouput_aliases = array(
            /*"review_id"=>"Products_id",
            "Products_name" => "Products_name",
            "total_amount"=>"total_amount",
            "payable_amount"=> "payable_amount",
            "vendors"=>"vendors"*/
             "product_id"=>"product_id",
            "business_id"=>"business_id",
            "product_name" => "product_name",
            "product_price"=> "product_price",
            "product_description"=>"product_description",
            "image_1"=>"image_1"
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "update_Products";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);
        
        return $responce_arr;
    }

    /**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function get_update_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_update_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "update_Products";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

     /**
     * delete_product_finish_success method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function delete_product_finish_success($input_params = array())
    {
     $setting_fields = array(
            "success" => "1",
            "message" => "delete_product_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_product";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
    /**
     * delete_vendors_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function delete_product_finish_success_1($input_params = array())
    {
     $setting_fields = array(
            "success" => "0",
            "message" => "delete_product_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_product";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
