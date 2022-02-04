<?php

add_action('login_enqueue_scripts', "mc_install_styles");
add_action('wp_enqueue_scripts', "mc_install_styles");
add_action('admin_enqueue_scripts', "mc_install_styles");
add_action('admin_enqueue_scripts', "mc_install_styles");

add_action('wp_enqueue_scripts', "mc_install_scripts");
add_action('admin_enqueue_scripts', "mc_install_scripts");
add_action('admin_enqueue_scripts', "mc_install_scripts");


function mc_install_styles()
{
    wp_enqueue_style('mc_windicss', get_template_directory_uri() . "/assets/styles/theme.css");
    wp_enqueue_style('mc_theme', get_template_directory_uri() . "/assets/styles/windi.css");
}

function mc_install_scripts()
{
    wp_enqueue_script("mc_alpinejs", "https://unpkg.com/alpinejs@3.7.1/dist/cdn.min.js", [], false, true);
    wp_enqueue_script('mc_app', get_template_directory_uri() . "/assets/javascript/app.js");
}
