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

class Get_near_business_model_v1 extends CI_Model
{
    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('listing');
    }
     
     
     
     

     
     
    /**
     * get_users_list method is used to execute database queries for User Sign Up Email API.
     * @created Kavita sawant | 27.05.2020
     * @modified Kavita sawant | 27.05.2020
     * @param string $insert_id insert_id is used to process query block.
     * @return array $return_arr returns response of query block.
     */
    public function get_near_business_list_details($arrParams = '')
    {
        try
        {


            $result_arr = $old_arr = $tmp_arr = array();

              $this->db->from("business AS b");
            $this->db->join("business_location AS bl", "bl.iBusinessId = b.iBusinessId", "left");

            $this->db->join("business_rating AS br", "br.iBusinessId = b.iBusinessId", "left");

             $this->db->join("favorite_business AS fb", "fb.iBusinessId = b.iBusinessId", "left");
           
            $this->db->select("b.iBusinessId AS business_id");
            $this->db->select("b.iBusinessTypeId AS business_type_id");
            $this->db->select("b.vBusinessName AS business_name");
     //$this->db->select("(select avg(br.vRating)as business_rating from business_rating as br,business as b where br.iBusinessId=b.iBusinessId group by br.iBusinessId) AS business_avarage_rattings", FALSE);
               $this->db->select("avg(br.vRating) as rating");
              $this->db->select("fb.iFavoriteBusinessId as favorite_id");
               $this->db->select("fb.iUserId as favorite_user");
           // $this->db->select("(select u.eIsSubscribed from users as u where iUserId='".$user_id."') AS subscribed_status", FALSE);
            $this->db->select("b.vEmailId AS b_email");
            $this->db->select("b.vMobileNumber AS b_mobile_no");
            $this->db->select("b.vImage AS b_profile_image");    
            $this->db->select("bl.vAddress AS b_address");
            $this->db->select("bl.vCity AS b_city");
            $this->db->select("bl.dLatitude AS b_latitude");
            $this->db->select("bl.dLongitude AS b_longitude");          
            $this->db->select("bl.vState AS b_state_name");
            $this->db->select("bl.vZipCode AS b_zip_code");    
            $this->db->select("b.eStatus AS b_status");
            $this->db->select("b.tCreatedOn AS b_added_at");
            $this->db->select("b.tUpdatedOn AS b_updated_at");
            
            if (isset($arrParams['search_radius']) && $arrParams['search_radius'] != "")
            {

            $this->db->where("FLOOR(".$arrParams['distance'].") <=", $arrParams['search_radius']); 
            }else{

                 $this->db->where("FLOOR(".$arrParams['distance'].") <=", 10); 
            }

            

            //$this->db->groupby("br.iBusinessId");

            $this->db->where("b.eStatus =", 'Active');
           //$this->db->where("fb.iUserId =",1);
            $this->db->group_by("br.iBusinessId");
            $result_obj = $this->db->get();
			//echo $this->db->last_query();exit;
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
