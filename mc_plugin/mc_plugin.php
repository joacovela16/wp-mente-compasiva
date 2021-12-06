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
    $restController = new MCRestAPI();

    mc_do_pages();
//    mc_register_taxonomy();


    add_filter('send_email_change_email', '__return_false');
    add_filter('send_password_change_email', '__return_false');


    add_action("rest_api_init", function () use ($restController) {
        $restController->register_routes();
    });

    add_action("user_register", "mc_user_register_interceptor");
    add_action("show_user_profile", "wporg_usermeta_form_field_birthday");
    add_action("wp_ajax_get_post", "mc_get_post_by_id");
}

function mc_plugin_deactivated()
{
    mc_undo_post();
    mc_unregister_taxonomy();
}


add_action("init", "mc_do_post");
add_action("init", "mc_register_taxonomy");
register_activation_hook(__FILE__, "mc_plugin_activated");
register_deactivation_hook(__FILE__, 'mc_plugin_deactivated');
