<?php
include_once "template-parts/utils.php";
include_once "template-parts/widgets/MCPostExplorer.php";
include_once "constants.php";
include_once "template-parts/widgets/MC_Meta_Box.php";
include_once "template-parts/user_lib.php";
include_once "template-parts/dom.php";

add_action("wp_head", "mc_wp_head");
add_action('wp_enqueue_scripts', "mc_install_assets");
add_action('admin_enqueue_scripts', "mc_install_assets");
add_action('widgets_init', 'mc_widgets_init');
add_filter('show_admin_bar', '__return_false');
add_action('login_form_logout', "mc_logout");


add_action("init", function () {
    add_rewrite_rule("^about\/?", "index.php?pagename=mc_about", 'top');
    add_rewrite_rule("^profile\/?", "index.php?pagename=mc_profile", 'top');
    add_rewrite_rule("^contact\/?", "index.php?pagename=mc_contact", 'top');
});

add_filter('template_include', 'mc_template_chooser');

function mc_template_chooser($template)
{
    $post_type = get_query_var('post_type');
    if (is_search() && $post_type === DIRECTORY_CATALOG) {
        $locate_template = locate_template("${post_type}_search.php");
        return empty($locate_template) ? $template : $locate_template;  //  redirect to archive-search.php
    }
    return $template;
}

function mc_logout(): void
{
    wp_logout();
    wp_safe_redirect(home_url());
    wp_die();
}


function mc_build_user_info()
{
    if (is_user_logged_in()) {

        $currentUser = wp_get_current_user();
        $user_avatar_url = get_user_meta($currentUser->ID, "user_avatar_url", true);

        if (empty($user_avatar_url)) {
            $user_avatar_url = get_avatar_url($currentUser->ID);
        }

        $usrData = $currentUser->data;
        return [
            "ID" => $currentUser->ID,
            "user_avatar_url" => $user_avatar_url,
            "display_name" => $usrData->display_name,
            "user_email" => empty($usrData->user_email) ? $usrData->user_login : $usrData->user_email,
            "user_url" => get_user_meta($currentUser->ID, "description", true)
        ];
    } else {
        return null;
    }

}

function svelte_installer()
{

    $uri = get_template_directory_uri() . "/components/dist/mc-lib.es.js";
    $import = "import {renderApp} from '$uri';";

    $nonce = is_user_logged_in() ? wp_create_nonce('wp_rest') : null;
    $filter = wp_json_encode(buildFilter());
    $profile = wp_json_encode(mc_build_user_info());

    ?>
    <script type='module'>
        <?=  $import  ?>
        renderApp(
            'mc-app',
            {
                mainContent: `<?php dynamic_sidebar('sidebar-1') ?>`,
                adminUrl: '<?= admin_url('admin-ajax.php') ?>',
                homeUrl: '<?= home_url() ?>',
                logoutUrl: `<?= wp_logout_url(home_url()) ?>`,
                loginUrl: `<?= wp_login_url(home_url()) ?>`,
                profile: <?= $profile ?>,
                nonce: '<?= $nonce ?>',
                filter: <?= $filter ?>,
            }
        );
    </script>
    <?php

}

function mc_widgets_init()
{
    register_widget("MCPostExplorer");
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for Block Styles.
    add_theme_support('wp-block-styles');

    register_sidebar(array(
        'name' => __('Main content', 'mc_theme'),
        'id' => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

function mc_install_assets()
{
    wp_enqueue_style('mc_windicss', get_template_directory_uri() . "/assets/styles/theme.css");
    wp_enqueue_style('mc_theme', get_template_directory_uri() . "/assets/styles/windi.css");
    wp_enqueue_script("mc_alpinejs", "https://unpkg.com/alpinejs@3.7.0/dist/cdn.min.js", [], false, true);
    wp_enqueue_script('mc_app', get_template_directory_uri() . "/assets/javascript/app.js");
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
