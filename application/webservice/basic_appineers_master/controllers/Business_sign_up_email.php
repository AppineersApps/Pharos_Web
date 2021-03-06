<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Sign Up Email Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module User Sign Up Email
 *
 * @class User_sign_up_email.php
 *
 * @path application\webservice\basic_appineers_master\controllers\User_sign_up_email.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.02.2020
 */

class Business_sign_up_email extends Cit_Controller
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
            "create_business",
            "get_user_details",
        );
        $this->multiple_keys = array(
            "format_email_v4",
            "custom_function",
            "email_verification_code",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('business_sign_up_email_model');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_user_sign_up_email method is used to validate api input params.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_business_sign_up_email($request_arr = array())
    {
        $valid_arr = array(
            "business_name" => array(
                array(
                    "rule" => "minlength",
                    "value" => 1,
                    "message" => "business_name_minlength",
                ),
                array(
                    "rule" => "maxlength",
                    "value" => 80,
                    "message" => "business_name_maxlength",
                )
            ),
           
            "email" => array(
                array(
                    "rule" => "email",
                    "value" => TRUE,
                    "message" => "email_email",
                )
            ),
            
            "password" => array(
                array(
                    "rule" => "minlength",
                    "value" => 6,
                    "message" => "password_minlength",
                ),
                array(
                    "rule" => "maxlength",
                    "value" => 15,
                    "message" => "password_maxlength",
                )
            ),
            
            "device_type" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "device_type_required",
                )
            ),
            "device_model" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "device_model_required",
                )
            ),
            "device_os" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "device_os_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "business_sign_up_email");

        return $valid_res;
    }

    /**
     * start_user_sign_up_email method is used to initiate api execution flow.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_business_sign_up_email($request_arr = array(), $inner_api = FALSE)
    {
        try
        {


            
            $validation_res = $this->rules_business_sign_up_email($request_arr);
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

            $input_params = $this->format_email_v4($input_params);
           
            $input_params = $this->custom_function($input_params);

            $condition_res = $this->check_status($input_params);

            if ($condition_res["success"])
            {

                $input_params = $this->email_verification_code($input_params);


                $input_params = $this->create_business($input_params);

                $condition_res = $this->is_business_created($input_params);
                if ($condition_res["success"])
                {
                    
                     $input_params = $this->create_business_profile($input_params);



                      $condition_res = $this->is_business_profile_created($input_params);
                      if ($condition_res["success"])
                     {

                        $input_params = $this->create_business_workinghours($input_params);
                       
                        $input_params = $this->get_business_details($input_params);

                    
                        $input_params = $this->email_notification($input_params);

                        $output_response = $this->users_finish_success($input_params);
                        return $output_response;
                    }else
                    {

                        $output_response = $this->users_finish_success_2($input_params);
                        return $output_response;
                    }
                }

                else
                {

                    $output_response = $this->users_finish_success_1($input_params);
                    return $output_response;
                }
            }

            else
            {

                $output_response = $this->finish_success_1($input_params);
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
     * format_email_v4 method is used to process custom function.
     * @created priyanka chillakuru | 07.11.2019
     * @modified saikumar anantham | 07.11.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function format_email_v4($input_params = array())
    {
        if (!method_exists($this->general, "format_email"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->general->format_email($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["format_email_v4"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * custom_function method is used to process custom function.
     * @created priyanka chillakuru | 12.09.2019
     * @modified Devangi Nirmal | 10.02.2020
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function custom_function($input_params = array())
    {
        if (!method_exists($this, "checkUniqueUser"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkUniqueUser($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["custom_function"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * check_status method is used to process conditions.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_status($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["status"];
            $cc_ro_0 = 1;

            $cc_fr_0 = ($cc_lo_0 == $cc_ro_0) ? TRUE : FALSE;
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
     * email_verification_code method is used to process custom function.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function email_verification_code($input_params = array())
    {
        if (!method_exists($this->general, "prepareEmailVerificationCode"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->general->prepareEmailVerificationCode($input_params);
        }
        $format_arr = $result_arr;

        $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
        $input_params["email_verification_code"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * create_user method is used to process query block.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function create_business($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = array();
          
           
            if (isset($input_params["email"]))
            {
                $params_arr["email"] = $input_params["email"];
            }
            
            $params_arr["user_type"] ='business';
            
           
            if (isset($input_params["password"]))
            {
                $params_arr["password"] = $input_params["password"];
            }
            if (method_exists($this->general, "encryptCustomerPassword"))
            {
                $params_arr["password"] = $this->general->encryptCustomerPassword($params_arr["password"], $input_params);
            }
             if (isset($input_params["address"]))
            {
                $params_arr["address"] = $input_params["address"];
            }
            if (isset($input_params["city"]))
            {
                $params_arr["city"] = $input_params["city"];
            }
            if (isset($input_params["latitude"]))
            {
                $params_arr["latitude"] = $input_params["latitude"];
            }
            if (isset($input_params["longitude"]))
            {
                $params_arr["longitude"] = $input_params["longitude"];
            }
            if (isset($input_params["state_id"]))
            {
                $params_arr["state_id"] = $input_params["state_id"];
            }
            if (isset($input_params["state_name"]))
            {
                $params_arr["state_name"] = $input_params["state_name"];
            }
            if (isset($input_params["zipcode"]))
            {
                $params_arr["zipcode"] = $input_params["zipcode"];
            }
    
            $params_arr["status"] = "Inactive";
            $params_arr["_dtaddedat"] = "NOW()";
            if (isset($input_params["device_type"]))
            {
                $params_arr["device_type"] = $input_params["device_type"];
            }
            if (isset($input_params["device_model"]))
            {
                $params_arr["device_model"] = $input_params["device_model"];
            }
            if (isset($input_params["device_os"]))
            {
                $params_arr["device_os"] = $input_params["device_os"];
            }
            if (isset($input_params["device_token"]))
            {
                $params_arr["device_token"] = $input_params["device_token"];
            }
            $params_arr["_eemailverified"] = "No";
            if (isset($input_params["email_confirmation_code"]))
            {
                $params_arr["email_confirmation_code"] = $input_params["email_confirmation_code"];
            }
            $params_arr["_vtermsconditionsversion"] = '{%REQUEST.terms_conditions_version%}';
            if (method_exists($this, "getTermsConditionVersion"))
            {
                $params_arr["_vtermsconditionsversion"] = $this->getTermsConditionVersion($params_arr["_vtermsconditionsversion"], $input_params);
            }
            $params_arr["_vprivacypolicyversion"] = '{%REQUEST.privacy_policy_version%}';
            if (method_exists($this, "getPrivacyPolicyVersion"))
            {
                $params_arr["_vprivacypolicyversion"] = $this->getPrivacyPolicyVersion($params_arr["_vprivacypolicyversion"], $input_params);
            }
            $this->block_result = $this->users_model->create_business($params_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("Insertion failed.");
            }
            $data_arr = $this->block_result["array"];
            /*$upload_path = $this->config->item("upload_path");
            if (!empty($images_arr["business_profile"]["name"]))
            {

                $folder_name = "pharos/business_profile";             
                
                $temp_file = $_FILES["business_profile"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["business_profile"]["name"]);
                if ($upload_arr[0] == "")
                {
                    //file upload failed

                }
            }

            if (!empty($images_arr["image1"]["name"]))
            {

                $folder_name = "pharos/business_profile";             
                
                $temp_file = $_FILES["image1"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image1"]["name"]);
                if ($upload_arr[0] == "")
                {
                    //file upload failed

                }
            }

            if (!empty($images_arr["image2"]["name"]))
            {

                $folder_name = "pharos/business_profile";             
                
                $temp_file = $_FILES["image2"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image2"]["name"]);
                if ($upload_arr[0] == "")
                {
                    //file upload failed

                }
            }

            if (!empty($images_arr["image3"]["name"]))
            {

                $folder_name = "pharos/business_profile";             
                
                $temp_file = $_FILES["image3"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image3"]["name"]);
                if ($upload_arr[0] == "")
                {
                    //file upload failed

                }
            }

            if (!empty($images_arr["image4"]["name"]))
            {

                $folder_name = "pharos/business_profile";             
                
                $temp_file = $_FILES["image4"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image4"]["name"]);
                if ($upload_arr[0] == "")
                {
                    //file upload failed

                }
            }*/
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["create_business"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }


    /**
     * create_user method is used to process query block.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function create_business_profile($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = array();
            if (isset($_FILES["business_profile"]["name"]) && isset($_FILES["business_profile"]["tmp_name"]))
            {
                $sent_file = $_FILES["business_profile"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["business_profile"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["business_profile"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["business_profile"]["ext"], $_FILES["business_profile"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["business_profile"]["size"], $_FILES["business_profile"]["size"]))
                    {
                        $images_arr["business_profile"]["name"] = $file_name;
                    }
                }
            }

            //image1

            if (isset($_FILES["image1"]["name"]) && isset($_FILES["image1"]["tmp_name"]))
            {
                $sent_file = $_FILES["image1"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["image1"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["image1"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["image1"]["ext"], $_FILES["image1"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["image1"]["size"], $_FILES["image1"]["size"]))
                    {
                        $images_arr["image1"]["name"] = $file_name;
                    }
                }
            }

            //image2
            if (isset($_FILES["image2"]["name"]) && isset($_FILES["image2"]["tmp_name"]))
            {
                $sent_file = $_FILES["image2"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["image2"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["image2"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["image2"]["ext"], $_FILES["image1"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["image2"]["size"], $_FILES["image2"]["size"]))
                    {
                        $images_arr["image2"]["name"] = $file_name;
                    }
                }
            }
            //image3
            if (isset($_FILES["image3"]["name"]) && isset($_FILES["image3"]["tmp_name"]))
            {
                $sent_file = $_FILES["image3"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["image3"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["image3"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["image3"]["ext"], $_FILES["image1"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["image3"]["size"], $_FILES["image3"]["size"]))
                    {
                        $images_arr["image3"]["name"] = $file_name;
                    }
                }
            }
            //image4

            if (isset($_FILES["image4"]["name"]) && isset($_FILES["image4"]["tmp_name"]))
            {
                $sent_file = $_FILES["image4"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["image4"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["image4"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["image4"]["ext"], $_FILES["image1"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["image4"]["size"], $_FILES["image4"]["size"]))
                    {
                        $images_arr["image4"]["name"] = $file_name;
                    }
                }
            }

            if (isset($input_params["insert_id"]))
            {
                $params_arr["user_id"] = $input_params["insert_id"];
            }

            if (isset($input_params["business_name"]))
            {
                $params_arr["business_name"] = $input_params["business_name"];
            }
           
            if (isset($images_arr["business_profile"]["name"]))
            {
                $params_arr["business_profile"] = $images_arr["business_profile"]["name"];
            }

            if (isset($images_arr["image1"]["name"]))
            {
                $params_arr["image1"] = $images_arr["image1"]["name"];
            }

            if (isset($images_arr["image2"]["name"]))
            {
                $params_arr["image2"] = $images_arr["image2"]["name"];
            }

            if (isset($images_arr["image3"]["name"]))
            {
                $params_arr["image3"] = $images_arr["image3"]["name"];
            }

              if (isset($images_arr["image4"]["name"]))
            {
                $params_arr["image4"] = $images_arr["image4"]["name"];
            }


            if (isset($input_params["business_type_id"]))
            {
                $params_arr["business_type_id"] = $input_params["business_type_id"];
            }

            if (isset($input_params["current_time_zone"]))
            {
                $params_arr["current_time_zone"] = $input_params["current_time_zone"];
            }

             
           
            $params_arr["_dtaddedat"] = "NOW()";
            
            $this->block_result = $this->users_model->create_business_profile($params_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("Insertion failed.");
            }
            $data_arr = $this->block_result["array"];
            $upload_path = $this->config->item("upload_path");
            if (!empty($images_arr["business_profile"]["name"]))
            {

                $folder_name = "pharos/business_profile";             
                
                $temp_file = $_FILES["business_profile"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["business_profile"]["name"]);
                if ($upload_arr[0] == "")
                {
                    //file upload failed

                }
            }

            if (!empty($images_arr["image1"]["name"]))
            {

                $folder_name = "pharos/business_profile";             
                
                $temp_file = $_FILES["image1"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image1"]["name"]);
                if ($upload_arr[0] == "")
                {
                    //file upload failed

                }
            }

            if (!empty($images_arr["image2"]["name"]))
            {

                $folder_name = "pharos/business_profile";             
                
                $temp_file = $_FILES["image2"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image2"]["name"]);
                if ($upload_arr[0] == "")
                {
                    //file upload failed

                }
            }

            if (!empty($images_arr["image3"]["name"]))
            {

                $folder_name = "pharos/business_profile";             
                
                $temp_file = $_FILES["image3"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image3"]["name"]);
                if ($upload_arr[0] == "")
                {
                    //file upload failed

                }
            }

            if (!empty($images_arr["image4"]["name"]))
            {

                $folder_name = "pharos/business_profile";             
                
                $temp_file = $_FILES["image4"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["image4"]["name"]);
                if ($upload_arr[0] == "")
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
        $input_params["create_business_profile"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }




      public function create_business_workinghours($input_params = array())
    {
       
        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["business_id"]))
            {
                $params_arr["business_id"] = $input_params["business_id"];
            }
            $return_arr = array();
            $insert_arr = array();
            $temp_var   = 0;
            if (false == empty($input_params["workinghours"]))
            {
                $params_arr["workinghours"] =json_decode($input_params["workinghours"],true);
                if(true == empty($params_arr["workinghours"])){
                 $params_arr["workinghours"] =$input_params["workinghours"];
                }
            }
            foreach($params_arr["workinghours"] as $key=>$value)
            {

                $insert_arr[$key]['iBusinessId']=$params_arr["business_id"];
                $insert_arr[$key]['vDay']=$value["day"];
                $insert_arr[$key]['vStartAt']=$value["start_time"];
                $insert_arr[$key]['vCloseAt']=$value["end_time"];
                $insert_arr[$key]['dCreatedOn']=date('Y-m-d H:i:s');
               
                
            }
            
             $this->block_result = $this->users_model->create_business_workinghours($insert_arr,$params_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("updation failed.");
            }
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["create_business_workinghours"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    

    /**
     * is_user_created method is used to process conditions.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_business_created($input_params = array())
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

     public function is_business_profile_created($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["business_id"];
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
     * get_user_details method is used to process query block.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_business_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $insert_id = isset($input_params["insert_id"]) ? $input_params["insert_id"] : "";
            $this->block_result = $this->users_model->get_business_details($insert_id);
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

                    $data = $data_arr["u_profile_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $dest_path = "business_profile";
                    //$image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $image_arr["path"] ="pharos/business_profile";
                    $data = $this->general->get_image_aws($image_arr);

                    $result_arr[$data_key]["u_profile_image"] = $data;

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
        $input_params["get_business_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * email_notification method is used to process email notification.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 12.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function email_notification($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $email_arr["vEmail"] = $input_params["email"];

            $email_arr["vUsername"] = $input_params["business_name"];
            $email_arr["email_confirmation_link"] = $input_params["email_confirmation_link"];

            $success = $this->general->sendMail($email_arr, "SIGNUP_EMAIL_CONFIRMATION", $input_params);

            $log_arr = array();
            $log_arr['eEntityType'] = 'General';
            $log_arr['vReceiver'] = is_array($email_arr["vEmail"]) ? implode(",", $email_arr["vEmail"]) : $email_arr["vEmail"];
            $log_arr['eNotificationType'] = "EmailNotify";
            $log_arr['vSubject'] = $this->general->getEmailOutput("subject");
            $log_arr['tContent'] = $this->general->getEmailOutput("content");
            if (!$success)
            {
                $log_arr['tError'] = $this->general->getNotifyErrorOutput();
            }
            $log_arr['dtSendDateTime'] = date('Y-m-d H:i:s');
            $log_arr['eStatus'] = ($success) ? "Executed" : "Failed";
            $this->general->insertExecutedNotify($log_arr);
            if (!$success)
            {
                throw new Exception("Failure in sending mail.");
            }
            $success = 1;
            $message = "Email notification send successfully.";
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->block_result["success"] = $success;
        $this->block_result["message"] = $message;
        $input_params["email_notification"] = $this->block_result["success"];

        return $input_params;
    }

    /**
     * users_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "users_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_sign_up_email";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success_1 method is used to process finish flow.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "users_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_sign_up_email";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * finish_success_1 method is used to process finish flow.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "user_sign_up_email";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
