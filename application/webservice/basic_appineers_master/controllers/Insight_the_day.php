<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Get Matched Users Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Get Matched Users
 *
 * @class Get_matched_users.php
 *
 * @path application\webservice\user\controllers\Get_matched_users.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 14.06.2019
 */

class Insight_the_day extends Cit_Controller
{
    public $settings_params;
    public $output_params;
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
        $this->multiple_keys = array(
            "Insight_the_day_count",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('Insight_the_day_model');
        //$this->load->model("posts/likes_model");
        //$this->load->model("user/user_images_ws_model");
    }

    /**
     * rules_get_matched_users method is used to validate api input params.
     * @created saikrishna bellamkonda | 21.05.2019
     * @modified Devangi Nirmal | 14.06.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_insight_the_day($request_arr = array())
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
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_matched_count");

        return $valid_res;
    }

    /**
     * start_get_matched_users method is used to initiate api execution flow.
     * @created saikrishna bellamkonda | 21.05.2019
     * @modified Devangi Nirmal | 14.06.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_insight_the_day($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_insight_the_day($request_arr);
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

          

             if ($condition_res["success"])
            {
      
                    $input_params = $this->get_all_count($input_params);

                    if(!empty($input_params['get_all_count']))
                    {

                        $output_response = $this->finish_success($input_params);
                        return $output_response;
                    }

                    else
                    {

                        $output_response = $this->finish_success1($input_params);
                        return $output_response;
                    }
                }

            else
            {

                $output_response = $this->finish_success1($input_params);
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

    /**
     * get_matched_users method is used to process query block.
     * @created saikrishna bellamkonda | 21.05.2019
     * @modified Devangi Nirmal | 10.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_all_count($input_params = array())
    {

        $this->block_result = array();
        try
        {

            
            $this->block_result = $this->Insight_the_day_model->get_all_count($input_params);
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
        $input_params["get_all_count"] = $this->block_result["data"];

        return $input_params;
    }

    
    /**
     * likes_finish_success method is used to process finish flow.
     * @created saikrishna bellamkonda | 21.05.2019
     * @modified Devangi Nirmal | 14.06.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "get_finish_success",
        );
        $output_fields = array(
            'profile_visits',
            'users_navigated',
            'ratings_received',
            
        );
        $output_keys = array(
            'get_all_count',
        );
        $ouput_aliases = array(
            "profile_visits" => "profile_visits",
            "users_navigated" => "users_navigated",
            "ratings_received" => "ratings_received",
            
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_all_count";
        $func_array["function"]["output_keys"] = $output_keys;


        $func_array["function"]["output_alias"] = $ouput_aliases;

        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        

        return $responce_arr;
    }

    /**
     * likes_finish_success_1 method is used to process finish flow.
     * @created saikrishna bellamkonda | 21.05.2019
     * @modified saikrishna bellamkonda | 21.05.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function finish_success1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "finish_success1",
        );
        $output_fields = array();

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_all_count";
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

   
}
