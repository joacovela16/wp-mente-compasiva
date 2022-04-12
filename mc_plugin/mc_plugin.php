<?php


/**
 * Plugin Name: Core - Mente Compasiva
 */
include_once "core/constants.php";
include_once "core/utils.php";
include_once "core/post_lib.php";
include_once "core/MCRestAPI.php";
include_once "core/MCKeyService.php";

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
(new MCSettingPanel())->init();
(new MCPermissionLib())->init();
(new MCActions())->init();

add_action("init", "mc_do_post");

register_activation_hook(__FILE__, "mc_plugin_activated");
register_deactivation_hook(__FILE__, 'mc_plugin_deactivated');
register_deactivation_hook(__FILE__, 'mc_undo_pages');


add_action("init", function () {
    add_rewrite_rule("^login\/?", "wp-login.php", 'top');
    add_rewrite_rule("^register\/([A-Za-z0-9-=\+]+)[\/]?$", 'wp-login.php?action=register&t=$matches[1]', 'top');
});

add_filter('option_users_can_register', function ($value) {
    $script = basename(parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH));

    if ($script == 'wp-login.php' && ($_GET['action'] ?? '') === 'lostpassword') {
        return false;
    }

    if ($script == 'wp-login.php' && ($_GET['action'] ?? '') === 'register') {
        $arr = explode("/", $_SERVER['REQUEST_URI'] ?? "");
        if (isset($arr[2])) {
            $token = $arr[2];
            $pending = get_option(MC_PASSWORD_GEN, []);
            $item = array_find($pending, fn($x) => $x['key'] === $token);
            if (!empty($item)) {
                $k = $item['key'];
                $t = $item['token'];
                $keyService = new MCKeyService();
                $result = $keyService->validate_recovery_mode_key($t, $k, MC_MAX_TIMEOUT);
                if (!is_wp_error($result)) {
//                    $pending = array_values(array_filter($pending, fn($x) => $x['token'] !== $t));
//                    update_option(MC_PASSWORD_GEN, $pending);
                    return true;
                }
            } else {
                return false;
            }
        }
    }

    if ($script == 'wp-login.php' && empty($_GET['action'])) {
        return false;
    }

    return $value;
});

function mc_plugin_activated()
{

    mc_do_pages();
    update_option('users_can_register', true);


    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure("/%postname%/");
}

function mc_plugin_deactivated()
{
    mc_undo_post();
    update_option('users_can_register', false);
}

