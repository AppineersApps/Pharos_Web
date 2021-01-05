<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of User Sign Up Email Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module User Sign Up Email
 *
 * @class User_sign_up_email_model.php
 *
 * @path application\webservice\basic_appineers_master\models\User_sign_up_email_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 12.02.2020
 */

class Get_near_business_model extends CI_Model
{
    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
    }
     
     
     
     

     
     
   public function get_near_business_list_details($arrParams = '')
    {
        try
        {


            $result_arr = $old_arr = $tmp_arr = array();

              $this->db->from("business AS b");
            $this->db->join("users AS u", "u.iUserId = b.iUserId", "left");

            //$this->db->join("business_rating AS br", "br.iBusinessId = b.iBusinessId", "left");
            
            $this->db->select("b.iBusinessId AS business_id");
            $this->db->select("b.iBusinessTypeId AS business_type_id");
            $this->db->select("b.vBusinessName AS business_name"); 
            $this->db->select("b.eIsOpen AS isOpen");   
            //$this->db->select("avg(br.vRating) as average_rating");                      
            $this->db->select("b.vImage AS business_profile");    
            $this->db->select("u.tAddress AS address");
            $this->db->select("u.vCity AS city");
            $this->db->select("u.dLatitude AS latitude");
            $this->db->select("u.dLongitude AS longitude");          
            $this->db->select("u.vStateName AS  state_name");
            $this->db->select("u.vZipCode AS zip_code");    
            $this->db->select("u.eStatus AS status");


            if(isset($arrParams['zipcode']) && $arrParams['zipcode'] != "")
            {

                $this->db->where("u.vZipCode=", $arrParams['zipcode']); 

            }else
            {
                if (isset($arrParams['search_radius']) && $arrParams['search_radius']!= "" && $arrParams['distance'] != "")
                {

                $this->db->where("FLOOR(".$arrParams['distance'].") <=", $arrParams['search_radius']); 
                }

            }
          
            $this->db->where("u.eStatus =", 'Active');
           //$this->db->where("fb.iUserId =",1);
            //$this->db->group_by("br.iBusinessId");
            $result_obj = $this->db->get();
           // echo $this->db->last_query();exit;
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
            //print_r( $result_arr);exit;
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
        // echo $this->db->last_query();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }


    public function get_avg_business_rating($business_id)
    {
        try
        {

            $strSql ="select avg(vRating) as average_rat from business_rating where iBusinessId ='".$business_id."' group by iBusinessId";
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
     /**
     * get_users_list method is used to execute database queries for User Sign Up Email API.
     * @created Kavita sawant | 27.05.2020
     * @modified Kavita sawant | 27.05.2020
     * @param string $insert_id insert_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_users_connection_details($user_id = '',$connection_id='',$other_user_id='')
    {
         try
        {

            $result_arr = array();
           
               $strSql=
               "SELECT '' AS connection_type, 
                        (SELECT eConnectionType 
                        FROM users_connections 
                        WHERE  iUserId=".$user_id." AND  iConnectionUserId = ".$connection_id.") AS connection_type_by_logged_user,
                        (SELECT eConnectionType 
                        FROM users_connections 
                        WHERE iUserId=".$connection_id." AND  iConnectionUserId = ".$user_id.") AS connection_type_by_receiver_user
                        FROM users_connections LIMIT 1";



                $result_obj =  $this->db->query($strSql);
            //echo $this->db->last_query();exit;
            $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();

            if(isset($result_arr[0]['connection_type_by_logged_user'])){

                $result_arr[0]['connection_type_by_logged_user'] = $result_arr[0]['connection_type_by_logged_user'];
            }
            else if(isset($result_arr[0]['connection_type_by_receiver_user'])){

                $result_arr[0]['connection_type_by_receiver_user'] = $result_arr[0]['connection_type_by_receiver_user'];
            }
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
        // echo $this->db->last_query();exit;
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        return $return_arr;
    }
      
   
  
}
