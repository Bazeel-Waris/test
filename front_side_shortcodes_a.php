<?php




// Add a shortcode to display the registration form frontend
add_shortcode( 'my_user_registration', 'my_user_registration_shortcode' );


function my_user_registration_shortcode() {
    $output = '';
    
    if ( is_user_logged_in() ) {
        $output .= '<p>You are already logged in.</p>';
    } else {
        $output .= '<form method="post" id="my-user-registration-form" class="my-user-registration-form">';
        
        $output .= '<div class="form-group">';
        $output .= '<label for="username">Username</label>';
        $output .= '<input class="form-control" type="text" name="username" id="username" required>';
        $output .= '</div>';

        
        $output .= '<label for="email">Email Address</label>';
        $output .= '<input class="form-control" type="email" name="email" id="email" required>';
       
        $output .= '<label for="password">Password</label>';
        $output .= '<input class="form-control" type="password" name="password" id="password" required>';
       
        $output .= '<label for="first_name">First Name</label>';
        $output .= '<input class="form-control" type="text" name="first_name" id="first_name">';
       
        $output .= '<label for="last_name">Last Name</label>';
        $output .= '<input class="form-control" type="text" name="last_name" id="last_name">';
        
        $output .= '<label for="phone_number">Phone Number</label>';
        $output .= '<input class="form-control" type="tel" name="phone_number" id="phone_number">';
       
        $output .= '<label for="the_color">Color</label>';
        $output .= '<input class="form-control" type="text" name="the_color" id="the_color">';
       
        $output .= '<label  for="the_role">Role</label>';
        
        $output .= '<select name="the_role" id="the_role">';
        
       

//-----------------
  
    $roles = wp_roles()->get_names();
    
    $excluded_roles = array(
        'administrator',
        'editor',
        'author',
        'subscriber',
        'contributor',
    );
    
    
    // Remove the excluded roles
    foreach($excluded_roles as $role) {
        unset($roles[$role]);
    }

//-------------------
        foreach ( $roles as $role => $name ) {
            $output .= '<option value="' . $role . '">' . $name . '</option>';
        }
        
        $output .= '</select>';
        $output .= '<br>';                                                                                    
        $output .= '<input type="submit" name="submit" value="Register">';
        $output .= '</form>';
       
    }
    
    return $output;
}





// Handle form submission
add_action( 'init', 'my_user_registration_submit' );

function my_user_registration_submit() {
    if ( isset( $_POST['submit'] ) && $_POST['submit'] == 'Register' ) {
        $username = sanitize_user( $_POST['username'] );
        $email = sanitize_email( $_POST['email'] );
        $password = sanitize_text_field( $_POST['password'] );
        $first_name = sanitize_text_field( $_POST['first_name'] );
        $last_name = sanitize_text_field( $_POST['last_name'] );
        $phone_number = sanitize_text_field( $_POST['phone_number'] );
        $the_color = sanitize_text_field( $_POST['the_color'] );
        $the_role = sanitize_text_field( $_POST['the_role'] );

        
        
        $userdata = array(
            'user_login' => $username,
            'user_email' => $email,
            'user_pass' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
            // 'role' => 'subscriber'
            'role' => $the_role,
    
        );
        
        $user_id = wp_insert_user( $userdata );
        

//inserting data through $wpdb->insert .

global $wpdb;
$table_name=$wpdb->prefix.'membership_table';
$wpdb->insert($table_name,array(
    'membership_holder'=>$username,
    'membership_level'=>$the_role,
    'user_id'=> $user_id,
)); 

//-------------------------------------------------------------------------




        
        if ( is_wp_error( $user_id ) ) {
            $error_message = $user_id->get_error_message();
            wp_die( $error_message );
        } else {
            update_user_meta( $user_id, 'phone_number', $phone_number );
            update_user_meta( $user_id, 'first_name', $first_name );
            update_user_meta( $user_id, 'last_name', $last_name );
            update_user_meta( $user_id, 'user_pass', $password );
            update_user_meta( $user_id, 'the_color', $the_color );
            update_user_meta( $user_id, 'the_role', $the_role );
            wp_redirect( home_url() );

            exit;
        }
    }
}
    



//displaying the user with subscriber role

// Register the shortcode function
add_shortcode( 'registered_users_table', 'display_registered_users_table' );

// Shortcode function to display the user table at frontend
function display_registered_users_table() {

    $all_roles = get_editable_roles();
    $role_slugs = array_keys( $all_roles );
    $args = array(
        'role__in' => $role_slugs,
    );
    // Set up the user query
    /* $args = array(
        //  'role' => 'subscriber',
         'role__in' => array( 'subscriber', 'editor','bronze' ),
    );*/
    $user_query = new WP_User_Query( $args ); ?>

<?php

    // Build the user table HTML
    $output  = '<table class="table table-striped">';
    $output .= '<thead>';
    $output .= '<tr>';
    $output .= '<th scope="col">Username</th>';
    $output .= '<th scope="col">Email Address</th>';
    $output .= '<th scope="col">First Name</th>';
    $output .= '<th scope="col">Last Name</th>';
    $output .= '<th scope="col">Phone Number </th>';
    // $output .= '<th scope="col">Password </th>';
    $output .= '<th scope="col">Color </th>';
    $output .= '<th scope="col">Role </th>';
    $output .= '</tr>';
    $output .= '</thead>';
    $output .= '<tbody>';
    

    // Loop through the results and add user rows to the table
    if ( ! empty( $user_query->results ) ) {


            foreach ( $user_query->results as $user){
            $username = $user->user_login;
            $useremail = $user->user_email;
            $password = $user->user_pass;
            $first_name = $user->first_name;
            $last_name = $user->last_name;
            $phone_number = get_user_meta( $user->ID, 'phone_number', true );
            $final_color = get_user_meta( $user->ID, 'the_color', true );
            $final_role = get_user_meta( $user->ID, 'the_role', true );
            $output .= '<tr>';
            $output .= '<td>' . $username . '</td>';
            $output .= '<td>' . $useremail . '</td>';
            $output .= '<td>' . $first_name . '</td>';
            $output .= '<td>' . $last_name . '</td>';
            $output .= '<td>' . $phone_number . '</td>';
            // $output .= '<td>' . $password . '</td>';
            $output .= '<td>' . $final_color . '</td>';
            $output .= '<td>' . $final_role . '</td>';
            $output .= '</tr>';
        }}

     else {
        $output .= '<tr>';
        $output .= '<td colspan="4">No users found.</td>';
        $output .= '</tr>';
    }

    // Close the table HTML and return the output
    $output .= '</tbody>';
    $output .= '</table>';
    return $output;
}


// making membership table

register_activation_hook(__FILE__, "making_table");

function making_table(){
global $wpdb;
$table_name=$wpdb->prefix.'membership_table';
$charset=$wpdb->get_charset_collate();
$sql="CREATE TABLE $table_name (
id mediumint(9) NOT NULL AUTO_INCREMENT,
user_id mediumint(9) NOT NULL ,
membership_holder varchar (50) NOT NULL,
membership_level varchar (50) NOT NULL,
PRIMARY KEY (id)
)$charset;";

require_once(ABSPATH .'wp-admin/includes/upgrade.php');
dbDelta($sql);

}



?>