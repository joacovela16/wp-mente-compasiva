<?php


/**
 * Plugin Name: Core - Mente Compasiva
 */
include_once "core/constants.php";
include_once "core/utils.php";
include_once "core/taxonomy_lib.php";
include_once "core/metadata_lib.php";
include_once "core/post_interceptor.php";
include_once "core/MCUserLib.php";
include_once "core/post_lib.php";
include_once "core/MCRestAPI.php";
include_once "core/ajax_actions.php";
include_once "core/search_engine.php";
include_once "core/renderers.php";
include_once "core/permission_lib.php";
include_once "widgets/MCPostExplorer.php";
include_once "widgets/MCMetaPost.php";
include_once "core/MCAssetLib.php";
include_once "core/MCSettingPanel.php";

add_filter('send_email_change_email', '__return_false');
add_filter('send_password_change_email', '__return_false');
add_action("wp_ajax_get_post", "mc_get_post_by_id");

add_action("rest_api_init", function () {
    (new MCRestAPI())->register_routes();
});

(new MCUserLib())->init();
(new MCAssetLib())->init();
(new MCMetaPost())->init();
(new MCSettingPanel())->init();

add_action("init", "mc_do_post");

register_activation_hook(__FILE__, "mc_plugin_activated");
register_deactivation_hook(__FILE__, 'mc_plugin_deactivated');
register_deactivation_hook(__FILE__, 'mc_undo_pages');

function mc_plugin_activated()
{

    mc_do_pages();


    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure("/%postname%/");
}

function mc_plugin_deactivated()
{
    mc_undo_post();
//    mc_unregister_taxonomy();
//    global $wp_rewrite;
//    $wp_rewrite->set_permalink_structure("/%postname%/");
}

