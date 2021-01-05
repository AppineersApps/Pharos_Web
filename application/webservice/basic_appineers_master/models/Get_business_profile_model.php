<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Users Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module Users
 *
 * @class Users_model.php
 *
 * @path application\webservice\basic_appineers_master\models\Users_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.02.2020
 */

class Get_business_profile_model extends CI_Model
{
    public $default_lang = 'EN';

    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
        $this->default_lang = $this->general->getLangRequestValue();
    }

    
   


    /**
     * get_user_login_details method is used to execute database queries for User Login Email API.
     * @created priyanka chillakuru | 13.09.2019
     * @modified priyanka chillakuru | 23.12.2019
     * @param string $auth_token auth_token is used to process query block.
     * @param string $where_clause where_clause is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_business_profile_details($business_id = '')
    {
        try
        {
            $result_arr = array();

            $this->db->from("business AS b");
            $this->db->join("users AS u", "u.iUserId = b.iUserId", "left");

             $this->db->join("business_type AS bt", "bt.iBusinessTypeId = b.iBusinessTypeId", "left");
              $this->db->join("business_rating AS br", "br.iBusinessId = b.iBusinessId", "left");

            $this->db->select("b.iBusinessId AS business_id");
             $this->db->select("b.iUserId AS u_user_id");
            $this->db->select("bt.vBusinessTypeName AS business_type");
             $this->db->select("bt.iBusinessTypeId AS business_type_id");
            $this->db->select("avg(br.vRating) as average_rating");  
            $this->db->select("count(br.iUserId) as userCount");   
            $this->db->select("b.vBusinessName AS business_name");
            $this->db->select("u.vEmail AS b_email");    
            $this->db->select("b.vImage AS b_profile_image");
           $this->db->select("b.vImage1 AS b_image1");
            $this->db->select("b.vImage2 AS b_image2");
             $this->db->select("b.vImage3 AS b_image3");
              $this->db->select("b.vImage4 AS b_image4");
            $this->db->select("u.tAddress AS b_address");
            $this->db->select("u.vCity AS b_city");
            $this->db->select("u.dLatitude AS b_latitude");
            $this->db->select("u.dLongitude AS b_longitude");
          $this->db->select("b.tAboutBusiness AS about_business");
            $this->db->select("u.vStateName AS b_state_name");
            $this->db->select("u.vZipCode AS b_zip_code");
            $this->db->select("u.eEmailVerified AS b_email_verified");
            $this->db->select("u.eDeviceType AS b_device_type");
            $this->db->select("u.vDeviceModel AS b_device_model");
            $this->db->select("u.vDeviceOS AS b_device_os");
            $this->db->select("u.vDeviceToken AS b_device_token");
            $this->db->select("u.eStatus AS b_status");
            $this->db->select("u.dtAddedAt AS b_added_at");
            $this->db->select("u.dtUpdatedAt AS b_updated_at");
           
            $this->db->select("u.eSocialLoginType AS b_social_login_type");
            $this->db->select("u.vSocialLoginId AS b_social_login_id");
            $this->db->select("u.ePushNotify AS b_push_notify");
            //$this->db->select("ms.vState AS ms_state");
           // $this->db->select("u.eOneTimeTransaction AS e_one_time_transaction");
//$this->db->select("u.tOneTimeTransaction AS t_one_time_transaction");
            $this->db->select("u.vTermsConditionsVersion AS b_terms_conditions_version");
            $this->db->select("u.vPrivacyPolicyVersion AS b_privacy_policy_version");
            $this->db->select("u.eLogStatus AS b_log_status_updated");
              $this->db->where("b.iBusinessId =", $business_id);
                $this->db->where("u.eStatus =", 'Active');
            $this->db->group_by("br.iBusinessId");

            $this->db->limit(1);

            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

     public function get_favorite_business_details($business_id, $user_id)
    {
        try
        {

            $strSql ="select count(*) as favorite_count from favorite_business where iBusinessId ='".$business_id."' AND  iUserId ='".$user_id."'";
            $result_obj =  $this->db->query($strSql);
        
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
         
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
            }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        //echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }




    public function get_rated_details($business_id, $user_id)
    {
        try
        {

            $strSql ="select * from business_rating where iBusinessId ='".$business_id."' AND iUserId ='".$user_id."' AND  dtAddedAt >= DATE(NOW()) - INTERVAL 7 DAY";
            $result_obj =  $this->db->query($strSql);
        
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
         
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
            }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        //echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }



      public function get_workinghourse_business_details($business_id)
    {
        try
        {

            $strSql ="select vDay as day,vStartAt as start_time, vCloseAt as end_time from business_working_hours where iBusinessId ='".$business_id."'";
            $result_obj =  $this->db->query($strSql);
        
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
           //echo $this->db->last_query();exit;
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
            }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        //echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }


      public function get_product_business_details($business_id)
    {
        try
        {

            $strSql ="select iProductId as product_id,vProductImage as product_image, vProductName as product_name,iProductPrice as product_price,tProductDescription as product_description from product where iBusinessId ='".$business_id."'";
            $result_obj =  $this->db->query($strSql);
        
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
           //echo $this->db->last_query();exit;
            if (!is_array($result_arr) || count($result_arr) == 0)
            {
                throw new Exception('No records found.');
            }
            $success = 1;
            }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        //echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }

   
}
