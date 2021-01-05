<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User User_navigation Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module User User_navigation
 *
 * @class User_User_navigation_model.php
 *
 * @path application\webservice\basic_appineers_master\models\User_User_navigation_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 18.09.2019
 */

class Profile_visit_model extends CI_Model
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
     * @param array $params_arr params_arr array to process User_navigation block.
     * @return array $return_arr returns response of User_navigation block.
     */
    public function set_profile_visit($params_arr = array())
    {

        try
        {

            $result_arr = array();
            if (!is_array($params_arr) || count($params_arr) == 0)
            {
                throw new Exception("Insert data not found.");
            }
            if (isset($params_arr["business_id"]))
            {
                $this->db->set("iBusinessId", $params_arr["business_id"]);
            }
            
            if (isset($params_arr["user_id"]))
            {
                $this->db->set("iUserId", $params_arr["user_id"]);
            }
        
            $this->db->set("eStatus", $params_arr["status"]);
            
           

             $this->db->set($this->db->protect("dtAddedAt"), $params_arr["_dtaddedat"], FALSE);
                
            
            $this->db->insert("profile_visit");
         
            $insert_id = $this->db->insert_id();
            if (!$insert_id)
            {
                throw new Exception("Failure in insertion.");
            }
        
            $result_param = "insert_id";
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
     * delete_like_and_dislike method is used to execute database queries for Block User API.
     * @created Chetan Dvs | 16.05.2019
     * @modified Chetan Dvs | 16.05.2019
     * @param string $user_id user_id is used to process query block.
     * @param string $block_id block_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function delete_existing_user_navigation($user_id = '', $business_id = '')
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
            $res = $this->db->delete("navigated_user");
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


    
   
    /**
     * delete_user_navigation method is used to execute database queries for Edit Profile API.
     * @created priyanka chillakuru | 18.09.2019
     * @modified priyanka chillakuru | 25.09.2019
     * @param array $params_arr params_arr array to process query block.
     * @param array $where_arr where_arr are used to process where condition(s).
     * @return array $return_arr returns response of query block.
     */
    public function delete_user_navigation($params_arr = array())
    {
        try
        {
            $result_arr = array();
            $this->db->start_cache();
            if (isset($params_arr["user_navigation_id"]))
            {
                $this->db->where("iUser_navigationId =", $params_arr["user_navigation_id"]);
            }
            $this->db->stop_cache();
            //$this->db->set("eUser_navigationStatus", 'InActive');
            //$this->db->set($this->db->protect("dtUpdatedAt"), $params_arr["dtUpdatedAt"], FALSE);
           
            $res = $this->db->delete("user_navigation");

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
