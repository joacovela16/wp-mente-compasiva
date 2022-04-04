<?php
include_once "constants.php";

class MCUserLib
{

    public function init()
    {
        add_action("show_user_profile", [$this, "addExtraFields"]);
        add_action("edit_user_profile", [$this, "addExtraFields"]);
        add_action("user_register", [$this, "mc_user_register_interceptor"]);
        add_action("edit_user_profile_update", [$this, "mc_user_profile_updated"]);
        add_action('delete_user', [$this, 'delete_user']);
    }

    public function delete_user($user_id)
    {
        $bind = get_user_meta($user_id, MC_POST_BIND, true);

        if (empty($bind)) return;

        wp_delete_post(intval($bind), true);
    }

    function addExtraFields(WP_User $user)
    {
        $is_cft = get_user_meta($user->ID, MC_CFT, true) === "on";
        $work_with = get_user_meta($user->ID, MC_WORKS_WITH);
        $profession = get_user_meta($user->ID, MC_PROFESSION, true);
        $mode = get_user_meta($user->ID, MC_MODE, true);
        $url_mode = get_user_meta($user->ID, MC_WEBSITE_MODE, true);
        $professions = get_option(MC_PROFESSION_OPTIONS, []);
        $is_enable = get_user_meta($user->ID, MC_ENABLED, true) === 'on';
        ?>
        <table class="form-table">
            <tr>
                <th><?= __('User enabled') ?></th>
                <td>
                    <label>
                        <input
                                type="checkbox"
                                name="<?= MC_ENABLED ?>" <?= $is_enable ? 'checked' : '' ?>>
                    </label>
                </td>
            </tr>

            <tr>
                <th><?= __('CFT Coursed') ?></th>
                <td>
                    <label>
                        <input
                                type="checkbox"
                                name="<?= MC_CFT ?>" <?= $is_cft ? 'checked' : '' ?>>
                    </label>
                </td>
            </tr>

            <tr>
                <th>
                    <?= __('About me') ?>
                </th>
                <td>
                    <textarea name="<?= MC_ABSTRACT ?>" cols="100" rows="15"><?= get_user_meta($user->ID, MC_ABSTRACT, true) ?></textarea>
                </td>
            </tr>

            <tr>
                <th>
                    <?= __('Profession') ?>
                </th>
                <td>
                    <select name="<?= MC_PROFESSION ?>">
                        <option value="" <?= $profession === "" ? 'selected' : '' ?> disabled><?= __('select') ?></option>
                        <?php foreach ($professions as $item): ?>
                            <option value="<?= $item ?>" <?= $item === $profession ? 'selected' : '' ?>><?= $item ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <?= __('Works with') ?>
                </th>
                <td>
                    <select class="w-full field-select" name="<?= MC_WORKS_WITH ?>[]" multiple>
                        <option value="children" <?= in_array("children", $work_with) ? 'selected' : '' ?>><?= __('Children') ?></option>
                        <option value="teenager" <?= in_array("teenager", $work_with) ? 'selected' : '' ?>><?= __('Teenager') ?></option>
                        <option value="adult" <?= in_array("adult", $work_with) ? 'selected' : '' ?>><?= __('Adult') ?></option>
                        <option value="couple" <?= in_array("couple", $work_with) ? 'selected' : '' ?>><?= __('Couple') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <?= __('Work mode') ?>
                </th>
                <td>
                    <select class="select select-bordered w-full" name="<?= MC_MODE ?>">
                        <option value="" <?= $mode === '' ? 'selected' : '' ?> disabled> <?= __('select') ?></option>
                        <option <?= $mode === 'onsite' ? 'selected' : '' ?> value="onsite"> <?= __('onsite') ?></option>
                        <option <?= $mode === 'online' ? 'selected' : '' ?> value="online"> <?= __('online') ?></option>
                        <option <?= $mode === 'online-onsite' ? 'selected' : '' ?> value="online-onsite"> <?= __('online-onsite') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?= __('cft_when_and_where') ?></th>
                <td>
                    <label>
                        <input
                                type="text"
                                name="<?= MC_CFT_WHEN_WHERE ?>" value="<?= get_user_meta($user->ID, MC_CFT_WHEN_WHERE, true) ?>">
                    </label>
                </td>
            </tr>
            <tr>
                <th>
                    <?= __('Birthday') ?>
                </th>
                <td>
                    <input type="date"
                           class="regular-text ltr"
                           id="<?= MC_BIRTHDAY ?>"
                           name="<?= MC_BIRTHDAY ?>"
                           value="<?= esc_attr(get_user_meta($user->ID, MC_BIRTHDAY, true)) ?>"
                           title="Please use YYYY-MM-DD as the date format."
                           pattern="(19[0-9][0-9]|20[0-9][0-9])-(1[0-2]|0[1-9])-(3[01]|[21][0-9]|0[1-9])"
                           required>
                </td>
            </tr>

            <tr>
                <th>
                    <?= __('Country') ?>
                </th>
                <td>
                    <input type="text"
                           class="regular-text ltr"
                           id="<?= MC_COUNTRY ?>"
                           name="<?= MC_COUNTRY ?>"
                           value="<?= esc_attr(get_user_meta($user->ID, MC_COUNTRY, true)) ?>"
                           required>
                </td>
            </tr>

            <tr>
                <th>
                    <?= __('City') ?>
                </th>
                <td>
                    <input type="text"
                           class="regular-text ltr"
                           id="<?= MC_CITY ?>"
                           name="<?= MC_CITY ?>"
                           value="<?= esc_attr(get_user_meta($user->ID, MC_CITY, true)) ?>"
                           required>
                </td>
            </tr>

            <tr>
                <th>
                    <?= __('Website') ?>
                </th>
                <td class="">
                    <select class="" name="<?= MC_WEBSITE_MODE ?>">
                        <option disabled <?= $url_mode === "" ? 'selected' : '' ?> ><?= __('Select') ?></option>
                        <option value="<?= MC_LINK_WEBSITE ?>" <?= $url_mode === MC_LINK_WEBSITE ? 'selected' : '' ?> >Sitio web</option>
                        <option value="<?= MC_LINK_INSTAGRAM ?>" <?= $url_mode === MC_LINK_INSTAGRAM ? 'selected' : '' ?>>Instagram</option>
                        <option value="<?= MC_LINK_FACEBOOK?>" <?= $url_mode === MC_LINK_FACEBOOK ? 'selected' : '' ?>>Facebook</option>
                        <option value="<?= MC_LINK_LINKEDIN ?>" <?= $url_mode === MC_LINK_LINKEDIN ? 'selected' : '' ?>>Linkedin</option>
                        <option value="<?= MC_LINK_TWITTER ?>" <?= $url_mode === MC_LINK_TWITTER ? 'selected' : '' ?>>Twitter</option>
                    </select>
                    <input type="text"
                           class=" "
                           id="<?= MC_WEBSITE ?>"
                           name="<?= MC_WEBSITE ?>"
                           value="<?= esc_attr(get_user_meta($user->ID, MC_WEBSITE, true)) ?>"
                           required>
                </td>
            </tr>

            <tr>
                <th>
                    <?= __('Phone') ?>
                </th>
                <td>
                    <input type="text"
                           class="regular-text ltr"
                           id="<?= MC_PHONE ?>"
                           name="<?= MC_PHONE ?>"
                           value="<?= esc_attr(get_user_meta($user->ID, MC_PHONE, true)) ?>"
                           required>
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
                    "post_name" => $maybe_user->display_name,
                    "post_title" => $maybe_user->display_name,
                    "post_status" => "publish",
                    "post_type" => CFT_DIRECTORY,
                    "meta_input" => [
                        MC_KIND => "person",
                        MC_ENABLED => 'on',
                        MC_USER_REF => $user_id,
                    ]
                ]
            );

            if (is_wp_error($post_linked_to_user)) {
                error_log($post_linked_to_user->get_error_message());
            } else {
                update_user_meta($user_id, MC_POST_BIND, $post_linked_to_user);
                update_user_meta($user_id, MC_ENABLED, 'on');
            }
        } else {
            error_log("Can't found user " . $user_id);
        }
    }

    function mc_user_profile_updated($user_id)
    {

        //update_user_meta($user_id, MC_ENABLED, $_POST[MC_ENABLED] ?? 'off');
        $user = get_userdata($user_id);
        if ($user) {
            MCUserLib::update_user_data($user);
        }
    }

    public static function update_user_data(WP_User $user, bool $update = true)
    {
        $ID = $user->ID;
        $avatarURL = null;
        $attachmentID = null;
        $user_data = ["ID" => $ID];

        if ($ID !== 0) {

            $meta_input = [];

            if (isset($_FILES[MC_PICTURE]) && !empty($_FILES[MC_PICTURE])) {
                if ($_FILES[MC_PICTURE]['size'] < 5242880) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/media.php');
                    $lastAttachment = get_user_meta($ID, MC_AVATAR_ID, true);

                    if (!is_wp_error($lastAttachment)) {
                        $attachmentID = media_handle_upload(MC_PICTURE, null);
                    }

                    if (!is_null($attachmentID) && !is_wp_error($attachmentID)) {

                        $id = intval($lastAttachment);
                        $wp_delete_attachment_result = wp_delete_attachment($id);

                        if (!is_wp_error($wp_delete_attachment_result)) {
                            $avatarURL = wp_get_attachment_url($attachmentID);

                            if (nonEmpty($avatarURL)) $meta_input[MC_AVATAR_URL] = $avatarURL;
                            if (nonEmpty($attachmentID)) $meta_input[MC_AVATAR_ID] = $attachmentID;

                            update_user_meta($ID, MC_AVATAR_URL, $avatarURL);
                            update_user_meta($ID, MC_AVATAR_ID, $attachmentID);
                        }
                    }
                } else {
                    $a = 1;
                }
            }
            $post_id = get_user_meta($ID, MC_POST_BIND, true);
            $post_id = intval($post_id);

            $post_data = ["ID" => $post_id, "meta_input" => &$meta_input];

            if (!isset($_POST[MC_ENABLED]) && !isset($_POST[MC_POLICY_1])) {
                $_POST[MC_ENABLED] = 'off';
            }

            if (!isset($_POST[MC_ENABLED]) && ($_POST[MC_POLICY_1] ?? '') === 'on') {
                $_POST[MC_ENABLED] = 'on';
            }

            if (!isset($_POST[MC_POLICY_1])) $_POST[MC_POLICY_1] = "off";

            $meta_fields = [
                MC_COUNTRY,
                MC_ENABLED,
                MC_CITY,
                MC_BIRTHDAY,
                MC_MODE,
                MC_PHONE,
                MC_PROFESSION,
                MC_CFT,
                MC_GENDER,
                MC_CFT_WHEN_WHERE,
                MC_NAME,
                MC_ABSTRACT,
                MC_WEBSITE_MODE,
                MC_WEBSITE,
                MC_POLICY_1,
                MC_POLICY_2,
                MC_POLICY_3,
            ];

            foreach ($meta_fields as $field) {
                if (isset($_POST[$field]) && nonEmpty($_POST[$field])) {
                    $value = $_POST[$field];
                    $meta_input[$field] = $value;
                    update_user_meta($ID, $field, $value);
                }
            }

            if (isset($_POST[MC_ABSTRACT]) && nonEmpty($_POST[MC_ABSTRACT])) {
                $post_data['post_content'] = $_POST[MC_ABSTRACT];
            }

            if (isset($_POST[MC_WORKS_WITH]) && nonEmpty($_POST[MC_WORKS_WITH])) {
                $ww = $_POST[MC_WORKS_WITH] ?? [];
                $ww = is_array($ww) ? $ww : [];

                delete_user_meta($ID, MC_WORKS_WITH);
                delete_post_meta($post_id, MC_WORKS_WITH);

                foreach ($ww as $item) {
                    add_user_meta($ID, MC_WORKS_WITH, $item);
                    add_post_meta($post_id, MC_WORKS_WITH, $item);
                }
            }

            if (isset($_POST[MC_WEBSITE]) && nonEmpty($_POST[MC_WEBSITE])) {
                $user_data['user_url'] = $_POST[MC_WEBSITE];
            }

            if (isset($_POST[MC_PASSWORD_1]) && isset($_POST[MC_PASSWORD_2])) {

                $new_password = $_POST[MC_PASSWORD_1];
                $confirm_password = $_POST[MC_PASSWORD_2];

                if (nonEmpty($new_password) && $new_password == $confirm_password) {
                    if (!empty($new_password) && !empty($confirm_password)) {
                        $user_data = array_merge($user_data, ["user_pass" => $new_password]);
                    }
                }
            }

            wp_update_post($post_data);
            if ($update) {
                wp_update_user($user_data);
            }
        }
    }

}