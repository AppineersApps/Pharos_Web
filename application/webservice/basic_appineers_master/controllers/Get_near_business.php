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

class Get_near_business extends Cit_Controller
{
    public $settings_params;
    public $output_params;
    public $single_keys;
    public $multiple_keys;
    public $block_result;

    

     public function __construct()
    {
        parent::__construct();
        $this->settings_params = array();
        $this->output_params = array();
        $this->single_keys = array(
            "Get_near_business_details",
        );
        $this->block_result = array();

        $this->load->library('wsresponse');
        $this->load->model('Get_near_business_model');
        $this->load->model("basic_appineers_master/users_model");
      
       
    }
  

    /**
     * rules_user_sign_up_email method is used to validate api input params.
     * @created kavita Sawant | 25-05-2020
     * @modified kavita Sawant | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @return array $valid_res returns output response of API.
     */
    public function rules_get_near_business($request_arr = array())
    {
       
        $valid_res = $this->wsresponse->validateInputParams($valid_arr, $request_arr, "get_near_business");
        return $valid_res;
    }
    

    /**
     * start_user_sign_up_email method is used to initiate api execution flow.
     * @created kavita Sawant | 25-05-2020
     * @modified kavita Sawant | 12.02.2020
     * @param array $request_arr request_arr array is used for api input.
     * @param bool $inner_api inner_api flag is used to idetify whether it is inner api request or general request.
     * @return array $output_response returns output response of API.
     */
    public function start_get_near_business($request_arr = array(), $inner_api = FALSE)
    {
  		try
  		{
            
  			$validation_res = $this->rules_get_near_business($request_arr);
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
            
  			$input_params = $this->get_near_business_list_details($input_params);
        
  			$condition_res = $this->is_posted($input_params);
  			if ($condition_res["success"])
  			{
  				
  				$output_response = $this->get_near_business_finish_success($input_params);
  				return $output_response;
  			}

  			else
  			{

  				$output_response = $this->get_near_business_finish_success_1($input_params);
  			
  				return $output_response;
  			}
  		}
  		catch(Exception $e)
  		{
  			$message = $e->getMessage();
  		}
  		return $output_response;
  }

       public function prepare_distance($input_params = array())
          {
              if (!method_exists($this, "prepareDistanceQuery"))
              {
                  $result_arr["data"] = array();
              }
              else
              {
                  $result_arr["data"] = $this->prepareDistanceQuery($input_params);
              }
              $format_arr = $result_arr;

              $format_arr = $this->wsresponse->assignFunctionResponse($format_arr);
              $input_params["prepare_distance"] = $format_arr;

              $input_params = $this->wsresponse->assignSingleRecord($input_params, $format_arr);
              return $input_params;
          }
 
    /**
     * get_users_list method is used to process query block.
     * @created kavita sawant | 27-05-2020
     * @modified kavita sawant  | 01.10.2019
     * @param array $input_params input_params array to process loop flow.
     * @return array $input_params returns modfied input_params array.
     */
    public function get_near_business_list_details($input_params = array())
    {

        $this->block_result = array();
        try
        {

			       $arrParams=array();
            if(isset($input_params['Latitude']) && $input_params['Longitude'] != '' && $input_params['Latitude'] != '')
             {
               $input_params = $this->prepare_distance($input_params);

             }
          
            $arrParams['search_radius'] = isset($input_params["search_radius"]) ? $input_params["search_radius"] : "";
             $arrParams['distance'] = isset($input_params["distance"]) ? $input_params["distance"] : "";

             $arrParams['zipcode'] = isset($input_params["zipcode"]) ? $input_params["zipcode"] : "";         
            $this->block_result = $this->Get_near_business_model->get_near_business_list_details($arrParams);
			
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

                 $arrShareData1 = $this->get_avg_business_rating($data_arr["business_id"]);

                 if(!empty($arrShareData1)){
                   foreach ($arrShareData1 as $data_key2 => $data_arr2)
                   {
                    $result_arr[$data_key]["average_rating"] =$data_arr2['average_rat'];
                   }
                  }else{
                    $result_arr[$data_key]["average_rating"] ='';
                  } 

                 $arrShareData = $this->get_favorite_business_details($data_arr["business_id"],$input_params['user_id']);

                 foreach ($arrShareData as $data_key1 => $data_arr1)
                 {
                  $result_arr[$data_key]["isFavorite"] =$data_arr1['favorite_count'];
                 }

                    $data = $data_arr["business_profile"];
                    $image_arr = array();
                    $image_arr["image_name"] = $data;
                    $image_arr["ext"] = implode(",", $this->config->item("IMAGE_EXTENSION_ARR"));
                    $image_arr["color"] = "FFFFFF";
                    $image_arr["no_img"] = FALSE;
                    $dest_path = "business_profile";
                    /*$image_arr["path"] = $this->general->getImageNestedFolders($dest_path);
                    $data = $this->general->get_image($image_arr);*/
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
        $input_params["get_near_business_list_details"] = $this->block_result["data"];
        $input_params = $this->wsresponse->assignSingleRecord($input_params, $this->block_result["data"]);

        return $input_params;
    }


    public function get_favorite_business_details($business_id, $user_id)
    {



      $arrShareResult = array();
      $arrShareResult = $this->Get_near_business_model->get_favorite_business_details($business_id, $user_id);
       $result_arr =  $arrShareResult["data"];
      $arrShareResult['data'] = $result_arr;
      return $arrShareResult['data'] ;
    }


    public function get_avg_business_rating($business_id)
    {

      $arrShareResult = array();
      $arrShareResult = $this->Get_near_business_model->get_avg_business_rating($business_id);
       $result_arr =  $arrShareResult["data"];
      $arrShareResult['data'] = $result_arr;
      return $arrShareResult['data'] ;
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
    

    public function get_near_business_finish_success($input_params = array())
    {
       
        $setting_fields = array(
            "success" => "1",
            "message" => "get_near_business_list_details",
        );
        $output_fields = array(
            'business_id',
            'business_type_id',
            'business_name',           
            'average_rating',
            'business_profile',          
            'address',
            'city',
            'latitude',
            'longitude',
            'state_name',            
            'zip_code',
            'isFavorite',
            'isOpen',
          
        );
        $output_keys = array(
            'get_near_business_list_details',
        );

        $output_array["settings"] = array_merge($this->settings_params, $setting_fields);
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_near_business_list_details";
        $func_array["function"]["output_keys"] = $output_keys;
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
    public function get_near_business_finish_success_1($input_params = array())
    {

        $setting_fields = array(
            "success" => "1",
            "message" => "get_near_business_finish_success_1",
        );
        $output_fields = array();

        $output_array["settings"] = $setting_fields;
        $output_array["settings"]["fields"] = $output_fields;
        $output_array["data"] = $input_params;

        $func_array["function"]["name"] = "get_near_business_list_details";
        $func_array["function"]["single_keys"] = $this->single_keys;
        $func_array["function"]["multiple_keys"] = $this->multiple_keys;

        $this->wsresponse->setResponseStatus(200);

        $responce_arr = $this->wsresponse->outputResponse($output_array, $func_array);

        return $responce_arr;
    }
    
  
}
