<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Products Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module User Products
 *
 * @class User_Products_model.php
 *
 * @path application\webservice\basic_appineers_master\models\User_Products_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Products_model extends CI_Model
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
     * post_a_feedback method is used to execute database queries for Post a Feedback API.
     * @created CIT Dev Team
     * @modified priyanka chillakuru | 16.09.2019
     * @param array $params_arr params_arr array to process Products block.
     * @return array $return_arr returns response of Products block.
     */
    public function set_products($params_arr = array())
    {

        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["product_name"]))
            {
                $this->db->set("vProductName", $params_arr["product_name"]);
            }
            
            if (isset($params_arr["product_price"]))
            {
                $this->db->set("iProductPrice", $params_arr["product_price"]);
            }
             if (isset($params_arr["business_id"]))
            {
                $this->db->set("iBusinessId", $params_arr["business_id"]);
            } 
             if (isset($params_arr["product_description"]))
            {
                $this->db->set("tProductDescription", $params_arr["product_description"]);
            } 
            
          
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtupdatedat"], FALSE);
                
            if (isset($params_arr["product_image"]) && !empty($params_arr["product_image"]))
            {
                $this->db->set("vProductImage", $params_arr["product_image"]);
            }
            
            //$this->db->set("eProductStatus", $params_arr["_estatus"]);

            $this->db->insert("product");
            //echo $this->db->last_query();exit;
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
        
            $result_param = "product_id";
            $result_arr[0][$result_param] = $insert_id;
            $success = 1;
        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }

        $this->db->_reset_all();
        #echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }


    /**
     * get_Products_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $Products_id Products_id is used to process Products block.
     * @return array $return_arr returns response of Products block.
     */
    public function get_updated_Products($arrResult)
    {
       // print_r($arrResult); exit;
        try
        {
            $result_arr = array();
            if(true == empty($arrResult)){
                return false;
            }
            $strWhere ='';
           
            $this->db->from("user_Products_relation AS umr");
            $this->db->join("Products AS m", "m.iProductsId = umr.iProductsId", "left");
            $this->db->join("vendor AS v", "v.iUserProductsId = umr.iProductsId", "left");
            $this->db->select("umr.iProductsId AS Products_id");
            $this->db->select("m.vProductsName AS Products_name");
            $this->db->select("m.vProductsAddress AS Products_address");            
            $this->db->select("m.dProductsStartDate AS Products_start_date");            
            $this->db->select("v.iVendorId AS vendor_id");
            $this->db->select("v.vVendorName AS vendor_name");
            $this->db->select("v.vVendorImageId AS vendor_image");
            $this->db->select("v.bIsHidden AS is_hidden");
            $this->db->select("m.dAddededAt AS Products_adddate");

           if (isset($arrResult['Products_id'] ) && $arrResult['Products_id']  != "")
            {
               $this->db->where_in("umr.iProductsId", $arrResult['Products_id']);
            }  
           
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
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }



    public function get_business_details($input_params)
    {
        try
        {

        
            $result_arr = array();
           
            $strWhere ='';


            if(false== empty($input_params["business_id"]))
            {

                $strWhere = "b.iBusinessId='" . $input_params['business_id'] . "'";
            }
            $this->db->from("business AS b");
         
            $this->db->select("b.iBusinessId AS business_id");
            $this->db->select("b.vBusinessName AS business_name");
            if(false == empty($strWhere)){
               $this->db->where($strWhere); 
            }          
            
        
            $result_obj = $this->db->get();
            //echo $this->db->last_query();exit;
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
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }




    /**
     * get_user_details_for_send_notifi method is used to execute database queries for Send Message API.
     * @created CIT Dev Team
     * @modified Devangi Nirmal | 27.06.2019
     * @param string $user_id user_id is used to process query block.
     * @param string $receiver_id receiver_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_user_details_for_send_notifi($input_params)
    {
        try
        {
            $result_arr = array();
             $strSql="SELECT 
            
               distinct(u.iUserId) AS receiver_id,
             u.vDeviceToken AS u_device_token,             
             u.vProfileImage AS u_profile_image,         
             CONCAT(u.vFirstName,\" \",u.vLastName) AS u_name
             FROM favorite_business AS fb            
             left JOIN users AS u ON u.iUserId = fb.iUserId 
             WHERE fb.iBusinessId = '".$input_params['business_id']."' ";
            $result_obj = $this->db->query($strSql);

            /*INNER JOIN time_availability AS ta ON (p.iUserId = ta.iUserId AND DAYNAME('".$input_params['service_date']."') = ta.vDay AND ta.vStartTime <= '".$input_params['service_time']."' <= ta.vEndTime ) -- removed time availibity condition as per discussion with bhushan */
          // echo '<pre>';
          // echo $this->db->last_query();exit;
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


     public function post_notification($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["notification_message"]))
            {
                $this->db->set("vNotificationMessage", $params_arr["notification_message"]);
            }
            if (isset($params_arr["receiver_id"]))
            {
                $this->db->set("iReceiverId", $params_arr["receiver_id"]);
            }
            if (isset($params_arr["sender_id"]))
            {
                $this->db->set("iSenderId", $params_arr["sender_id"]);
            }
            
            $this->db->set("eNotificationType", $params_arr["_enotificationtype"]);
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
            $this->db->set("eNotificationStatus", "Active");

            /*if (isset($params_arr["user_id"]))
            {
                $this->db->set("iUserId", $params_arr["user_id"]);
            }*/
            $this->db->insert("notification");
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
            $result_param = "insert_id1";
            $result_arr[0][$result_param] = $insert_id;
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


    /**
     * get_Products_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $Products_id Products_id is used to process Products block.
     * @return array $return_arr returns response of Products block.
     */
    public function get_all_products($arrResult)
    {
        try
        {

        
            $result_arr = array();
            if(true == empty($arrResult)){
                return false;
            }
            $strWhere ='';


            if(false== empty($arrResult["business_id"]))
            {

                $strWhere = "i.iBusinessId='" . $arrResult['business_id'] . "'";
            }
            $this->db->from("product AS i");
            $this->db->select("i.iProductId AS product_id");
            $this->db->select("i.iBusinessId AS business_id");
            $this->db->select("i.vProductName AS product_name");
            $this->db->select("i.vProductImage AS product_image");
            $this->db->select("i.iProductPrice AS product_price");
            $this->db->select("i.tProductDescription AS product_description"); 
             $this->db->select("i.dtAddedAt AS added_date"); 
            $this->db->select("i.eStatus AS status"); 
            
            //$this->db->select("i.vImage_ID1 AS image_1"); 
            
            $this->db->order_by("i.iProductId Desc");

            if(false == empty($strWhere)){
               $this->db->where($strWhere); 
            }          
            
            $this->db->limit($rec_per_page, $start_from);
            $result_obj = $this->db->get();
            //echo $this->db->last_query();exit;
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
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
   /**
     * update_profile method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function update_product($params_arr = array())
    {
        try
        {
            $result_arr = array();
            $this->db->start_cache();
            $this->db->start_cache();
            if (false == empty($params_arr["product_id"]))
            {
                $this->db->where("iProductId =", $params_arr["product_id"]);
            }


            if (false == empty($params_arr["business_id"]))
            {
                $this->db->where("iBusinessId", $params_arr["business_id"]);
            }
            $this->db->where_in("eStatus", array('Active'));
            $this->db->stop_cache();
            if (isset($params_arr["product_name"]))
            {
                $this->db->set("vProductName", $params_arr["product_name"]);
            }
            
            if (isset($params_arr["product_price"]))
            {
                $this->db->set("iProductPrice", $params_arr["product_price"]);
            }
              
             if (isset($params_arr["product_description"]))
            {
                $this->db->set("tProductDescription", $params_arr["product_description"]);
            } 
            
                
            if (isset($params_arr["product_image"]) && !empty($params_arr["product_image"]))
            {
                $this->db->set("vProductImage", $params_arr["product_image"]);
            }
            
            $this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["_dtupdatedat"], FALSE);
           
            $res = $this->db->update("product");

            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;

        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        //echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
    /**
     * delete_product method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function delete_product($params_arr = array())
    {
        try
        {

           
            $result_arr = array();
            $this->db->start_cache();
            if (isset($params_arr["product_id"]))
            {
                $this->db->where("iProductId =", $params_arr["product_id"]);
            }
            $this->db->stop_cache();
            //$this->db->set("eProductStatus", 'InActive');
            //$this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["dtUpdatedAt"], FALSE);
           
            $res = $this->db->delete("product");

            $affected_rows = $this->db->affected_rows();
            if (!$res || $affected_rows == -1)
            {
                throw new Exception("Failure in updation.");
            }
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
            $success = 1;

        }
        catch(Exception $e)
        {
            $success = 0;
            $message = $e->getMessage();
        }
        $this->db->flush_cache();
        $this->db->_reset_all();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
}
