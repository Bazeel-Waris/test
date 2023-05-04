<?php

add_action( "wp_enqueue_scripts", "enqueue_bootstrap_cr");
add_action( "admin_enqueue_scripts", "enqueue_bootstrap_cr");
function enqueue_bootstrap_cr() {
    wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'assets/bootstrap/css/bootstrap.min.css' );
    wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '5.2.3', true );
}


// Define the plugin settings page
function mrrp_settings_page() {
    ?>
<div class="wrap">
    <h1>Manual Role Restrict Pages Settings</h1>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'mrrp_settings_group' );
            do_settings_sections( 'mrrp_settings_section' );
            submit_button();
            ?>
    </form>
</div>
<?php
}

// Register the plugin settings
function mrrp_register_settings() {
    register_setting( 'mrrp_settings_group', 'mrrp_allowed_roles' );
    register_setting( 'mrrp_settings_group', 'mrrp_restricted_pages' );
    register_setting( 'mrrp_settings_group', 'mrrp_restricted_posts' );
    register_setting( 'mrrp_settings_group', 'mrrp_restricted_categories' );
    register_setting( 'mrrp_settings_group', 'mrrp_redirect_page' );
}

// Add the plugin settings section and fields
function mrrp_settings_init() {
    add_settings_section( 'mrrp_settings_section', 'Manual Role Restrict Pages Settings', 'mrrp_settings_section_callback', 'mrrp_settings_section' );
    add_settings_field( 'mrrp_allowed_roles', 'Allowed Roles', 'mrrp_allowed_roles_callback', 'mrrp_settings_section', 'mrrp_settings_section' );
    add_settings_field( 'mrrp_restricted_pages', 'Restricted Pages', 'mrrp_restricted_pages_callback', 'mrrp_settings_section', 'mrrp_settings_section' );
    add_settings_field( 'mrrp_restricted_posts', 'Restricted Posts', 'mrrp_restricted_posts_callback', 'mrrp_settings_section', 'mrrp_settings_section' );
    add_settings_field( 'mrrp_restricted_categories', 'Restricted Categories', 'mrrp_restricted_categories_callback', 'mrrp_settings_section', 'mrrp_settings_section' );
    add_settings_field( 'mrrp_redirect_page', 'Redirect Page', 'mrrp_redirect_page_callback', 'mrrp_settings_section', 'mrrp_settings_section' );
}

// Render the plugin settings section
function mrrp_settings_section_callback() {
    echo 'Configure the allowed user roles, restricted pages, and redirect page for the Manual Role Restrict Pages plugin.';
}

// Render the allowed roles field
function mrrp_allowed_roles_callback() {
    $roles = get_editable_roles();
    $allowed_roles = get_option( 'mrrp_allowed_roles', array( 'administrator' ) );
    foreach ( $roles as $role => $details ) {
        $checked = in_array( $role, $allowed_roles ) ? 'checked' : '';
         echo "<label><input type='checkbox' name='mrrp_allowed_roles[]' value='$role' $checked> $details[name]</label><br>";
    }
}

// Render the restricted pages field
function mrrp_restricted_pages_callback() {
    $pages = get_pages();
    $restricted_pages = get_option( 'mrrp_restricted_pages', array() );
    foreach ( $pages as $page ) {
        $checked = in_array( $page->ID, $restricted_pages ) ? 'checked' : '';
        echo "<label><input type='checkbox' name='mrrp_restricted_pages[]' value='$page->ID' $checked> $page->post_title</label><br>";
    }
}

// Render the restricted posts field
function mrrp_restricted_posts_callback() {
    $posts = get_posts();
    $restricted_posts = get_option( 'mrrp_restricted_posts', array() );
    foreach ($posts as $post) {
        $checked =  in_array($post->ID, $restricted_posts) ? 'checked' : '';
        echo '<label><input type="checkbox" name="mrrp_restricted_posts[]" value="'.$post->ID.'" '.$checked.'> '.$post->post_title.'</label><br>';
    }
}


/*
// Render the restricted categories field
function mrrp_restricted_categories_callback() {
    $categories = get_categories();
    var_dump($categories);
    $restricted_categories = get_option( 'mrrp_restricted_categories', array() );
    foreach ($categories as $category) {
        $checked = in_array($category->term_id, $restricted_categories) ? 'checked' : '';
        echo '<label><input type="checkbox" name="mrrp_restricted_categories[]" value="'.$category->term_id.'" '.$checked.'> '.$category->name.'</label><br>';
    }
}
*/



function mrrp_restricted_categories_callback() {
    $categories = get_categories();
    $restricted_categories = get_option( 'mrrp_restricted_categories', array() );
    if (!is_array($restricted_categories)) {
        $restricted_categories = array($restricted_categories);
    }
    // var_dump($restricted_categories);
    foreach ($categories as $category) {
        $checked = in_array($category->term_id, $restricted_categories) ? 'checked' : '';
        echo '<label><input type="checkbox" name="mrrp_restricted_categories[]" value="'.$category->term_id.'" '.$checked.'> '.$category->name.'</label><br>';
    }
}


// Render the redirect page field
function mrrp_redirect_page_callback() {
    $redirect_page = get_option( 'mrrp_redirect_page', home_url() );
    echo "<div class='input-group mb-3'><input type='text' name='mrrp_redirect_page' value='$redirect_page'>";
}



// Restrict access to certain pages based on user roles
function mrrp_restrict_pages() {
    $user = wp_get_current_user();
    $allowed_roles = get_option( 'mrrp_allowed_roles', array( 'administrator' ) );
    $restricted_pages = get_option( 'mrrp_restricted_pages', array() );
    $restricted_posts = get_option( 'mrrp_restricted_posts', array() );
    $restricted_categories = get_option( 'mrrp_restricted_categories', array() );
    $redirect_page = get_option( 'mrrp_redirect_page', home_url() );
    
    


    if ( in_array( get_the_ID(), $restricted_pages ) && ! array_intersect( $allowed_roles, $user->roles ) ) {
        // user doesn't have the allowed roles, so redirect them to the specified page
        wp_redirect( $redirect_page );
        exit;
    }

    if ( in_array( get_the_ID(), $restricted_posts ) && ! array_intersect( $allowed_roles, $user->roles ) ) {
        // user doesn't have the allowed roles, so redirect them to the specified page
        wp_redirect( $redirect_page );
        exit;
    }
    if ( in_array( get_query_var('cat'), $restricted_categories ) && ! array_intersect( $allowed_roles, $user->roles ) ) {
        // user doesn't have the allowed roles, so redirect them to the specified page
        wp_redirect( $redirect_page );
        exit;
    } 
}

add_action( 'template_redirect', 'mrrp_restrict_pages' );

// Add the plugin settings page
// function mrrp_add_settings_page() {
//     add_menu_page( 'Manual Role Restrict Pages', 'Manual Role Restrict Pages', 'manage_options', 'mrrp-settings', 'mrrp_settings_page' );
// }
// add_action( 'admin_menu', 'mrrp_add_settings_page' );

// Register the plugin settings
add_action( 'admin_init', 'mrrp_register_settings' );
add_action( 'admin_init', 'mrrp_settings_init' );

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

*/