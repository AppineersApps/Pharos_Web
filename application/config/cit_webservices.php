<?php

defined('BASEPATH') OR exit('No direct script access allowed');

#####GENERATED_CONFIG_SETTINGS_START#####

$config["admin_update_user_status_in_listing"] = array(
    "title" => "Admin Update User status In Listing",
    "folder" => "admin",
    "method" => "GET_POST",
    "params" => array(
        "user_id"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["change_mobile_number"] = array(
    "title" => "Change Mobile Number",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "new_mobile_number",
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["change_password"] = array(
    "title" => "Change Password",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "old_password",
        "new_password",
        "user_id",
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["check_unique_user"] = array(
    "title" => "Check Unique User",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "type",
        "email",
        "mobile_number",
        "user_name"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["country_list"] = array(
    "title" => "Country List",
    "folder" => "tools",
    "method" => "GET_POST",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["country_with_states"] = array(
    "title" => "Country With States",
    "folder" => "tools",
    "method" => "GET_POST",
    "params" => array(
        "country_id"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["delete_account"] = array(
    "title" => "Delete Account",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_access_token",
        "user_id"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["edit_about_business"] = array(
    "title" => "Edit About Business",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(     
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["edit_business_working_hours"] = array(
    "title" => "edit_business_working_hours",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(     
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);



$config["notification"] = array(
    "title" => "User Notification Webservices",
    "folder" => "basic_appineers_master",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["change_business_status"] = array(
    "title" => "Change_business_status",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(     
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["edit_business"] = array(
    "title" => "Edit Business",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(     
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["report_abusive_user"] = array(
    "title" => "report_abusive_user",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(     
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["edit_profile"] = array(
    "title" => "Edit Profile",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "first_name",
        "last_name",
        "user_profile",
        "dob",
        "address",
        "city",
        "latitude",
        "longitude",
        "state_id",
        "zipcode",
        "user_name",
        "mobile_number"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["forgot_password"] = array(
    "title" => "Forgot Password",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "email"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["forgot_password_phone"] = array(
    "title" => "Forgot Password Phone",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "mobile_number"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["subscription_purchase"] = array(
    "title" => "Subscription purchase",
    "folder" => "subscription",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["get_config_paramaters"] = array(
    "title" => "Get Config Paramaters",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["get_template_message"] = array(
    "title" => "Get Template Message",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "template_code",
        "user_name",
        "otp"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["go_ad_free"] = array(
    "title" => "Go Ad Free",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "one_time_transaction_data"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["logout"] = array(
    "title" => "Logout",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["post_a_feedback"] = array(
    "title" => "Post a Feedback",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "feedback",
        "device_type",
        "device_model",
        "device_os",
        "image_1",
        "image_2",
        "image_3",
        "images_count",
        "app_version",
        "user_id",
        "user_access_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["reset_password"] = array(
    "title" => "Reset Password",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "new_password",
        "reset_key"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["reset_password_confirmation"] = array(
    "title" => "Reset Password Confirmation",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "reset_key"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["reset_password_phone"] = array(
    "title" => "Reset Password Phone",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "new_password",
        "mobile_number",
        "reset_key"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["send_sms"] = array(
    "title" => "Send Sms",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "mobile_number",
        "message"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["send_verification_link"] = array(
    "title" => "Send Verification Link",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "email"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["social_login"] = array(
    "title" => "Social Login",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "social_login_type",
        "social_login_id",
        "device_type",
        "device_model",
        "device_os",
        "device_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["social_sign_up"] = array(
    "title" => "Social Sign Up",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "longitude",
        "state_id",
        "zipcode",
        "device_type",
        "device_model",
        "device_os",
        "device_token",
        "social_login_type",
        "social_login_id",
        "first_name",
        "last_name",
        "user_name",
        "email",
        "mobile_number",
        "user_profile",
        "dob",
        "address",
        "city",
        "latitude"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["social_sign_up_business"] = array(
    "title" => "Social Sign Up Business",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "zipcode",
        "device_type",
        "device_model",
        "device_os",
        "device_token",
        "social_login_type",
        "social_login_id",
        "email",  
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["states_list"] = array(
    "title" => "States List",
    "folder" => "basic_appineers_master",
    "method" => "GET",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["insight_the_day"] = array(
    "title" => "Insight the day",
    "folder" => "basic_appineers_master",
    "method" => "GET",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);



$config["rated_business"] = array(
    "title" => "Rated Business",
    "folder" => "basic_appineers_master",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["favorites_business"] = array(
    "title" => "Favorites_business",
    "folder" => "basic_appineers_master",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["get_ratings_list"] = array(
    "title" => "Get Ratings List",
    "folder" => "basic_appineers_master",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(
       
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["products"] = array(
    "title" => "Products Add Update get delete",
    "folder" => "basic_appineers_master",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["business_type_list"] = array(
    "title" => "Business Type List",
    "folder" => "basic_appineers_master",
    "method" => "GET",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["get_near_business"] = array(
    "title" => "get near Business",
    "folder" => "basic_appineers_master",
    "method" => "GET",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["user_navigation"] = array(
    "title" => "User_navigation",
    "folder" => "basic_appineers_master",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["profile_visit"] = array(
    "title" => "Profile_visit",
    "folder" => "basic_appineers_master",
    "method" => $_SERVER['REQUEST_METHOD'],
    "params" => array(),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["get_near_business_v1"] = array(
    "title" => "get near Business v1",
    "folder" => "basic_appineers_master",
    "method" => "GET",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);


$config["get_business_profile"] = array(
    "title" => "Get Business Profile",
    "folder" => "basic_appineers_master",
    "method" => "GET",
    "params" => array(
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);



$config["static_pages"] = array(
    "title" => "Static Pages",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "page_code"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["update_device_token"] = array(
    "title" => "Update Device Token",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "device_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["update_page_version"] = array(
    "title" => "Update Page Version",
    "folder" => "tools",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "page_type"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["update_push_notification_settings"] = array(
    "title" => "Update Push Notification Settings",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "user_id",
        "user_access_token",
        "notification"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["user_email_confirmation"] = array(
    "title" => "User Email Confirmation",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "confirmation_code"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["user_login_email"] = array(
    "title" => "User Login Email",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "email",
        "password",
        "device_type",
        "device_model",
        "device_os",
        "device_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["user_login_phone"] = array(
    "title" => "User Login Phone",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "mobile_number",
        "password",
        "device_type",
        "device_model",
        "device_os",
        "device_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);
$config["user_sign_up_email"] = array(
    "title" => "User Sign Up Email",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "device_type",
        "device_model",
        "device_os",
        "device_token",
        "first_name",
        "last_name",
        "user_name",
        "email",
        "mobile_number",
        "user_profile",
        "dob",
        "password",
        "address",
        "city",
        "latitude",
        "longitude",
        "state_id",
        "zipcode"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["business_sign_up_email"] = array(
    "title" => "Business Sign Up Email",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["user_sign_up_phone"] = array(
    "title" => "User Sign Up Phone",
    "folder" => "basic_appineers_master",
    "method" => "GET_POST",
    "params" => array(
        "first_name",
        "last_name",
        "user_name",
        "email",
        "mobile_number",
        "user_profile",
        "dob",
        "password",
        "address",
        "city",
        "latitude",
        "longitude",
        "state_id",
        "zipcode",
        "device_type",
        "device_model",
        "device_os",
        "device_token"
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["user"] = array(
    "title" => "Get User Details",
    "folder" => "user_details",
    "method" => "GET",
    "params" => array(
       
    ),
    "token" => "",
    "payload" => array(
    ),
    "target" => ""
);

$config["delete_api_log"] = array(
    "title" => "delete_api_log",
    "folder" => "misc",
    "method" => "GET_POST",
    "params" => array(
    )
);
#####GENERATED_CONFIG_SETTINGS_END#####

/* End of file cit_webservices.php */
/* Location: ./application/config/cit_webservices.php */
    
