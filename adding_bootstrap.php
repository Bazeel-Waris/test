<?php 
// Adding bootstrap for nice layouts.

add_action( "wp_enqueue_scripts", "enqueue_bootstrap_iur");
add_action( "admin_enqueue_scripts", "enqueue_bootstrap_iur");
function enqueue_bootstrap_iur() {
    wp_enqueue_style( 'bootstrap-css', plugin_dir_url( __FILE__ ) . 'assets/bootstrap/css/bootstrap.min.css' );
    wp_enqueue_script( 'bootstrap-js', plugin_dir_url( __FILE__ ) . 'assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '5.2.3', true );
}