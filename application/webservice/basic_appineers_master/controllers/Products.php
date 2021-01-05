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

class Products extends Cit_Controller
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
            "get_products_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('products_model');
    }

    /**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_add_products($request_arr = array())
    {        
        $valid_arr = array(
          "business_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "business_id_required",
                )
            ),
            "products_name" => array(
                array(
                    "rule" => "minlength",
                    "value" => 1,
                    "message" => "products_name_minlength",
                )
            )
        );
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "add_products");

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
    public function start_Products($request_arr = array())
    {
              // get the HTTP method, path and body of the request
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();

        switch ($method) {
          case 'GET':
           $output_response =  $this->get_products($request_arr);
           return  $output_response;
             break;
        
          case 'POST':
                 if(!empty($request_arr['product_id'])){
                        $output_response =  $this->update_products($request_arr);
                         return  $output_response;

                      }else{
                      $output_response =  $this->add_products($request_arr);
                   return  $output_response;
                    }
             break;
          case 'DELETE':
            $output_response = $this->get_deleted_product($request_arr);
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
    public function rules_update_products($request_arr = array())
    {
        
         $valid_arr = array(            
            "business_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "business_id_required",
                )
            ),
            "product_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "product_id_required",
                )
            ),
            );
        
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "update_service");

        return $valid_res;
    }
    /**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_products($request_arr = array())
    {
            if(true == empty($request_arr['product_id']))
                {
                    $valid_arr = array(            
                        "business_id" => array(
                            array(
                                "rule" => "required",
                                "value" => TRUE,
                                "message" => "business_id_required",
                            )
                        )
                    );
                }
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "update_products");

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
    public function get_products($request_arr = array(), $inner_api = FALSE)
    {
      try
        {
            $validation_res = $this->rules_get_products($request_arr);
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

            $input_params = $this->get_all_products($input_params);

            if (!empty($input_params["get_all_products"]))
            {

          
             
                $output_response = $this->get_products_finish_success($input_params);
                return $output_response;
            }
            else
            {
                $output_response = $this->get_products_finish_success_1($input_params);
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
     * start_edit_profile method is used to initiate api execution flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function update_products($request_arr = array(), $inner_api = FALSE)
    {

        try
        {


            $validation_res = $this->rules_update_products($request_arr);
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

            $condition_res = $this->check_products_exist($input_params);


            if ($condition_res["status"])
            {

                $input_params = $this->update_exist_products($input_params);



                //$condition_res = $this->is_details_updated($input_params);
                
                if ($input_params["affected_rows"] > 0)
                {                  
                    $output_response = $this->get_update_finish_success($input_params);
                    return $output_response;
                }
                else
                {
                    $output_response = $this->get_update_finish_success_1($input_params);
                    return $output_response;
                }
            }
            else
            {
                $output_response = $this->get_update_finish_success_1($input_params);
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
     * update_profile method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function update_exist_products($input_params = array())
    {

        $this->block_result = array();
        try
        {
            $params_arr = array();

            if (isset($input_params["product_id"]))
            {
                $params_arr["product_id"] = $input_params["product_id"];
            }
           if (isset($_FILES["product_image"]["name"]) && isset($_FILES["product_image"]["tmp_name"]))
            {
                $sent_file = $_FILES["product_image"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["product_image"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["product_image"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["product_image"]["ext"], $_FILES["product_image"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["product_image"]["size"], $_FILES["product_image"]["size"]))
                    {
                        $images_arr["product_image"]["name"] = $file_name;
                    }
                }
            }
            $params_arr["_dtupdatedat"] = "NOW()";            
            $params_arr["_estatus"] = "Active";

            if (isset($input_params["business_id"]))
            {
                $params_arr["business_id"] = $input_params["business_id"];
            }
            if (isset($input_params["product_name"]))
            {
                $params_arr["product_name"] = $input_params["product_name"];
            }            
            if (isset($input_params["product_price"]))
            {
                $params_arr["product_price"] = $input_params["product_price"];
            }
            if (isset($input_params["product_description"]))
            {
                $params_arr["product_description"] = $input_params["product_description"];
            }
            
            if (isset($images_arr["product_image"]["name"]))
            {
                $params_arr["product_image"] = $images_arr["product_image"]["name"];
            }
  
            
            $this->block_result = $this->products_model->update_product($params_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("updation failed.");
            }            
             $data_arr = $this->block_result["array"];
             $upload_path = $this->config->item("upload_path");
            if (!empty($images_arr["product_image"]["name"]))
            {

                 $folder_name = "pharos/products";             
                
                $temp_file = $_FILES["product_image"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["product_image"]["name"]);
                if (!$res)
                {
                    //file upload failed

                }
            }
            

        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["update_Products"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * get_deleted_product method is used to initiate api execution flow.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function get_deleted_product($request_arr = array())
    {
      try
        {

            $validation_res = $this->rules_get_deleted_product($request_arr);
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
            $output_array = $func_array = array();
            $input_params = $request_arr;
            $condition_res = $this->check_products_exist($input_params);
            if ($condition_res["status"])
            {
               $input_params = $this->delete_product($input_params);
               $output_response = $this->delete_product_finish_success($input_params);
                return $output_response;
            }
            else
            {
                $output_response = $this->delete_product_finish_success_1($input_params);
                return $output_response;
            }
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }



    
      public function rules_get_deleted_product($request_arr = array())
    {
            if(true == empty($request_arr['product_id']))
                {
                    $valid_arr = array(            
                        "product_id" => array(
                            array(
                                "rule" => "required",
                                "value" => TRUE,
                                "message" => "product_id_required",
                            )
                        )
                    );
                }
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "delete_product");

        return $valid_res;
    }

    /**
     * delete product method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function delete_product($input_params = array())
    {
      $this->block_result = array();
        try
        {
            $arrResult = array();
           
            $arrResult['product_id']  = isset($input_params["product_id"]) ? $input_params["product_id"] : "";
           // $arrResult['dtUpdatedAt']  = "NOW()";
            $this->block_result = $this->products_model->delete_product($arrResult);
            if (!$this->block_result["success"])
            {
                throw new Exception("No records found.");
            }
            $result_arr = $this->block_result["data"];
           
          $this->block_result["data"] = $result_arr;
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["delete_product"] = $this->block_result["data"];
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       return $input_params;

    }
    /**
     * checkuniqueusername method is used to process custom function.
     * @created priyanka chillakuru | 25.09.2019
     * @modified saikumar anantham | 08.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function check_products_exist($input_params = array())
    {

        if (!method_exists($this, "checkProductsExist"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkProductsExist($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["checkProductsexist"] = $format_arr;

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
    public function get_updated_products($input_params = array())
    {
//print_r($input_params); exit;
        $this->block_result = array();
        try
        {
            $arrResult = array();
            $arrResult['products_id']  = isset($input_params["products_id"]) ? $input_params["products_id"] : "";   
            $this->block_result = $this->iems_model->get_updated_products($arrResult);
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

                                     
                    /*profile images */
                    $data = $data_arr["image_1"];
                    //echo  $data;exit;image_1
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->product("IMAGE_EXTENSION_ARR"));
                    
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                   
                    $p_key = ($data_arr["products_id"] != "") ? $data_arr["products_id"] : $input_params["products_id"];
                    $image_arr["path"] = "my_market/products_images";
                    $image_arr["pk"] = $p_key;
                    // $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);

                    $result_arr[$data_key]["products_images"] = $data;
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
        $input_params["get_updated_products"] = $this->block_result["data"];
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       return $input_params;
    }


    /**
     * get_review_details method is used to process review block.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_all_products($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $arrResult = array();
           
            $arrResult['business_id']  = isset($input_params["business_id"]) ? $input_params["business_id"] : "";   
            $this->block_result = $this->products_model->get_all_products($arrResult);
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

                    $data = $data_arr["product_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] = "pharos/products";
                   // $image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);

                    $result_arr[$data_key]["product_image"] = $data;

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
        $input_params["get_all_products"] = $this->block_result["data"];
        
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);
       return $input_params;
    }


    public function add_products($input){
        try
        {
         $validation_res = $this->rules_add_products($input);
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

            

            $input_params = $this->set_products($input_params);

           

            $condition_res = $this->is_posted($input_params);

            if ($condition_res["success"])
            {

               $input_params = $this->get_business_details($input_params);
               $input_params = $this->get_user_details_for_send_notifi($input_params);

               $input_params = $this->start_loop_1($input_params);
              
                $output_response = $this->user_products_finish_success($input_params);
                return $output_response;
            }

            else
            {

                $output_response = $this->user_Products_finish_success_1($input_params);
                return $output_response;
            }
            }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        return $output_response;
    }


     public function start_loop_1($input_params = array())
    {
        $this->iterate_start_loop_1($input_params["get_user_details_for_send_notifi"], $input_params);
        return $input_params;
    }




    /**
     * iterate_start_loop_1 method is used to iterate loop.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 31.07.2019
     * @param array $get_near_by_drivers_lp_arr get_near_by_drivers_lp_arr array to iterate loop.
     * @param array $input_params_addr $input_params_addr array to address original input params.
     */
    public function iterate_start_loop_1(&$get_fav_business_user_arr = array(), &$input_params_addr = array())
    {

        $input_params_loc = $input_params_addr;
        $_loop_params_loc = $get_fav_business_user_arr;
        $_lp_ini = 0;
        $_lp_end = count($_loop_params_loc);
        for ($i = $_lp_ini; $i < $_lp_end; $i += 1)
        {
            $get_fav_business_user_pms = $input_params_loc;

            unset($get_fav_business_user_pms["get_user_details_for_send_notifi"]);
            if (is_array($_loop_params_loc[$i]))
            {
                $get_fav_business_user_pms = $_loop_params_loc[$i]+$input_params_loc;
            }
            else
            {
                $get_fav_business_user_pms["get_user_details_for_send_notifi"] = $_loop_params_loc[$i];
                $_loop_params_loc[$i] = array();
                $_loop_params_loc[$i]["get_user_details_for_send_notifi"] = $get_fav_business_user_pms["get_user_details_for_send_notifi"];
            }

            $get_fav_business_user_pms["i"] = $i;
            $input_params = $get_fav_business_user_pms;

            $condition_res = $this->check_receiver_device_token($input_params);
            if ($condition_res["success"])
            {

                $input_params = $this->post_notification($input_params);
                $input_params = $this->push_notification($input_params);
            }

            $get_fav_business_user_arr[$i] = $this->wsresponse->filterLoopParams($input_params, $_loop_params_loc[$i], $get_fav_business_user_pms);
        }
    }


     /**
     * check_receiver_device_token method is used to process conditions.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 27.06.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_receiver_device_token($input_params = array())
    {
        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["u_device_token"];

            $cc_fr_0 = (!is_null($cc_lo_0) && !empty($cc_lo_0) && trim($cc_lo_0) != "") ? TRUE : FALSE;
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
     * post_notification method is used to process query block.
     * @created CIT Dev Team
     * @modified ---
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function post_notification($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $params_arr = array();
            
            $params_arr = array();
         
           $params_arr["notification_message"] = "".$input_params["business_name"]." Added New Product.";
            
            if (isset($input_params["receiver_id"]))
            {
                $params_arr["receiver_id"] = $input_params["receiver_id"];
            }
            if (isset($input_params["user_id"]))
            {
                $params_arr["sender_id"] = $input_params["user_id"];
            }
          
            
            
           $params_arr["_enotificationtype"] = "product";

          
            $params_arr["_dtaddedat"] = "NOW()";
            $params_arr["_dtupdatedat"] = "NOW()";
            $params_arr["eNotificationStatus"] = "active";
            $this->block_result = $this->products_model->post_notification($params_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["post_notification"] = $this->block_result["data"];
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
                    "value" =>"product",
                    "send" => "Yes",
                ),
                array(
                    "key" => "user_id",
                    "value" => $input_params["user_id"],
                    "send" => "Yes",
                ),

                array(
                    "key" => "business_id",
                    "value" => $input_params["business_id"],
                    "send" => "Yes",
                )
            );
            $push_msg = "".$input_params["business_name"]." Added New Product.";
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






     public function get_business_details($input_params = array())
    {

        $this->block_result = array();
        try
        {
            $this->block_result = $this->products_model->get_business_details($input_params);
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
        $input_params["get_business_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }


     public function get_user_details_for_send_notifi($input_params = array())
    {

        $this->block_result = array();
        try
        {
            $this->block_result = $this->products_model->get_user_details_for_send_notifi($input_params);
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
        $input_params["get_user_details_for_send_notifi"] = $this->block_result["data"];
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
    public function set_products($input_params = array())
    {
      //print_r($_FILES); exit;
        $this->block_result = array();
        try
        {
            $params_arr = array();

            if (isset($_FILES["product_image"]["name"]) && isset($_FILES["product_image"]["tmp_name"]))
            {
                $sent_file = $_FILES["product_image"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["product_image"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["product_image"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["product_image"]["ext"], $_FILES["product_image"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["product_image"]["size"], $_FILES["product_image"]["size"]))
                    {
                        $images_arr["product_image"]["name"] = $file_name;
                    }
                }
            }


            
            $params_arr["_dtupdatedat"] = "NOW()";              
            
            $params_arr["_estatus"] = "Active"; 
            if (isset($input_params["business_id"]))
            {
                $params_arr["business_id"] = $input_params["business_id"];
            }
            if (isset($input_params["product_name"]))
            {
                $params_arr["product_name"] = $input_params["product_name"];
            }            
            if (isset($input_params["product_price"]))
            {
                $params_arr["product_price"] = $input_params["product_price"];
            }
            if (isset($input_params["product_description"]))
            {
                $params_arr["product_description"] = $input_params["product_description"];
            }
            

            if (isset($images_arr["product_image"]["name"]))
            {
                $params_arr["product_image"] = $images_arr["product_image"]["name"];
            }
            
            
            $this->block_result = $this->products_model->set_products($params_arr);

            if (!$this->block_result["success"])
            {
                throw new Exception("Insertion failed.");
            }
             $data_arr = $this->block_result["array"];
            $upload_path = $this->config->item("upload_path");
            if (!empty($images_arr["product_image"]["name"]))
            {

                 $folder_name = "pharos/products";             
                
                $temp_file = $_FILES["product_image"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["product_image"]["name"]);
                if (!$res)
                {
                    //file upload failed

                }
            }

        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["set_Products"] = $this->block_result["data"];
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
            $cc_lo_0 = (is_array($input_params["product_id"])) ? count($input_params["product_id"]):$input_params["product_id"];
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
     * is_posted method is used to process conditions.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_fetched($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $cc_lo_0 = $input_params["Products_id"];
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
    public function user_products_finish_success($input_params = array())
    {
        $output_arr['settings']['success'] = "1";
        $output_arr['settings']['message'] = "Products added successfully";
        $output_arr['data'] = "";
        $responce_arr = $this->wsresponse->sendWSResponse($output_arr, array(), "add_Products");

        return $responce_arr;
    }

    /**
     * user_review_finish_success_1 method is used to process finish flow.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function user_products_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "user_products_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "add_products";
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
    public function get_products_finish_success($input_params = array())
    {
       //print_r($input_params); exit;
        $setting_fields = array(
            "success" => "1",
            "message" => "get_products_finish_success",
            "total_count"=> count($input_params["get_all_products"])
        );
        $output_fields = array(
            "product_id",
            "business_id",
            "product_name",
            "product_price",
            "product_description",
            "product_image",
            "added_date"
            
        );
        $output_keys = array(
            'get_all_products',
        );
        $ouput_aliases = array(
            "product_id"=>"product_id",
            "business_id"=>"business_id",
            "product_name" => "product_name",
            "product_price"=> "product_price",
            "product_description"=>"product_description",
            "product_image"=>"product_image",
             "added_date"=>"added_date"
          
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "get_all_products";
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
    public function get_products_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_products_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_all_products";
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
            "Product_image"
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
