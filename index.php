<?php
/*
Plugin Name: The Member Plugin
Plugin URI: http://www.shopifylearners.com
Description: In this plugin we provide light weight complete membership system. Enjoy!!
Version: 1.0
Author: DevHub Team
Author URI: http://www.shopifylearners.com
*/


//adding menus file.
require_once('adding_menu.php');

//adding bootstrap file
require_once("adding_bootstrap.php");

//registering new user with desired membership from admin side (b)
require_once('admin_side_shortcodes_b.php');

//registering new user with desired membership from frontend side (a)
require_once('front_side_shortcodes_a.php');

//adding new membership level file.
require_once("new_membership_level.php");

//adding membership content restriction file.
require_once("membership_content_restriction.php");

//adding roles from option table to custom table file (table name: wp_roles).
require_once("role_custom_table.php");