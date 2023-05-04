<?php


//Admin side main page code
function adding_users_callback(){
    echo '<h1 class=display-2 >Welcome to THEmember.</h1>';
    echo '<p class="mt-5 ml-1" style="font-size:20px; font-weight:500;">To view Membership Holders</p>';
    echo '<a href=" '.home_url('/wp-admin/admin.php?page=display_users'). '">Click Here</a>';
    echo '<p class="mt-3 ml-1" style="font-size:20px; font-weight:500;">To add new Membership Holder</p>';
    echo '<a href=" '.home_url('/wp-admin/admin.php?page=new_user'). '">Click Here</a>';
    echo '<h1 class="display-4 mt-5" >Frontend Shortcodes.</h1>';
    echo '<p class="mt-5 ml-1" style="font-size:20px; font-weight:500;">To view Membership holders on front page use shortcode : [registered_users_table]</p>';
    echo '<p class="mt-3 ml-1" style="font-size:20px; font-weight:500;">To Add New Membership holders on front page use shortcode : [my_user_registration]</p>';
 }

 //-----------------------------------------------


// Admin side calling function table
function display_users_callback(){
    echo '<h1 class="display-3 mb-3">Membership Holders</h1>';
    echo display_registered_users_table();
}


//-------------------------------------------------
// Admin side calling function form
function new_users_callback(){
    echo view_roles_inter();
    $output = '';
        $output .= '<h1 class="ms-4 display-4 mb-3">Add New Membership Holders</h1>';
        $output .='<div class="container"><div class="row"><div class="col-5">';
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
       
        $output .= '<label class=" mt-3 mb-3 " for="the_role">Role</label>';
        $output .= '<select class="mt-3 mb-3 ms-3" name="the_role" id="the_role">';

       


        
  
    $roles = wp_roles()->get_names();
    // var_dump($roles);
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

        foreach ( $roles as $role => $name ) {
            $output .= '<option value="' . $role . '">' . $name . '</option>';
        }
        
        $output .= '</select>';
        $output .= '<br>';
        $output .= '<input class="btn btn-warning" type="submit" name="submit" value="Register">';
        $output .= '</form>';
        $output .='</div ></div ></div>';
    
        echo  $output;  
}

// Code to view roles.
add_shortcode( "view_membership_prices","view_roles_inter" );
function view_roles_inter(){

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

        // foreach ( $roles->get_names() as $role_name ) {
        foreach ( $roles as $role_name ) {
            $role_price = get_option( $role_name . '_price' );
            // if ( ! empty( $role_price ) ) {
                $output .='<tr><td>'.$role_name . '</td><td>' . $role_price.'</td></tr>';
            // }
        }
        return $output;
        }