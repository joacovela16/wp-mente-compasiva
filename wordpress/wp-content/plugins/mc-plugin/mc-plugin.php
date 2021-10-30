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

add_action( "init", "mc_do_post" );
add_action( "init", "mc_register_taxonomy" );
add_action("user_register" , "mc_user_register_interceptor");
add_action("show_user_profile", "wporg_usermeta_form_field_birthday");
register_deactivation_hook( __FILE__, 'mc_undo_post' );
register_deactivation_hook( __FILE__, 'mc_unregister_taxonomy' );
