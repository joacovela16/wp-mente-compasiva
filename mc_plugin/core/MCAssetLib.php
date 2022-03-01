<?php

class MCAssetLib
{
    public function init()
    {

        add_action('wp_enqueue_scripts', [$this, "mc_plugin_install_assets"]);
        add_action('admin_enqueue_scripts', [$this, "mc_plugin_install_assets"]);
        add_action('admin_footer', [$this, "mc_plugin_install_app"]);
        add_action("widgets_init",[$this, "register_widgets"]);
    }

    public function register_widgets()
    {
        register_sidebar([
            'name' => __('Navbar Zone', 'mc_theme'),
            'id' => 'sidebar-1',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);

        register_sidebar([
            'name' => __('Main content', 'mc_theme'),
            'id' => 'sidebar-2',
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);

        register_widget("MCPermissionNavbar");
        register_widget("MCPostExplorer");
    }

    public function mc_plugin_install_assets()
    {
        wp_enqueue_style('mc_plugin_svelte_css', plugins_url() . "/mc_plugin/assets/style.css");
    }


    public function mc_plugin_install_app()
    {
        $post_types = array_values(get_post_types(["public" => true]));
        $permissions = get_option(MC_SETTING);
        $permissionConfig = [
            "postUrl" => menu_page_url('mc_panel', false),
            "defaultPermission" => MC_DEFAULT_PERMISSION,
            "post_types" => $post_types,
            "permissions" => $permissions['permissions'] ?? [],
            "defaults" => $permissions['defaults'] ?? null,
            "countries" => $permissions['countries'] ?? [],
            "i18n" => [
                "Permissions" => __("Permissions"),
                "Name" => __("Name"),
                "Signed required" => __("Signed required"),
                "Post types" => __("Post types"),
                "Posts" => __("Posts"),
                "Default user settings" => __("Default user settings"),
                "Capabilities" => __("Capabilities"),
                "Delete" => __("Delete"),
                "Save settings" => __("Save settings"),
                "Add permission" => __("Add permission"),
                "permission_desc" => __('permission_desc')
            ]
        ];


        ?>
        <script type="module">
            import {renderPermissionEditor} from "<?= plugins_url() . "/mc_plugin/assets/mc_svelte_lib.es.js" ?>";

            renderPermissionEditor("mc_plugin_setting_panel", <?= wp_json_encode($permissionConfig) ?>);
        </script>
        <?php
    }
}
