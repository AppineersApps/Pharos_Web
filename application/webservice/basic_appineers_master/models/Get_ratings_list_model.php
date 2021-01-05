<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Items Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module User Items
 *
 * @class User_Items_model.php
 *
 * @path application\webservice\basic_appineers_master\models\User_Items_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Get_ratings_list_model extends CI_Model
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
     * get_Items_details method is used to execute database queries for Post a Feedback API.
     * @created priyanka chillakuru | 16.09.2019
     * @modified priyanka chillakuru | 16.09.2019
     * @param string $Items_id Items_id is used to process Items block.
     * @return array $return_arr returns response of Items block.
     */
    public function get_ratings_list_details($arrResult)
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
            $this->db->from("business_rating AS i");
               $this->db->join("users AS u", "u.iUserId = i.iUserId", "left");
            $this->db->select("i.iBusinessId AS business_id");

            $this->db->select("u.iUserId AS user_id");
           $this->db->select("(concat(u.vFirstName,' ',u.vLastName)) AS user_name", FALSE);
            $this->db->select("u.vProfileImage AS user_image");
            $this->db->select("i.vRating AS rating");           
            $this->db->select("i.eStatus AS status");
            $this->db->select("i.dtAddedAt AS dtAddedAt"); 
            $this->db->select("i.dtUpdatedAt AS dtUpdatedAt"); 
           
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


      public function get_user_device_token($business_id = '')
    {
        try {
            $result_arr = array();
                                
            $this->db->from("business AS b");
            $this->db->join("users AS u", "u.iUserId = b.iUserId", "left");
            $this->db->select("u.vDeviceToken AS u_device_token");
            $this->db->select("u.iUserId AS b_user_id");
             $this->db->select("b.vBusinessName AS busness_name");
            if(isset($business_id) && $business_id != ""){ 
                $this->db->where("b.iBusinessId =", $business_id);
            }
           
            
            
            $this->db->limit(1);
            
            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            
            if(!is_array($result_arr) || count($result_arr) == 0){
                throw new Exception('No records found.');
            }
            $success = 1;
        } catch (Exception $e) {
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



     public function get_user_details($user_id = '')
    {
        try {
            $result_arr = array();
                                
            $this->db->from("users as u");
    
      
           $this->db->select("concat(u.vFirstName,' ',u.vLastName) AS user_name");
            if(isset($user_id) && $user_id != ""){ 
                $this->db->where("u.iUserId =", $user_id);
            }
           
            
            
            $this->db->limit(1);
            
            $result_obj = $this->db->get();
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            
            if(!is_array($result_arr) || count($result_arr) == 0){
                throw new Exception('No records found.');
            }
            $success = 1;
        } catch (Exception $e) {
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
     * notification_entry method is used to execute database queries for Like User Profile API.
     * @created Devangi Nirmal | 05.06.2019
     * @modified Devangi Nirmal | 19.06.2019
     * @param array $params_arr params_arr array to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function notification_entry($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }

            $this->db->set($this->db->protect("vNotificationmessage"), $params_arr["_vmessage"], FALSE);
            if (isset($params_arr["b_user_id"]))
            {
                $this->db->set("iReceiverId", $params_arr["b_user_id"]);
            }
            $this->db->set("eNotificationType", $params_arr["_enotificationtype"]);
            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
            $this->db->set("eNotificationStatus", $params_arr["_estatus"]);
            if (isset($params_arr["user_id"]))
            {
                $this->db->set("iSenderId", $params_arr["user_id"]);
            }
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
     * delete_like_and_dislike method is used to execute database queries for Block User API.
     * @created Chetan Dvs | 16.05.2019
     * @modified Chetan Dvs | 16.05.2019
     * @param string $user_id user_id is used to process query block.
     * @param string $block_id block_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function delete_existing_user_rating($user_id = '', $business_id = '')
    {
        try
        {
            $result_arr = array();
            if (isset($user_id) && $user_id != "")
            {
                $this->db->where("iUserId =", $user_id);
            }
            if (isset($business_id) && $business_id != "")
            {
                $this->db->where("iBusinessId =", $business_id);
            }

            $res = $this->db->delete("business_rating");
            if (!$res)
            {
                throw new Exception("Failure in deletion.");
            }
            $affected_rows = $this->db->affected_rows();
            $result_param = "affected_rows";
            $result_arr[0][$result_param] = $affected_rows;
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

    public function set_ratings($params_arr = array())
    {
        try
        {
            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["user_id"]))
            {
                $this->db->set("iUserId", $params_arr["user_id"]);
            }
            if (isset($params_arr["business_id"]))
            {
                $this->db->set("iBusinessId", $params_arr["business_id"]);
            }
            if (isset($params_arr["rating"]))
            {
                $this->db->set("vRating", $params_arr["rating"]);
            }
            

            $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
            $this->db->set("eStatus", $params_arr["_estatus"]);
                
            
            //$this->db->set("eProductStatus", $params_arr["_estatus"]);

            $this->db->insert("business_rating");
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

   
}
