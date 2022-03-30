<?php


/**
 * Plugin Name: Core - Mente Compasiva
 */
include_once "core/constants.php";
include_once "core/utils.php";
include_once "core/post_lib.php";
include_once "core/MCRestAPI.php";

include_once "widgets/MCDirectoryExplorer.php";

include_once "core/MCPermissionLib.php";
include_once "core/MCUserLib.php";
include_once "core/MCAssetLib.php";
include_once "core/MCSettingPanel.php";
include_once "core/MCActions.php";

add_action("rest_api_init", function () {
    (new MCRestAPI())->register_routes();
});

(new MCUserLib())->init();
(new MCAssetLib())->init();
//(new MCMetaPost())->init();
(new MCSettingPanel())->init();
(new MCPermissionLib())->init();
(new MCActions())->init();

add_action("init", "mc_do_post");

register_activation_hook(__FILE__, "mc_plugin_activated");
register_deactivation_hook(__FILE__, 'mc_plugin_deactivated');
register_deactivation_hook(__FILE__, 'mc_undo_pages');


add_action("init", function () {
    //add_rewrite_rule("^profile\/?", "index.php?pagename=mc_profile", 'top');
//    add_rewrite_rule("^register\/?", "index.php?pagename=register", 'top');
});

function mc_plugin_activated()
{

    mc_do_pages();
//    update_option('users_can_register', false);

    add_filter('option_users_can_register', function($value) {
        $script = basename(parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH));

        if ($script == 'wp-login.php') {
            $value = false;
        }

        return $value;
    });

    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure("/%postname%/");
}

function mc_plugin_deactivated()
{
    mc_undo_post();
    update_option('users_can_register', false);
}

