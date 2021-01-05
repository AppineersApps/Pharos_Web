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
   
}
