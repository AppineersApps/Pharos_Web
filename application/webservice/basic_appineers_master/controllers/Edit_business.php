<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Edit Profile Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module Edit Profile
 *
 * @class Edit_profile.php
 *
 * @path application\webservice\basic_appineers_master\controllers\Edit_profile.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 23.12.2019
 */

class Edit_business extends Cit_Controller
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
            "update_profile",
            "get_updated_details",
        );
        $this->multiple_keys = array(
            "checkuniqueusername",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('edit_business_model');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_edit_profile method is used to validate api input params.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_edit_business($request_arr = array())
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
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "edit_business");

        return $valid_res;
    }

    /**
     * start_edit_profile method is used to initiate api execution flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_edit_business($request_arr = array(), $inner_api = FALSE)
    {
        try
        {



            $validation_res = $this->rules_edit_business($request_arr);
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



                $input_params = $this->update_profile($input_params);

                 $input_params = $this->update_business_profile($input_params);

               

                if(!empty($input_params['workinghours'])){
                 $input_params = $this->delete_working_hours($input_params);
                $input_params = $this->create_business_workinghours($input_params);
               }
           

                $condition_res = $this->is_details_updated($input_params);
                if ($condition_res["success"])
                {

                   
                     $input_params = $this->get_business_profile_details($input_params);

                  

                    $condition_res = $this->check_user_exists($input_params);
                    if ($condition_res["success"])
                    {

                        $output_response = $this->business_update_finish_success_2($input_params);
                        return $output_response;
                    }

                    else
                    {

                        $output_response = $this->business_update_finish_success_1($input_params);
                        return $output_response;
                    }
                }

                else
                {

                    $output_response = $this->business_update_finish_success($input_params);
                    return $output_response;
                }
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
     * get_user_login_details method is used to process query block.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_business_profile_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $business_id = isset($input_params["business_id"]) ? $input_params["business_id"] : "";
            
            $this->block_result = $this->users_model->get_business_profile_details($business_id);
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

                   
            $arrShareData = $this->get_workinghourse_business_details($data_arr["business_id"]);

            $result_arr[$data_key]["workinghours"] = $arrShareData;


            


                    $data = $data_arr["b_profile_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="pharos/business_profile";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["b_profile_image"] = $data;

                     $data = $data_arr["b_image1"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="pharos/business_profile";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["b_image1"] = $data;

                     $data = $data_arr["b_image2"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="pharos/business_profile";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["b_image2"] = $data;

                      $data = $data_arr["b_image3"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="pharos/business_profile";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["b_image3"] = $data;

                     $data = $data_arr["b_image4"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="pharos/business_profile";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $result_arr[$data_key]["b_image4"] = $data;


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
        $input_params["get_business_profile_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }




     public function get_workinghourse_business_details($business_id)
    {



      $arrShareResult = array();
      $arrShareResult = $this->users_model->get_workinghourse_business_details($business_id);
       $result_arr =  $arrShareResult["data"];
      $arrShareResult['data'] = $result_arr;
      return $arrShareResult['data'] ;
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
     * update_profile method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function update_profile($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["user_id"]))
            {
                $where_arr["user_id"] = $input_params["user_id"];
            }
            /*if (isset($_FILES["user_profile"]["name"]) && isset($_FILES["user_profile"]["tmp_name"]))
            {
                $sent_file = $_FILES["user_profile"]["name"];
            }
            else
            {
                $sent_file = "";
            }
            if (!empty($sent_file))
            {
                list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                $images_arr["user_profile"]["ext"] = implode(',', $this->config->item('IMAGE_EXTENSION_ARR'));
                $images_arr["user_profile"]["size"] = "102400";
                if ($this->general->validateFileFormat($images_arr["user_profile"]["ext"], $_FILES["user_profile"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["user_profile"]["size"], $_FILES["user_profile"]["size"]))
                    {
                        $images_arr["user_profile"]["name"] = $file_name;
                    }
                }
            }*/
            
           
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
            $params_arr["_dtupdatedat"] = "NOW()";
           
            $this->block_result = $this->users_model->update_profile($params_arr, $where_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("updation failed.");
            }
            $data_arr = $this->block_result["array"];
            /*$upload_path = $this->config->item("upload_path");
            if (!empty($images_arr["user_profile"]["name"]))
            {

                 $folder_name = "pharos/user_profile";             
                
                $temp_file = $_FILES["user_profile"]["tmp_name"];
                $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["user_profile"]["name"]);
                if (!$res)
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
        $input_params["update_profile"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }



     /**
     * update_profile method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function update_business_profile($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $params_arr = $where_arr = array();
            if (isset($input_params["business_id"]))
            {
                $where_arr["business_id"] = $input_params["business_id"];
            }
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
                if ($this->general->validateFileFormat($images_arr["image2"]["ext"], $_FILES["image2"]["name"]))
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
                if ($this->general->validateFileFormat($images_arr["image3"]["ext"], $_FILES["image3"]["name"]))
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
                if ($this->general->validateFileFormat($images_arr["image4"]["ext"], $_FILES["image4"]["name"]))
                {
                    if ($this->general->validateFileSize($images_arr["image4"]["size"], $_FILES["image4"]["size"]))
                    {
                        $images_arr["image4"]["name"] = $file_name;
                    }
                }
            }
           
            if (isset($input_params["business_name"]))
            {
                $params_arr["business_name"] = $input_params["business_name"];
            }
            if (isset($input_params["business_type_id"]))
            {
                $params_arr["business_type_id"] = $input_params["business_type_id"];
            }

            if (isset($input_params["about_business"]))
            {
                $params_arr["about_business"] = $input_params["about_business"];
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
            
            $params_arr["_dtupdatedat"] = "NOW()";




           
            $this->block_result = $this->users_model->update_business_profile($params_arr, $where_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("updation failed.");
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
        $input_params["update_business_profile"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * is_details_updated method is used to process conditions.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function is_details_updated($input_params = array())
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
     * get_updated_details method is used to process query block.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_updated_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $user_id = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
            $this->block_result = $this->users_model->get_updated_details($user_id);
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
                    $image_arr["path"] = "pharos/user_profile";
                    //$image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image_aws($image_arr);
                    //print_r($data); exit;
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
        $input_params["get_updated_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }

    /**
     * check_user_exists method is used to process conditions.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_user_exists($input_params = array())
    {

        $this->block_result = array();
        try
        {

            $cc_lo_0 = (empty($input_params["get_business_profile_details"]) ? 0 : 1);
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
     * users_finish_success_3 method is used to process finish flow.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function business_update_finish_success_2($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "business_update_finish_success_2",
        );
        $output_fields = array(
            'business_id',
            'business_type',
            'business_type_id',
            'business_name',           
            'b_email',         
            'b_profile_image',
            'b_image1',
           'b_image2',
           'b_image3',
            'b_image4',
            'b_address',
            'b_city',
            'b_latitude',
            'b_longitude',
            'b_state_name',
            'about_business',
            'b_zip_code',     
            'workinghours',
           
          

        );
        $output_keys = array(
            'get_business_profile_details',
        );
        $ouput_aliases = array(
            "get_business_details" => "get_business_details",
            "business_id" => "business_id",
            "business_type_id" => "business_type_id",
            "business_type" => "business_type",
            "business_name" => "business_name",
            "b_profile_image" => "business_profile",
            "b_image1" => "image1",
            "b_image2" => "image2",
            "b_image3" => "image3",
            "b_image4" => "image4",
            "b_email" => "email",
            "b_address" => "address",
            "b_state_name" => "state_name",
            "b_city" => "city",
            "b_latitude" => "latitude",
            "b_longitude" => "longitude",
            "average_rating" => "average_rating",
            "userCount" => "user_count",
             "about_business" => "about_business",
            "b_zip_code" => "zipcode",
               "isFavorite" => "isFavorite",
            "workinghours" => "workinghours",
            "products" => "products",
            //"ms_state" => "state",
          
         
         
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_business_profile_details";
        $func_array["function"]["output_keys"] = $output_keys;
        $func_array["function"]["output_alias"] = $ouput_aliases;
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }



    /**
     * users_finish_success_1 method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function business_update_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "business_update_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "edit_business";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success method is used to process finish flow.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 18.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function business_update_finish_success($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "business_update_finish_success",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "edit_business";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }

    /**
     * users_finish_success_3 method is used to process finish flow.
     * @created priyanka chillakuru | 25.09.2019
     * @modified priyanka chillakuru | 25.09.2019
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

        $func_array["function"]["name"] = "edit_business";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }


}
