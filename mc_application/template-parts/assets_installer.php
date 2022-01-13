<?php


add_action('wp_enqueue_scripts', "mc_install_assets");
add_action('admin_enqueue_scripts', "mc_install_assets");
//if (is_admin() && !empty($_GET['page']) && $_GET['page'] === "mc_panel") {
//}

function mc_install_assets()
{

    wp_enqueue_style('mc_windicss', get_template_directory_uri() . "/assets/styles/theme.css");
    wp_enqueue_style('mc_theme', get_template_directory_uri() . "/assets/styles/windi.css");
    wp_enqueue_script("mc_alpinejs", "https://unpkg.com/alpinejs@3.7.1/dist/cdn.min.js", [], false, true);
    wp_enqueue_script('mc_app', get_template_directory_uri() . "/assets/javascript/app.js");
}
