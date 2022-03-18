<?php

class MCMetaPost
{

    private WP_Post $post;

    public function __constructor()
    {
    }

    public function init()
    {

//        add_action('add_meta_boxes', [$this, 'add']);
//        add_action('save_post', [$this, 'save']);
//        add_action("admin_footer", [$this, "prepareScript"]);
    }

    /**
     * Set up and add the meta box.
     */
    public function add()
    {
        add_meta_box(
            'mc_metabox_id', // Unique ID
            'MC Post settings',// Box title
            [$this, 'html']
        );


    }


    /**
     * Save the meta box selections.
     *
     * @param int $post_id The post ID.
     */
    public function save(int $post_id)
    {
        if (array_key_exists(MC_ABSTRACT, $_POST)) {
            update_post_meta($post_id, MC_ABSTRACT, $_POST[MC_ABSTRACT]);
        }

        if (array_key_exists(MC_METABOX_IMAGE, $_FILES) && $_FILES[MC_METABOX_IMAGE]['size'] > 0) {
            $attachmentID = media_handle_upload(MC_METABOX_IMAGE, $post_id);

            $last_attachment = get_post_meta($post_id, MC_METABOX_IMAGE);
            if (!empty($last_attachment)) {
                wp_delete_attachment($last_attachment);
            }

            update_post_meta($post_id, MC_METABOX_IMAGE, $attachmentID);
        }

        delete_post_meta($post_id, MC_METABOX_PERMISSION);
        delete_post_meta($post_id, MC_METABOX_PERMISSION_RULE);

        if (array_key_exists(MC_METABOX_PERMISSION, $_POST)) {
            // adjust format
            $perm = [];
            foreach ($_POST[MC_METABOX_PERMISSION] ?? [] as $k=>$v){
                $perm[] = [MC_ID => $k, MC_CAPABILITIES => $v[MC_CAPABILITIES] ?? []];
            }
            MCPermissionLib::update_post_permissions($post_id, $perm);
        }

        if (array_key_exists(MC_COUNTRY, $_POST)) {
            $countries = $_POST[MC_COUNTRY] ?? [];
            foreach ($countries as $item) {
                update_post_meta($post_id, MC_COUNTRY, $item);
            }
        }
    }

    /**
     * Display the meta box HTML to the user.
     *
     * @param \WP_Post $post Post object.
     */
    public function html($post)
    {
        $this->post = $post;
        ?>
        <div class="mc_meta_post_place"></div>
        <?php
    }

    public function prepareScript()
    {

        if (!isset($this->post)) return;

        $post = $this->post;
        $abstractValue = get_post_meta($post->ID, MC_ABSTRACT, true);
        $permissionSelection = get_post_meta($post->ID, MC_METABOX_PERMISSION, true);
        $countries = get_post_meta($post->ID, MC_COUNTRY, false);
        $settings = get_option(MC_SETTING);

        if (!$settings) {
            $settings = ["permissions" => []];
        }

        if ($permissionSelection === "") {
            $permissionSelection = [];
        }

        $post_type = $post->post_type;
        $permissions = array_values(
            array_filter($settings['permissions'], fn($x) => array_exists($x[MC_POST_TYPES], fn($y) => $post_type === $y))
        );

        $config = [
            'permissions' => $permissions,
            'selections' => $permissionSelection,
            MC_ABSTRACT => $abstractValue,
            MC_COUNTRY => $countries,
            'base_countries' => $settings[MC_COUNTRY] ?? []
        ];
        ?>
        <script id="jvc" type="module">
            <?= 'import {renderMetaPost} from "' . plugins_url() . '/mc_plugin/assets/mc_svelte_lib.es.js"' ?>

            renderMetaPost("mc_meta_post_place", <?= wp_json_encode($config) ?>);
        </script>
        <?php
    }
}
