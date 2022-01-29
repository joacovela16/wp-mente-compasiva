<?php


/**
 * Plugin Name: Core - Mente Compasiva
 */
include_once "core/constants.php";
include_once "core/utils.php";
include_once "core/post_lib.php";
include_once "core/MCRestAPI.php";

include_once "widgets/MCMetaPost.php";
include_once "widgets/MCPostExplorer.php";
include_once "widgets/MCPermissionNavbar.php";

include_once "core/MCPermissionLib.php";
include_once "core/MCUserLib.php";
include_once "core/MCAssetLib.php";
include_once "core/MCSettingPanel.php";

add_filter('send_email_change_email', '__return_false');
add_filter('send_password_change_email', '__return_false');

add_action("rest_api_init", function () {
    (new MCRestAPI())->register_routes();
});

(new MCUserLib())->init();
(new MCAssetLib())->init();
(new MCMetaPost())->init();
(new MCSettingPanel())->init();
(new MCPermissionLib())->init();

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

