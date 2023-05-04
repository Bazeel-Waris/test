<?php  

//Adding main menu 

function adding_main_menu(){
    add_menu_page( 'The Users', 'The Member', 'manage_options', 'adding_users', 'adding_users_callback');
    
    }

add_action( 'admin_menu', 'adding_main_menu');



//Adding sub menu's 
function adding_main_sub(){
    add_submenu_page( 'adding_users', 'Display Users', 'Membership Holders List', 'manage_options', 'display_users', 'display_users_callback' );
    add_submenu_page( 'adding_users', 'Add New User', 'Add New  Membership Holder', 'manage_options', 'new_user', 'new_users_callback' );
    add_submenu_page( 'adding_users' , 'Restrict Membership Content', 'Restrict Membership Content', 'manage_options', 'mrrp-settings', 'mrrp_settings_page');
    add_submenu_page( 'adding_users','Custom User Roles','Add New Membership Level', 'manage_options', 'custom-user-roles', 'custom_user_role_form', );
    
    }

add_action( 'admin_menu', 'adding_main_sub');

?>