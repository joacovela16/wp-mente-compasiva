<?php

class MCAssetLib
{
    public function init()
    {

        add_action('wp_enqueue_scripts', [$this, "mc_plugin_install_assets"]);
        add_action('admin_enqueue_scripts', [$this, "mc_plugin_install_assets"]);
        add_action('admin_footer', [$this, "mc_plugin_install_app"]);
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
            "permissions" => $permissions['permissions'],
            "i18n" => [
                "Permissions" => __("Permissions"),
                "Name" => __("Name"),
                "Signed required" => __("Signed required"),
                "Post type" => __("Post type"),
                "Capabilities" => __("Capabilities"),
                "Delete" => __("Delete"),
                "Save settings" => __("Save settings"),
                "Add new" => __("Add new"),
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
