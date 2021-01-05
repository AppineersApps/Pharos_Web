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

class Edit_business_working_hours extends Cit_Controller
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
            "edit_business_working_hours",
        );
       
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('edit_business_working_hours_model');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_user_sign_up_email method is used to validate api input params.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_edit_business_working_hours($request_arr = array())
    {
        $valid_arr = array(
          
            "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "business_id_required",
                )
            )
           
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "edit_business_working_hours");

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
    public function start_edit_business_working_hours($request_arr = array(), $inner_api = FALSE)
    {
        try
        {


            
            $validation_res = $this->rules_edit_business_working_hours($request_arr);
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

            
             $input_params = $this->checkuniqueusername($input_params);

            $condition_res = $this->condition($input_params);

            if ($condition_res["success"] && (!empty($input_params['workinghours'])))
            {

          
                $input_params = $this->delete_working_hours($input_params);
                $input_params = $this->create_business_workinghours($input_params);
              
                $output_response = $this->business_update_finish_success($input_params);
                  return $output_response;  

          }
          else
            {



                $output_response = $this->business_update_finish_success_3($input_params);
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
    public function checkuniqueusername($input_params = array())
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
        $input_params["checkuniqueusername"] = $format_arr;

        $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
        return $input_params;
    }

    /**
     * condition method is used to process conditions.
     * @created priyanka chillakuru | 25.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function condition($input_params = array())
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



    public function delete_working_hours($input_params = array())
    {

        $this->block_result = array();
        try
        {

        
            $business_id = isset($input_params["business_id"]) ? $input_params["business_id"] : "";
          
            $this->block_result = $this->users_model->delete_working_hours($business_id);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["delete_working_hours"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }
    

    /**
     * users_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 12.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function business_update_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "business_update_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "edit_business_working_hours";
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
    public function business_update_finish_success_3($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "business_update_finish_success_3",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "edit_business_working_hours";
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
