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

class Rated_business extends Cit_Controller
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
            "get_ratings_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('Rated_business_model');
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
            )
            /*"products_name" => array(
                array(
                    "rule" => "minlength",
                    "value" => 1,
                    "message" => "products_name_minlength",
                )
            ),
            "product_price" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[0-9]+(\.[0-9]{1,2})?$/",
                    "message" => "product_serial_number_with_two_decimal",
                )
            ),
            "product_quantity" => array(
                array(
                    "rule" => "number",
                    "value" => TRUE,
                    "message" => "product_quantity_number_only",
                )
            ),
            "vendor_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "vendor_id_required",
                )
            ),
            "product_shipping_date" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^\d{4}-\d{2}-\d{2}$/",
                    "message" => "product_shipping_date_yyyy-mm-dd_format_required",
                )
            ),
            "timestamp" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/",
                    "message" => "timestamp_yyyy-mm-dd hh:mm:ss_format_required",
                ),
                 array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "timestamp_required",
                )
            ),
            "free_shipping" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[01]$/",
                    "message" => "free_shipping_with_boolean_value",
                )
            ),
            "card_charged" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[01]$/",
                    "message" => "card_charged_with_boolean_value",
                )
            ),
            "received" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[01]$/",
                    "message" => "received_with_boolean_value",
                )
            ),
            "insystem" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[01]$/",
                    "message" => "insystem_with_boolean_value",
                )
            ),
            "cancelled" => array(
                array(
                    "rule" => "regex",
                    "value" => "/^[01]$/",
                    "message" => "cancelled_with_boolean_value",
                )
            ),
            "product_image_1" => array(
                array(
                    "rule" => "regex",
                    "value" => "/.*\.(gif|jpe?g|bmp|png)$/",
                    "message" => "product_image_1_only_jpeg_gif_png_images",
                )
            ),
            "product_image_2" => array(
                array(
                    "rule" => "regex",
                    "value" => "/.*\.(gif|jpe?g|bmp|png)$/",
                    "message" => "product_image_2_only_jpeg_gif_png_images",
                )
            ),
            "product_image_3" => array(
                array(
                    "rule" => "regex",
                    "value" => "/.*\.(gif|jpe?g|bmp|png)$/",
                    "message" => "product_image_3_only_jpeg_gif_png_images",
                )
            ),
             "product_image_4" => array(
                array(
                    "rule" => "regex",
                    "value" => "/.*\.(gif|jpe?g|bmp|png)$/",
                    "message" => "product_image_4_only_jpeg_gif_png_images",
                )
            ),
             "images_count" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "images_count_required",
                )
            )*/
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
    public function start_rated_business($request_arr = array())
    {
              // get the HTTP method, path and body of the request
        $method = $_SERVER['REQUEST_METHOD'];
        $output_response = array();

        switch ($method) {
          case 'GET':
           $output_response =  $this->get_ratings($request_arr);
           return  $output_response;
             break;
          case 'PUT':
           $output_response =  $this->update_products($request_arr);
           return  $output_response;
             break;
          case 'POST':
              $output_response =  $this->add_products($request_arr);
           return  $output_response;
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
    {print_r($request_arr);
        
         $valid_arr = array(            
            
            "business_id" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "business_id_required",
                )
            ),
            "product_name" => array(
                array(
                    "rule" => "required",
                    "value" => TRUE,
                    "message" => "business_id_required",
                )
            )
            );
        
        
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "update_products");

        return $valid_res;
    }
    /**
     * rules_set_store_review method is used to validate api input params.
     * @created kavita sawant | 08.01.2020
     * @modified kavita sawant | 08.01.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_ratings($request_arr = array())
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
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_ratings");

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
    public function get_ratings($request_arr = array(), $inner_api = FALSE)
    {
      try
        {
            $validation_res = $this->rules_get_ratings($request_arr);
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

            $condition_res = $this->check_ratings_exist($input_params);

            if ($condition_res["status"])
            {
                $input_params = $this->get_all_ratings($input_params);

                $output_response = $this->get_ratings_finish_success($input_params);

                return $output_response;
            }
            else
            {
                $output_response = $this->get_ratings_finish_success_1($input_params);
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

            //$condition_res = $this->is_posted($input_params);
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
            //$upload_path = $this->config->product("upload_path");
            /*$user_id=$input_params['user_id'];
            $img_name="product_image_";*/
            
                    
            $images_arr = array();
            $temp_var   = 0;
            $upper_limit = 5;
            for($i=1; $i<=$upper_limit; $i++)
            {
              $new_file_name=$img_name.$i;
              
               if (isset($_FILES[$new_file_name]["name"]) && isset($_FILES[$new_file_name]["tmp_name"]))
              {
                  $sent_file = $_FILES[$new_file_name]["name"];
              }
              else
              {
                  $sent_file = "";
              }
              if (!empty($sent_file))
              {
                  list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                  $images_arr[$new_file_name]["ext"] = implode(',', $this->config->product('IMAGE_EXTENSION_ARR'));
                  $images_arr[$new_file_name]["size"] = "102400";
                  if ($this->general->validateFileFormat($images_arr[$new_file_name]["ext"], $_FILES[$new_file_name]["name"]))
                  {
                      if ($this->general->validateFileSize($images_arr[$new_file_name]["size"], $_FILES[$new_file_name]["size"]))
                      {
                          $images_arr[$new_file_name]["name"] = $file_name;
                      }
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
            
            if (isset($images_arr["product_image_1"]["name"]))
            {
                $params_arr["product_image_1"] = $images_arr["product_image_1"]["name"];
            }
            
            if (isset($input_params["delete_image_1"]))
            {
                $params_arr["delete_image_1"] = $input_params["delete_image_1"];
            }
            
            if (isset($images_arr["product_image_1"]["name"]))
            {
                $params_arr["product_image_1"] = $images_arr["product_image_1"]["name"];
            }
            
            $this->block_result = $this->ratings_business_model->update_product($params_arr);
            if (!$this->block_result["success"])
            {
                throw new Exception("updation failed.");
            }            
            $product_id=$input_params["product_id"];
            //$upload_path = $this->config->product("upload_path");
            if(false == empty($product_id))
            {
                for($i=1; $i<=$upper_limit; $i++)
                {
                  $folder_name="my_market/products_images/".$product_id."/";
                  $file_path = $upload_path.$folder_name; 
                  // if (!empty($images_arr["product_image_".$i]["name"]) && false == empty($product_id))
                  //   {
                                       
                  //       $file_name = $images_arr["product_image_".$i]["name"];
                  //       $file_tmp_path = $_FILES["product_image_".$i]["tmp_name"];
                  //       $file_tmp_size = $_FILES["product_image_".$i]["size"];
                  //       $valid_extensions = $images_arr["product_image_".$i]["ext"];
                  //       $valid_max_size = $images_arr["product_image_".$i]["size"];
                  //       $upload_arr = $this->general->file_upload($file_path, $file_tmp_path, $file_name, $valid_extensions, $file_tmp_size, $valid_max_size);
                  //       if ($upload_arr[0] == "")
                  //       {
                  //           throw new Exception("File is not uploaded.");

                  //       }
                  //   }
                    if (!empty($images_arr["product_image_".$i]["name"]) && false == empty($product_id))
                    {
            
                        
                        $temp_file = $_FILES["product_image_".$i]["tmp_name"];
                        $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["product_image_".$i]["name"]);
                        if (!$res)
                        {
                            //file upload failed

                        }
                    }

                    // if(false == empty($input_params["delete_image_".$i]))
                    // {
                    //  $path_parts = pathinfo($input_params["delete_image_".$i]);
                    // }

                    //  if(false == empty($path_parts['basename']))   
                    //  {
                    //    $file_path = $upload_path.$folder_name.$path_parts['basename'];
                    //    //echo $file_path;exit;
                    //     if (is_file($file_path))
                    //     {
                    //         unlink($file_path);
                    //     }
                    //  }           
                   
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
            $this->block_result = $this->ratings_business_model->delete_product($arrResult);
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
    public function check_ratings_exist($input_params = array())
    {

        if (!method_exists($this, "checkRatingsExist"))
        {
            $result_arr["data"] = array();
        }
        else
        {
            $result_arr["data"] = $this->checkRatingsExist($input_params);
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
    public function get_updated_products($input_params = array())
    {

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
        $input_params["get_all_products"] = $this->block_result["data"];
        
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
    public function get_all_ratings($input_params = array())
    {
        $this->block_result = array();
        try
        {
            $arrResult = array();
            $user_id=$input_params['user_id'];

            //$arrResult['business_id']  = isset($input_params["business_id"]) ? $input_params["business_id"] : "";
            $arrResult['user_id']  = isset($input_params["user_id"]) ? $input_params["user_id"] : "";
               
            $this->block_result = $this->Rated_business_model->get_ratings_details($arrResult);
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
        $input_params["get_all_ratings"] = $this->block_result["data"];
        
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

    /**
     * set_store_review method is used to process review block.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function set_products($input_params = array())
    {
      
        $this->block_result = array();
        try
        {
            $params_arr = array();
            //$upload_path = $this->config->product("upload_path");
            $user_id=$input_params['user_id'];
            $img_name="product_image_";
                    
            $images_arr = array();
            $temp_var   = 0;
            $upper_limit = 5;
            for($i=1; $i<=$upper_limit; $i++)
            {
              $new_file_name=$img_name.$i;
              
               if (isset($_FILES[$new_file_name]["name"]) && isset($_FILES[$new_file_name]["tmp_name"]))
              {
                  $sent_file = $_FILES[$new_file_name]["name"];
              }
              else
              {
                  $sent_file = "";
              }
              if (!empty($sent_file))
              {
                  list($file_name, $ext) = $this->general->get_file_attributes($sent_file);
                  $images_arr[$new_file_name]["ext"] = implode(',', $this->config->product('IMAGE_EXTENSION_ARR'));
                  $images_arr[$new_file_name]["size"] = "102400";
                  if ($this->general->validateFileFormat($images_arr[$new_file_name]["ext"], $_FILES[$new_file_name]["name"]))
                  {
                      if ($this->general->validateFileSize($images_arr[$new_file_name]["size"], $_FILES[$new_file_name]["size"]))
                      {
                          $images_arr[$new_file_name]["name"] = $file_name;
                      }
                  }
              }
            }
            //print_r($images_arr); exit;
            
            if (isset($input_params["timestamp"]))
            {
                $params_arr["_dtaddedat"] = $input_params["timestamp"];
            }        
            
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
            

            if (isset($images_arr["product_image_1"]["name"]))
            {
                $params_arr["product_image_1"] = $images_arr["product_image_1"]["name"];
            }
            
            
            $this->block_result = $this->ratings_business_model->set_products($params_arr);

            if (!$this->block_result["success"])
            {
                throw new Exception("Insertion failed.");
            }
            $data_arr = $this->block_result["data"];
            $product_id=$data_arr["0"]["product_id"];
            /*$upload_path = $this->config->product("upload_path");
            for($i=1; $i<=$upper_limit; $i++)
            {
              
                if (!empty($images_arr["product_image_".$i]["name"]) && false == empty($product_id))
                {

                    $folder_name = "my_market/products_images/".$product_id."/";             
                    
                    $temp_file = $_FILES["product_image_".$i]["tmp_name"];
                    $res = $this->general->uploadAWSData($temp_file, $folder_name, $images_arr["product_image_".$i]["name"]);
                    if (!$res)
                    {
                        //file upload failed

                    }
                }


            }*/

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
    public function get_ratings_finish_success($input_params = array())
    {
       //print_r($input_params); exit;
        $setting_fields = array(
            "success" => "1",
            "message" => "get_ratings_finish_success",
            "total_count"=> count($input_params["get_all_ratings"])
        );
        $output_fields = array(
            "business_id",
            "business_name",
            "rating",
            "business_profile",
            "business_type",
            "rated_on",
            
        );
        $output_keys = array(
            'get_all_ratings',
        );
        $ouput_aliases = array(
            "business_id"=>"business_id",
            "business_name"=>"business_name",
            "rating" => "rating",
            "business_profile" => "business_profile",
            "business_type" => "business_type",
            "rated_on" => "rated_on",
          
        );

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;
        //print_r($input_params);exit;

        $func_array["function"]["name"] = "get_all_ratings";
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
    public function get_ratings_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "0",
            "message" => "get_ratings_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_all_ratings";
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
