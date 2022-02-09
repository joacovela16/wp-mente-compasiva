<?php

class MCActions
{
    public function init()
    {
        add_action('admin_post_update_user', [$this, 'update_user_action']);
    }

    public function update_user_action()
    {

        $user = wp_get_current_user();
        $ID = $user->ID;
        $avatarURL = null;
        $attachmentID = null;
        $user_data = ["ID" => $ID];

        if (isset($_FILES["mc_picture"])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            $lastAttachment = get_user_meta($ID, "user_avatar_id", true);

            if (!is_wp_error($lastAttachment)) {


                $attachmentID = media_handle_upload("mc_picture", null);
            }

            if (!is_null($attachmentID) && !is_wp_error($attachmentID)) {

                $id = intval($lastAttachment);
                $wp_delete_attachment_result = wp_delete_attachment($id);

                if (!is_wp_error($wp_delete_attachment_result)) {
                    $avatarURL = wp_get_attachment_url($attachmentID);
                    update_user_meta($ID, "user_avatar_url", $avatarURL);
                    update_user_meta($ID, "user_avatar_id", $attachmentID);
                }
            }
        }

        if ($ID !== 0) {
            $post_id = get_user_meta($ID, MC_USER_REF, true);

            $meta = [
                MC_METABOX_ABSTRACT => $_POST["mc_about"]
            ];
            if (!$avatarURL == null) {
                $meta["user_avatar_url"] = $avatarURL;
            }
            $update_result = wp_update_post([
                "ID" => intval($post_id),
                "post_content" => $_POST["mc_about"] ?? '',
                "meta_input" => $meta
            ]);

            $user_data = array_merge($user_data, [
                "display_name" => $_POST["mc_name"],
                "description" => $_POST["mc_about"] ?? '',
                "user_url" => $_POST["mc_website"] ?? '',
            ]);

            if (isset($_POST["mc_birthday"])) {
                update_user_meta($ID, 'birthday', $_POST["mc_birthday"]);
            }

            $new_password = $_POST["mc_password_1"];
            $confirm_password = $_POST["mc_password_2"];

            if ($new_password == $confirm_password) {
                if (!empty($new_password) && !empty($confirm_password)) {
                    $user_data = array_merge($user_data, ["user_pass" => $new_password]);
                }
            }
            wp_update_user($user_data);
        }
        wp_safe_redirect('/profile');
    }

}