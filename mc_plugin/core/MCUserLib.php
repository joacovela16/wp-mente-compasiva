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
    }

    function render_user_permissions()
    {
        if (!isset($this->user)) return;

        $permissionSelection = get_user_meta($this->user->ID, MC_METABOX_PERMISSION, true);
        $settings = get_option(MC_SETTING);
        if (!$settings) {
            $settings = ["permissions" => []];
        }

        $config = [
            'permissions' => $settings['permissions'],
            'selections' => $permissionSelection === "" ? null : $permissionSelection,
            'i18n' => []
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
        $permissionSelection = get_user_meta($user->ID, MC_METABOX_PERMISSION, true);
        $settings = get_option(MC_SETTING);

        if (!$settings) {
            $settings = ["permissions" => []];
        }

        $settings['selection'] = $permissionSelection === "" ? [] : $permissionSelection;
        $settingsEsc = htmlspecialchars(wp_json_encode($settings));

        ?>
        <h3>It's Your Birthday</h3>
        <table class="form-table">
            <tr>
                <th>
                    <label for="birthday">Birthday</label>
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
                    <p class="description">
                        Please enter your birthday date.
                    </p>
                </td>
            </tr>
            <tr x-data="<?= $settingsEsc ?>">
                <th><?= __('Permissions') ?></th>
                <td x-data="{currentTab: undefined}" x-init="currentTab=permissions[0]" class="mc_plugin_user_permission">
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
                    "post_type" => PROFESSIONAL_CATALOG,
                    "meta_input" => [
                        "kind" => "person",
                        MC_USER_REF => $user_id,
                    ]
                ]
            );

            $settings = get_option(MC_SETTING);
            if ($settings) {
                if (array_key_exists(MC_DEFAULTS, $settings) && array_key_exists(MC_USER, $settings[MC_DEFAULTS])) {
                    $user_defaults = $settings[MC_DEFAULTS][MC_USER];
                    $items = [];
                    foreach ($user_defaults as $item){
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
        if (array_key_exists(MC_METABOX_PERMISSION, $_POST)) {
            $permissions = $_POST[MC_METABOX_PERMISSION];

            $this->update_metadata($permissions, $user_id);
        }
    }

    function update_metadata(array $permissions, $user_id)
    {
        $result = [];
        foreach ($permissions as $k => $v) {
            foreach ($v[MC_CAPABILITIES] ?? [] as $datum) {
                $result[] = $k . "::" . $datum;
            }
        }
        update_user_meta($user_id, MC_METABOX_PERMISSION, $permissions);
        update_user_meta($user_id, MC_METABOX_PERMISSION_RULE, $result);
    }

}