<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Login Email Controller
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage controllers
 *
 * @module User Login Email
 *
 * @class User_login_email.php
 *
 * @path application\webservice\basic_appineers_master\controllers\User_login_email.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.02.2020
 */

class Get_business_profile extends Cit_Controller
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
            "Get_business_profile_details",
        );
       
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('Get_business_profile_model');
        $this->load->model("basic_appineers_master/users_model");
    }

    /**
     * rules_user_login_email method is used to validate api input params.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_business_profile($request_arr = array())
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
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_business_profile");

        return $valid_res;
    }

    /**
     * start_user_login_email method is used to initiate api execution flow.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_get_business_profile($request_arr = array(), $inner_api = FALSE)
    {
        try
        {
            $validation_res = $this->rules_get_business_profile($request_arr);
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



            $input_params = $this->get_business_profile_details($input_params);

         
            

                $condition_res = $this->check_business_exists($input_params);
                if ($condition_res["success"])
                {


                       $output_response = $this->get_business_finish_succes($input_params);
                        return $output_response;
                 }

                 else
                  {
                        $output_response = $this->get_business_finish_success_1($input_params);
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
            
            $this->block_result = $this->Get_business_profile_model->get_business_profile_details($business_id);
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


             $arrShareData1 = $this->get_product_business_details($data_arr["business_id"]);

                $insert_arr = array();
                foreach ($arrShareData1 as $data_key2 => $data_arr2)
                 {

                   $insert_arr[$data_key2]['product_id'] =$data_arr2['product_id'];
                  $insert_arr[$data_key2]['product_name'] =$data_arr2['product_name'];
                   $insert_arr[$data_key2]['product_price'] =$data_arr2['product_price'];
                  $insert_arr[$data_key2]['product_description'] =$data_arr2['product_description'];

                      $data = $data_arr2["product_image"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $image_arr["path"] ="pharos/products";
                    $data = $this->general->get_image_aws($image_arr);
                    
                    $insert_arr[$data_key2]["product_image"] = $data;


                 }

            $result_arr[$data_key]["products"] = $insert_arr;


             $arrShareData = $this->get_favorite_business_details($data_arr["business_id"],$input_params['user_id']);

                 foreach ($arrShareData as $data_key1 => $data_arr1)
                 {
                  $result_arr[$data_key]["isFavorite"] =$data_arr1['favorite_count'];
                 }


                  $arrShareData2 = $this->get_rated_details($data_arr["business_id"],$input_params['user_id']);
                  if(!empty($arrShareData2))
                  {
                     $data_arr3=1;
                  }else
                  {
                     $data_arr3=0;
                  }
                 
                  $result_arr[$data_key]["isRated"] =$data_arr3;
                 
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

    public function get_favorite_business_details($business_id, $user_id)
    {

      $arrShareResult = array();
      $arrShareResult = $this->Get_business_profile_model->get_favorite_business_details($business_id, $user_id);
       $result_arr =  $arrShareResult["data"];
      $arrShareResult['data'] = $result_arr;
      return $arrShareResult['data'] ;
    }


     public function get_rated_details($business_id, $user_id)
    {

      $arrShareResult = array();
      $arrShareResult = $this->Get_business_profile_model->get_rated_details($business_id, $user_id);
       $result_arr =  $arrShareResult["data"];
      $arrShareResult['data'] = $result_arr;
      return $arrShareResult['data'] ;
    }

     public function get_workinghourse_business_details($business_id)
    {

      $arrShareResult = array();
      $arrShareResult = $this->Get_business_profile_model->get_workinghourse_business_details($business_id);
      $result_arr =  $arrShareResult["data"];
      $arrShareResult['data'] = $result_arr;
      return $arrShareResult['data'] ;
    }




     public function get_product_business_details($business_id)
    {

      $arrShareResult = array();
      $arrShareResult = $this->Get_business_profile_model->get_product_business_details($business_id);
       $result_arr =  $arrShareResult["data"];
      $arrShareResult['data'] = $result_arr;
      return $arrShareResult['data'] ;
    }



    /**
     * check_user_exists method is used to process conditions.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process condition flow.
     * @return array $block_result returns result of condition block as array.
     */
    public function check_business_exists($input_params = array())
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
    public function get_business_finish_succes($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "get_business_finish_succes",
        );
        $output_fields = array(
            'business_id',
            'business_type_id',
            'u_user_id',
            'business_type',
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
            'average_rating',
            'userCount',
            'about_business',
            'b_zip_code',     
            'isFavorite',
            'workinghours',
            'products',
            'isRated',
          

        );
        $output_keys = array(
            'get_business_profile_details',
        );
        $ouput_aliases = array(
            "get_business_details" => "get_business_details",
            "business_id" => "business_id",
               "u_user_id" => "user_id",
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
              "isRated" => "isRated",
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
     * users_finish_success_4 method is used to process finish flow.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 13.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $responce_arr returns responce array of api.
     */
    public function get_business_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_business_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_business_profile_details";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }



}
