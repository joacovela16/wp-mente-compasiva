<?php


/**
 * Plugin Name: Core - Mente Compasiva
 */
include_once "core/constants.php";
include_once "core/utils.php";
include_once "core/taxonomy_lib.php";
include_once "core/metadata_lib.php";
include_once "core/post_interceptor.php";
include_once "core/user_lib.php";
include_once "core/post_lib.php";
include_once "core/rest_api.php";
$restController = new MCRestAPI();

add_filter( 'send_email_change_email', '__return_false' );
add_filter( 'send_password_change_email', '__return_false' );

add_action( "init", "mc_do_post" );
add_action( "init", "mc_register_taxonomy" );
add_action( "rest_api_init", function () use ( $restController ) {
	$restController->register_routes();
} );

add_action( "user_register", "mc_user_register_interceptor" );
add_action( "show_user_profile", "wporg_usermeta_form_field_birthday" );
add_action("wp_ajax_get_post", "mc_get_post_by_id");

register_deactivation_hook( __FILE__, 'mc_undo_post' );
register_deactivation_hook( __FILE__, 'mc_unregister_taxonomy' );
