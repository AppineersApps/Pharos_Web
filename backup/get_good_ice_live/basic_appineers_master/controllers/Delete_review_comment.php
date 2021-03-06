<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Delete Account Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Delete Account
 *
 * @class Delete_account.php
 *
 * @path application\webservice\basic_appineers_master\controllers\Delete_account.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 01.10.2019
 */

class Delete_review_comment extends Cit_Controller
{
    public $settings_params;
    public $output_params;
    public $single_keys;
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
            "delete_review_comment",
        );
        $this->block_result = array();
        $this->load->library('wsresponse');
        $this->load->model('delete_review_comment_model');
         $this->load->model('basic_appineers_master/get_store_review_model');
    }

    /**
     * rules_delete_account method is used to validate api input params.
     * @created priyanka chillakuru | 01.10.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_delete_review_comment($request_arr = array())
    {
        $valid_arr = array(
            "user_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "user_id_required",
                )
            ),
            "review_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "review_id_required",
                )
            )
        );
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "delete_account");

        return $valid_res;
    }

    /**
     * start_delete_account method is used to initiate api execution flow.
     * @created priyanka chillakuru | 01.10.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_delete_review_comment($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_delete_review_comment($request_arr);
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

            $input_params = $this->delete_review_comment($input_params);

            $condition_res = $this->is_deleted($input_params);
            if ($condition_res["success"])
            {
                $input_params =$this->select_review_images($input_params);
                
                $condition_res = $this->is_deleted($input_params);

                if ($condition_res["success"])
                {
                  // $this->select_review_images($input_params);
                    $params_arr["_estatus"] = "Inactive";
                    $params_arr["_dtdeletedat"] = "NOW()";
                    $this->delete_review_comment_model->delete_review_images($params_arr,$input_params);
                }
                $output_response = $this->users_finish_success_1($input_params);
                return $output_response;
            }

            else
            {
                $output_response = $this->users_finish_success($input_params);
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
     * delete_user_account method is used to process query block.
     * @created priyanka chillakuru | 01.10.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function select_review_images($input_params = array())
    {
        $this->block_result = array();
        $review_id = isset($input_params["review_id"]) ? $input_params["review_id"] : "";
        try
        {
            $this->block_result = $this->get_store_review_model->get_image_details($review_id);
           
            $result_arr = $this->block_result["data"];
            if (is_array($result_arr) && count($result_arr) > 0)
            {
                $i = 0;
                    foreach ($result_arr as $data_key => $data_arr)
                    {
                        $data = $data_arr["uri_review_image"];
                        $folder_name="public/upload/review_images/".$review_id."/";

                        $image_path = $folder_name.$data;
                        if (file_exists($image_path)) 
                        {
                             unlink($image_path); 
                        } 
                    }
                    rmdir($folder_name);
               $this->block_result["data"] = $result_arr;
            }
        }
       catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        
        return $result_arr;

    }

    /**
     * delete_user_account method is used to process query block.
     * @created priyanka chillakuru | 01.10.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function delete_review_comment($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["user_id"]))
            {
                $where_arr["user_id"] = $input_params["user_id"];
            }
            if (isset($input_params["review_id"]))
            {
                $where_arr["review_id"] = $input_params["review_id"];
            }
            $params_arr["_estatus"] = "Inactive";
            $params_arr["_dtdeletedat"] = "NOW()";
            $this->block_result = $this->delete_review_comment_model->delete_review_comment($params_arr, $where_arr);
        }
        catch(Exception $e)
        {
            $success = 0;
            $this->block_result["data"] = array();
        }
        $input_params["delete_review_comment"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_deleted method is used to process conditions.
     * @created priyanka chillakuru | 01.10.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_deleted($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = $input_params["affected_rows"];
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
     * users_finish_success_1 method is used to process finish flow.
     * @created priyanka chillakuru | 01.10.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "users_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_review_comment";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 01.10.2019
     * @modified priyanka chillakuru | 01.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function users_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "users_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "delete_review_comment";
        $func_array["function"]["single_keys"] = $this->single_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
}
