<?php

class MCActions
{

    public function init()
    {

        add_action('admin_post_update_user', [$this, 'update_user_action']);
        add_action('wp_ajax_nopriv_upload_csv', [$this, 'csv_handler']);
        add_action('template_redirect', [$this, 'secure_profile']);
        add_filter('authenticate', [$this, 'authenticate'], 20, 3);
        add_filter('login_redirect', [$this, 'login_redirect'], 10, 3);
        add_filter('init', [$this, 'block_wp_admin_init']);
    }

    public function csv_handler()
    {
        $settings = get_option(MC_SETTING) ?? [];
        $default_permissions = ($settings[MC_DEFAULTS] ?? [])[MC_USER] ?? [];
        $datum = [];
        if (($stream = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {
            $data = fgetcsv($stream, 1000, ",");
            if ($data) {
                $n = count($data);
                $header = $data;


                while (($data = fgetcsv($stream, 1000, ",")) !== false) {
                    $row = [];
                    for ($c = 0; $c < $n; $c++) {
                        $row[$header[$c]] = $data[$c];
                    }
                    $datum[] = $row;
                }


            }
            fclose($stream);
        }

        foreach ($datum as $item) {
            $name = $item['name'];
            $country = $item['country'];
            $location = $item['location'];
            $description = $item['description'];
            $email = $item['email'];
            $phone = $item['phone'];
            $website = $item['website'];
            $password = 'QgHTPqkTzg4K6u';
            $user = wp_create_user($name, $password, $email);

            if (is_wp_error($user)) {
                error_log(`ERROR: Usuario ${$name}/${email} no pudo ser creado`);
                $user = get_user_by('email', $email);
            }

            if ($user) {

                $post_id = get_user_meta($user, MC_POST_BIND, true);
                if (!empty($post_id)) {
                    $post = get_post(intval($post_id));
                    wp_update_post([
                        'ID' => $post->ID,
                        'meta_input' => [
                            'country' => $country,
                            'location' => $location,
                            'email' => $email,
                            'phone' => $phone,
                            'website' => $website,
                            'description' => $description,

                        ]
                    ]);

                    MCPermissionLib::update_post_permissions($post->ID, $default_permissions);
                }
            }
        }
    }

    private function isAdm($user = null): bool
    {
        if (is_wp_error($user)) return false;

        $usr = empty($user) ? wp_get_current_user() : $user;
        return user_can($usr, 'administrator');
    }

    function block_wp_admin_init()
    {
        if (strpos(strtolower($_SERVER['REQUEST_URI']), '/wp-admin/') !== false) {

            if (!$this->isAdm()) {
                wp_redirect(site_url(), 302);
            }
        }
    }


    public function login_redirect($redirect_to, $requested_redirect_to, $user)
    {
        return $this->isAdm($user) ? '/wp-admin' : '/';
    }

    public function authenticate($user)
    {

        if (empty($user) || is_wp_error($user)) return $user;

        if (user_can($user, 'administrator')) return $user;

        $is_enabled = get_user_meta($user->ID, MC_ENABLED, true);

        if ($is_enabled === 'on') {
            return $user;
        }
        return new WP_Error(404, __('User disabled. Contact administrator.'));
    }

    public function secure_profile()
    {
        if (is_page('mc_profile') && !is_user_logged_in()) {
            wp_redirect(site_url(), 301);
            exit;
        }
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


            if (isset($_POST["mc_country"])) {
                $mc_country = $_POST["mc_country"];
                update_user_meta($ID, 'country', $mc_country);
                $meta['country'] = $mc_country;
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