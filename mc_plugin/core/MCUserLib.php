<?php
include_once "constants.php";

class MCUserLib
{
    private WP_User $user;

    public function init()
    {
        add_action("show_user_profile", [$this, "addExtraFields"]);
        add_action("edit_user_profile", [$this, "addExtraFields"]);
        add_action("user_register", [$this, "mc_user_register_interceptor"]);
        add_action('admin_footer', [$this, "render_user_permissions"]);
        add_action("edit_user_profile_update", [$this, "mc_user_profile_updated"]);
        add_action("profile_update", [$this, "mc_user_profile_updated"]);
    }

    function render_user_permissions()
    {
        if (!isset($this->user)) return;

        $permissionSelection = get_user_meta($this->user->ID, MC_METABOX_PERMISSION, true);
        $settings = get_option(MC_SETTING);
        if (!$settings) {
            $settings = ["permissions" => []];
        }
        if (empty($permissionSelection)) {
            $tmp = ($settings[MC_DEFAULTS] ?? [])[MC_USER] ?? [];
            $permissionSelection = [];
            foreach ($tmp as $item) {
                $permissionSelection[$item['id']] = $item;
            }
        }

        $config = [
            'permissions' => $settings['permissions'],
            'selections' => $permissionSelection,
            'i18n' => [
                'Post types' => __('Post types'),
                'Capabilities' => __('Capabilities'),
            ]
        ];
        ?>
        <script type="module">
            <?= 'import {renderUserPermissions} from "' . plugins_url() . '/mc_plugin/assets/mc_svelte_lib.es.js"' ?>

            renderUserPermissions("mc_plugin_user_permission", <?= wp_json_encode($config) ?>);

        </script>
        <?php
    }

    function addExtraFields(WP_User $user)
    {
        $this->user = $user;
        ?>
        <table class="form-table">
            <tr>
                <th>
                    <label for="birthday"><?= __('Birthday') ?></label>
                </th>
                <td>
                    <input type="date"
                           class="regular-text ltr"
                           id="birthday"
                           name="birthday"
                           value="<?= esc_attr(get_user_meta($user->ID, 'birthday', true)) ?>"
                           title="Please use YYYY-MM-DD as the date format."
                           pattern="(19[0-9][0-9]|20[0-9][0-9])-(1[0-2]|0[1-9])-(3[01]|[21][0-9]|0[1-9])"
                           required>
                </td>
            </tr>
            <tr>
                <th><?= __('Enabled') ?></th>
                <td>
                    <label>
                        <input
                                title="Enables user to login on website"
                                type="checkbox"
                                name="<?= MC_ENABLED ?>" <?= get_user_meta($user->ID, MC_ENABLED, true) === 'on' ? 'checked' : '' ?>>
                    </label>
                </td>
            </tr>
            <tr>
                <th><?= __('Permissions') ?></th>
                <td class="mc_plugin_user_permission">
                </td>
            </tr>
        </table>
        <?php
    }

    function mc_user_register_interceptor(int $user_id)
    {

        $maybe_user = get_userdata($user_id);
        if ($maybe_user) {

            $post_linked_to_user = wp_insert_post(
                [
                    "post_author" => $maybe_user->ID,
                    "post_name" => "person: " . $maybe_user->display_name,
                    "post_title" => $maybe_user->display_name . " " . $maybe_user->user_email,
                    "post_status" => "publish",
                    "post_type" => CFT_DIRECTORY,
                    "meta_input" => [
                        MC_KIND => "person",
                        MC_ENABLED => 'off',
                        MC_USER_REF => $user_id,
                    ]
                ]
            );

            $settings = get_option(MC_SETTING);
            if ($settings) {
                if (array_key_exists(MC_DEFAULTS, $settings) && array_key_exists(MC_USER, $settings[MC_DEFAULTS])) {
                    $user_defaults = $settings[MC_DEFAULTS][MC_USER];
                    $items = [];
                    foreach ($user_defaults as $item) {
                        $items[$item[MC_NAME]] = $item[MC_CAPABILITIES];
                    }
                    $this->update_metadata($items, $user_id);
                }
            }

            if (is_wp_error($post_linked_to_user)) {
                error_log($post_linked_to_user->get_error_message());
            }
        } else {
            error_log("Can't found user " . $user_id);
        }
    }

    function mc_user_profile_updated($user_id)
    {
        update_user_meta($user_id, MC_ENABLED, $_POST[MC_ENABLED] ?? 'off');

        if (array_key_exists(MC_METABOX_PERMISSION, $_POST)) {
            $permissions = $_POST[MC_METABOX_PERMISSION];

            $this->update_metadata($permissions, $user_id);
        }
    }

    function update_metadata(array $permissions, $user_id)
    {
        delete_user_meta($user_id, MC_METABOX_PERMISSION_RULE);

        foreach ($permissions as $k => $v) {
            $post_types = $v[MC_POST_TYPES] ?? [];

            if (count($post_types) === 0) continue;

            foreach ($v[MC_CAPABILITIES] ?? [] as $datum) {
                $result = $k . "::" . $datum;
                add_user_meta($user_id, MC_METABOX_PERMISSION_RULE, $result);
            }
        }
        update_user_meta($user_id, MC_METABOX_PERMISSION, $permissions);
    }

}