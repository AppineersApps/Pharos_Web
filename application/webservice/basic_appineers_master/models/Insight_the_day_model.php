<?php
defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Description of Get Matched Users Model
 *
 * @category webservice
 *
 * @package basic_appineers_master
 *
 * @subpackage models
 *
 * @module Get Matched Users
 *
 * @class Get_matched_users_model.php
 *
 * @path application\webservice\user\models\Get_matched_users_model.php
 *
 * @version 4.4
 *
 * @author CIT Dev Team
 *
 * @since 14.06.2019
 */

class Insight_the_day_model extends CI_Model
{
    /**
     * __construct method is used to set model preferences while model object initialization.
     */
    public function __construct()
    {
        parent::__construct();
         $this->load->helper('listing');
    }

     public function get_all_count($params_arr='')
    {
        try {
              
              $result_arr = array();

           


              if(true===empty($params_arr['date'])){

              $this->db->from("business_rating AS br");          
              $this->db->select("count(*) AS ratings_received");

              $this->db->select("(select count(*) from profile_visit as pv where pv.iBusinessId='".$params_arr["business_id"]."') AS profile_visits", FALSE);


               $this->db->select("(select count(*) from navigated_user as nu where nu.iBusinessId='".$params_arr["business_id"]."') AS users_navigated", FALSE);

              $this->db->where("br.iBusinessId =",$params_arr["business_id"]);
      

              }else
              {
              $date=$params_arr["date"];           
              $newDate = date("Y-m-d", strtotime($date));

              $this->db->from("business_rating AS br");          
             $this->db->select("count(*) AS ratings_received");

              $this->db->select("(select count(*) from profile_visit as pv where pv.iBusinessId='".$params_arr["business_id"]."' and DATE_FORMAT(pv.dtAddedAt,'%Y-%m-%d')='".$newDate."') AS profile_visits", FALSE);


               $this->db->select("(select count(*) from navigated_user as nu where nu.iBusinessId='".$params_arr["business_id"]."' and DATE_FORMAT(nu.dtAddedAt,'%Y-%m-%d')='".$newDate."') AS users_navigated", FALSE);

              $this->db->where("br.iBusinessId =",$params_arr["business_id"]);
               $this->db->where("DATE_FORMAT(br.dtAddedAt,'%Y-%m-%d')",$newDate);
            
               }
              
              

             $result_obj = $this->db->get();
              //echo $this->db->last_query();exit;
              $result_arr = is_object($result_obj) ? $result_obj->result_array() : array();
    
            
            if(!is_array($result_arr) || count($result_arr) == 0){
                    throw new Exception('No records found.');
            }
            
            $success = 1;
        } catch (Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }
        
        //print_r($return_arr);
        $this->db->_reset_all();
        $return_arr["success"] = $success;
        $return_arr["message"] = $message;
        $return_arr["data"] = $result_arr;
        // print_r($return_arr);
        return $return_arr;
    }

}
