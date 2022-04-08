<?php

class MCAssetLib
{
    public function init()
    {

//        add_action('wp_enqueue_scripts', [$this, "mc_plugin_install_assets"]);
//        add_action('admin_enqueue_scripts', [$this, "mc_plugin_install_assets"]);
//        add_action('admin_footer', [$this, "mc_plugin_install_app"]);
        add_action("widgets_init", [$this, "register_widgets"]);
    }

    public function register_widgets()
    {

        register_sidebar([
            'name' => __('Main content', 'mc_theme'),
            'id' => 'sidebar-2',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);

        register_widget("MCDirectoryExplorer");
    }
}
