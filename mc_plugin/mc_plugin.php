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


function mc_plugin_activated()
{

    mc_do_pages();

    add_filter('send_email_change_email', '__return_false');
    add_filter('send_password_change_email', '__return_false');



    add_action("user_register", "mc_user_register_interceptor");
    add_action("show_user_profile", "wporg_usermeta_form_field_birthday");
    add_action("wp_ajax_get_post", "mc_get_post_by_id");

    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure("/%postname%/");
}

function mc_plugin_deactivated()
{
    mc_undo_post();
    mc_unregister_taxonomy();
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure("/%postname%/");
}


add_action("rest_api_init", function ()  {
    $restController = new MCRestAPI();
    $restController->register_routes();
});

add_action("init", "mc_do_post");
add_action("init", "mc_register_taxonomy");
add_action("init", "mc_register_taxonomy");

register_activation_hook(__FILE__, "mc_plugin_activated");
register_deactivation_hook(__FILE__, 'mc_plugin_deactivated');
register_deactivation_hook(__FILE__, 'mc_undo_pages');