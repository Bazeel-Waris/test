<?php

// Add a new custom user role with price input field
function add_custom_user_role() {
    $roles = wp_roles();
    $role_name = $_POST['role_name'];
    $role_price = $_POST['role_price'];

    // Check if the role already exists
    if ( !in_array( $role_name, $roles->get_names() ) ) {
        add_role( $role_name, $role_name, array( 'read' => true ) );
        update_option( $role_name . '_price', $role_price );
    } else {
        echo 'Role already exists!';
    }
}

// Add custom fields for user role price
function add_user_role_price_field( $user ) {
    $user_role = $user->roles[0];
    $user_role_price = get_option( $user_role . '_price', '' );

    // Output the price input field
    echo '<h3>User Role Price</h3>';
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="user_role_price">Price:</label></th>';
    echo '<td><input type="number" name="user_role_price" id="user_role_price" value="' . esc_attr( $user_role_price ) . '" class="regular-text"></td>';
    echo '</tr>';
    echo '</table>';
}

// Save the user role price
function save_user_role_price( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    $user_role = get_user_by( 'id', $user_id )->roles[0];
    $user_role_price = sanitize_text_field( $_POST['user_role_price'] );

    update_option( $user_role . '_price', $user_role_price );
}

// Add custom fields to the user role edit screen
function add_user_role_price_field_admin() {
    $user_role = $_GET['role'];
    $user_role_price = get_option( $user_role . '_price', '' );

    // Output the price input field
    echo '<h3>User Role Price</h3>';
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="user_role_price">Price:</label></th>';
    echo '<td><input type="number" name="user_role_price" id="user_role_price" value="' . esc_attr( $user_role_price ) . '" class="regular-text"></td>';
    echo '</tr>';
    echo '</table>';
}

// Save the user role price from the admin screen
function save_user_role_price_admin( $user_role ) {
    $user_role_price = sanitize_text_field( $_POST['user_role_price'] );
    update_option( $user_role . '_price', $user_role_price );
}

// Add the custom user role price fields to the user profile page
add_action( 'show_user_profile', 'add_user_role_price_field' );
add_action( 'edit_user_profile', 'add_user_role_price_field' );

// Save the user role price when the user profile is updated
add_action( 'personal_options_update', 'save_user_role_price' );
add_action( 'edit_user_profile_update', 'save_user_role_price' );

// Add the custom user role price fields to the user role edit screen
add_action( 'edit_role_form_fields', 'add_user_role_price_field_admin' );

// Save the user role price when the user role edit screen is updated
add_action( 'edited_role', 'save_user_role_price_admin' );

// Add the custom user role creation form to the admin dashboard
function custom_user_role_form() {
    echo view_roles();
echo '<div class="wrap">';
echo '<h2>Create a New Custom User Role</h2>';
echo '<form method="post" action="">';
echo '<table class="form-table">';
echo '<tr>';
echo '<th><label for="role_name">Role Name:</label></th>';
echo '<td><input type="text" name="role_name" id="role_name" class="regular-text"></td>';
echo '</tr>';
echo '<tr>';
echo '<th><label for="role_price">Price:</label></th>';
echo '<td><input type="number" name="role_price" id="role_price" class="regular-text"></td>';
echo '</tr>';
echo '</table>';
echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Create Custom Role"></p>';
echo '</form>';
echo '</div>';
}

// Handle the creation of the custom user role
function handle_custom_user_role_creation() {
if ( isset( $_POST['submit'] ) ) {
add_custom_user_role();
}
}

/*
// Add the custom user role creation form to the admin dashboard menu
function add_custom_user_role_menu() {
add_menu_page(
'Custom User Roles',
'Custom User Roles',
'manage_options',
'custom-user-roles',
'custom_user_role_form',
'dashicons-groups'
);
}

// Initialize the plugin
add_action( 'admin_menu', 'add_custom_user_role_menu' );*/
add_action( 'admin_init', 'handle_custom_user_role_creation' );



// Code to view roles.
function view_roles(){

$output='<table class="table table-striped"><tr><th>Role</th><th>Price</th></tr>';
    // $roles = wp_roles();

  //-----------------
  
  $roles = wp_roles()->get_names();
    
  $excluded_roles = array(
      'administrator',
      'editor',
      'author',
      'subscriber',
      'contributor',
      'roles',
      'prices'
  );
  
  
  // Remove the excluded roles
  foreach($excluded_roles as $role) {
      unset($roles[$role]);
  }

      //-------------------


    foreach ( $roles as $role_name ) {
        $role_price = get_option( $role_name . '_price' );
        if ( ! empty( $role_price ) ) {
            $output .='<tr><td>'.$role_name . '</td><td>' . $role_price.'</td></tr>';
        }
    
    }
    return $output;
    }

    