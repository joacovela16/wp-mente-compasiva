<?php

add_action('login_enqueue_scripts', "login_scripts");
add_action('wp_enqueue_scripts', "mc_install_styles");
add_action('admin_enqueue_scripts', "mc_install_styles");
add_action('admin_enqueue_scripts', "mc_install_styles");

add_action('wp_enqueue_scripts', "mc_install_scripts");
add_action('admin_enqueue_scripts', "mc_install_scripts");
add_action('admin_enqueue_scripts', "mc_install_scripts");

function login_scripts()
{
    if (!is_admin()) {
        mc_install_styles();
    }
}

function mc_install_styles()
{

    wp_enqueue_style('mc_windicss', get_template_directory_uri() . "/assets/styles/theme.css");
    wp_enqueue_style('mc_theme', get_template_directory_uri() . "/assets/styles/base-theme.css");
}

function mc_install_scripts()
{
    wp_enqueue_script("mc_alpinejs", "https://unpkg.com/alpinejs@3.7.1/dist/cdn.min.js", [], false, true);
    wp_enqueue_script('mc_app', get_template_directory_uri() . "/assets/javascript/app.js");
}
