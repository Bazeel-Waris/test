<?php 
 function custom_roles_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'roles';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        role_name varchar(255) NOT NULL,
        role_capabilities longtext NOT NULL,
        price int(11) NOT NULL DEFAULT 0,
        PRIMARY KEY (role_name)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'custom_roles_activate' );


function custom_roles_populate() {
    //------------
    $role_value=get_option('role_price');
    //------------
    
    global $wpdb, $wp_roles;
    $table_name = $wpdb->prefix . 'roles';

    foreach ( $wp_roles->roles as $role_name => $role_info ) {
        $wpdb->replace(
            $table_name,
            array(
                'role_name' => $role_name,
                'role_capabilities' => serialize( $role_info['capabilities'] ),
                //'price' => 0 // Replace 0 with the desired price for each role
                'price' => $role_value// Replace 0 with the desired price for each role
            ),
            array(
                '%s',
                '%s',
                '%d'
            )
        );
    }
}
add_action( 'init', 'custom_roles_populate' );


//----------------------------------------------