<?php
include_once "template-parts/utils.php";
include_once "constants.php";
include_once "template-parts/dom.php";
include_once "template-parts/assets_installer.php";

add_action("wp_head", "mc_wp_head");
add_action('widgets_init', 'mc_widgets_init');
add_filter('show_admin_bar', '__return_false');
add_action('login_form_logout', "mc_logout");

add_action("init", function () {
    add_rewrite_rule("^about\/?", "index.php?pagename=mc_about", 'top');
    add_rewrite_rule("^profile\/?", "index.php?pagename=mc_profile", 'top');
    add_rewrite_rule("^contact\/?", "index.php?pagename=mc_contact", 'top');
});

function mc_logout(): void
{
    wp_logout();
    wp_safe_redirect(home_url());
    wp_die();
}

function mc_widgets_init()
{

    add_theme_support('customize-selective-refresh-widgets');

    // Add support for Block Styles.
    add_theme_support('wp-block-styles');

    register_widget("MCPermissionNavbar");
    register_widget("MCPostExplorer");
}

function mc_wp_head()
{
    $nonce = wp_create_nonce('wp_rest');
    ?>
    <script>
        window.MCApp = window.MCApp || {
            nonce: "<?= $nonce ?>",
            baseUrl: "<?= get_rest_url() ?>",
        };
    </script>
    <?php
}