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


        add_filter('registration_errors', function (WP_Error $errors, $sanitized_user_login, $user_email) {
            if (isset($_POST['token'])) {

                $token = $_POST['token'];
                $pending = get_option(MC_PASSWORD_GEN, []);
                $item = array_find($pending, fn($x) => $x['key'] === $token);

                if (!empty($item)) {
                    $k = $item['key'];
                    $t = $item['token'];
                    $keyService = new MCKeyService();
                    $result = $keyService->validate_recovery_mode_key($t, $k, MC_MAX_TIMEOUT);
                    if (!is_wp_error($result)) {

                        $pending = array_values(array_filter($pending, fn($x) => $x['token'] !== $t));
                        update_option(MC_PASSWORD_GEN, $pending);
                        $keyService->remove_key($t);
                    } else {
                        return $result;
                    }
                }
            } else {
                $errors->add('invalid-token', __('Invalid registration token'));
            }

            $_POST[MC_POLICY] = "on";

            return $errors;

        }, 10, 3);

        add_filter('post_row_actions', function ($actions) {
            if (get_post_type() === CFT_DIRECTORY) {
                unset($actions['edit']);
                unset($actions['view']);
                unset($actions['trash']);
                unset($actions['inline hide-if-no-js']);
            }
            return $actions;
        });

        add_action('register_form', function () {
            $token = explode('/', $_SERVER['REQUEST_URI']);
            ?>
            <div class="user-pass1-wrap">
                <p>
                    <label for="pass1"><?php _e('Password'); ?></label>
                </p>

                <div class="wp-pwd">
                    <input type="password" data-reveal="1" name="pass1" id="pass1" class="input password-input" size="24" value=""
                           autocomplete="off" aria-describedby="pass-strength-result"/>

                    <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e('Hide password'); ?>">
                        <span class="dashicons dashicons-hidden" aria-hidden="true"></span>
                    </button>
                    <div id="pass-strength-result" class="hide-if-no-js" aria-live="polite"><?php _e('Strength indicator'); ?></div>
                </div>
                <div class="pw-weak">
                    <input type="checkbox" name="pw_weak" id="pw-weak" class="pw-checkbox"/>
                    <label for="pw-weak"><?php _e('Confirm use of weak password'); ?></label>
                </div>
            </div>

            <p class="user-pass2-wrap">
                <label for="pass2"><?php _e('Confirm new password'); ?></label>
                <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off"/>
                <input type="hidden" name="token" value="<?= is_array($token) && isset($token[2]) ? $token[2] : '' ?>">
            </p>
            <?php
        });

        add_filter('login_message', function ($msg) {
            $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
            if ($action === 'register') {
                $result = "<p class='message'>Registro de usuario</p>";
                $result .= "<div class='message warning'>";
                $result .= "<p class='font-bold text-blue-800 underline'>¡IMPORTANTE!</p>";
                $result .= '<div class="px-2">
                    <ul class="list-disc">
                        <li>No utilizar caracteres especiales, ni tildes en "Nombre de usuario".</li>
                        <li>
                            <a href="' . get_option(MC_MENU_LINK, "") . '" target="_blank" class="text-blue-800 underline">
                                Ante dudas, siga este manual de registro.
                            </a>
                       </li>
                    </ul>
                </div>';
                $result .= "</div>";

                return $result;
            }
            return $msg;
        });

        add_action('register_form', function () {
            ?>
            <div>
                <details>
                    <summary class="font-bold">Términos y condiciones</summary>
                    <div class="px-3">
                        Al registrarse está aceptando los siguientes términos y condiciones.
                        <ul class="list-disc text-xs px-3">
                            <li>Al proporcionar mis datos acepto voluntariamente que estos datos sean publicados en el directorio de profesionales de la salud mental
                                formados en el modelo CFT gestionado por Cultivar la Mente y Mente Compasiva.
                            </li>
                            <li>Comprendo que este directorio cumple con el fin de dar visibilidad a los profesionales con orientación CFT y facilitar el contacto entre
                                posibles pacientes interesados en seguir un tratamiento centrado en la compasión y profesionales de la salud mental.
                            </li>
                            <li>
                                Cultivar la Mente y Mente Compasiva se reserva el derecho de quitar un registro de este listado ante eventuales quejas o denuncias de mala
                                praxis o problemas de ética profesional.
                            </li>
                        </ul>
                    </div>
                </details>
            </div>
            <?php
        });

        add_filter('registration_errors', function ($errors) {
            if (empty($_POST['pass1'])) {
                $errors->add('password-required', '<strong>Error</strong>: Please enter a password.');
            }

            if (empty($_POST['pass2'])) {
                $errors->add('password-required', '<strong>Error</strong>: Please enter a password confirmation.');
            }

            return $errors;
        });


        add_filter('random_password', function ($password) {
            if ($this->is_on_registration_page() && !empty($_POST['pass1'])) {
                $password = $_POST['pass1'];
            }
            return $password;
        });


        add_filter('wp_new_user_notification_email', function ($wp_new_user_notification_email, $user) {
            $wp_new_user_notification_email['message'] = null;
            return $wp_new_user_notification_email;
        }, 10, 2);

        add_action('login_enqueue_scripts', function () {
            if ($this->is_on_registration_page() && !wp_script_is('user-profile')) {
                wp_enqueue_script('user-profile');
            }
        });
        add_filter('wp_mail_from_name', function ($original_email_from) {
            return "Mente Compasiva";
        });
        add_action('user_register', function ($user_id) {
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            $user_info = get_userdata($user_id);

            $message = "Bienvenido/a a Mente Compasiva.\n";
            $message .= "Le informamos que su registro al sitio web de Mente Compasiva a sido realizado con éxito.\n\n";
            $message .= "Usted puede ingresar en cualquier momento a través del siguiente link. \n";
            $message .= home_url('/login') . "\n\n";
            $message .= "Muchas gracias.\n\n";
            $message .= "Gonzalo Brito.\n";
            $message .= "Equipo de Mente Compasiva.";
            wp_mail($user_info->user_email, "Registro", $message);
            wp_redirect(home_url('/profile?a=register'));
            exit();
        });
    }


    public function is_on_registration_page(): bool
    {
        return $GLOBALS['pagenow'] == 'wp-login.php' && isset($_REQUEST['action']) && $_REQUEST['action'] == 'register';
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
        $language = get_user_meta($user->ID, MC_LANGUAGE);
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
                <th><?= __('Upload profile picture') ?></th>
                <td>
                    <input  name="<?= MC_PICTURE ?>" type="file" accept="image/png,image/jpeg" placeholder="<?= __('Change picture') ?>">
                </td>
            </tr>

            <tr>
                <th>
                    <?= __('Profession') ?>
                </th>
                <td x-data="{showOther: '<?= in_array($profession, $professions) ? $profession : MC_OTHER ?>'}">
                    <div class="flex flex-row space-x-2">
                        <select name="<?= MC_PROFESSION ?>" x-model="showOther">
                            <option value="" <?= $profession === "" ? 'selected' : '' ?> disabled><?= __('select') ?></option>
                            <?php foreach ($professions as $item): ?>
                                <option value="<?= $item ?>" ><?= $item ?></option>
                            <?php endforeach; ?>
                            <option value="<?= MC_OTHER ?>" ><?= __(MC_OTHER) ?></option>
                        </select>
                        <input
                                x-bind:disabled="showOther!=='<?= MC_OTHER ?>'"
                                type="text"
                                name="<?= MC_PROFESSION ?>"
                                value="<?= in_array($profession, $professions) ? '' : $profession ?>"
                        >
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <?= __('Works with') ?>
                </th>
                <td>
                    <?php foreach (get_option(MC_WORKS_WITH, []) as $item): ?>
                        <div>
                            <input type="checkbox" value="<?= $item ?>" name="<?= MC_WORKS_WITH ?>[]" <?= in_array($item, $work_with) ? 'checked' : '' ?>>
                            <span><?= $item ?></span>
                        </div>
                    <?php endforeach; ?>
                </td>
            </tr>

            <tr>
                <th>
                    <?= __('Language') ?>
                </th>
                <td>
                    <?php foreach (get_option(MC_LANGUAGE, []) as $item): ?>
                        <div>
                            <input type="checkbox" value="<?= $item ?>" name="<?= MC_LANGUAGE ?>[]" <?= in_array($item, $language) ? 'checked' : '' ?>>
                            <span><?= $item ?></span>
                        </div>
                    <?php endforeach; ?>
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
                        <option value="<?= MC_LINK_FACEBOOK ?>" <?= $url_mode === MC_LINK_FACEBOOK ? 'selected' : '' ?>>Facebook</option>
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
                update_user_meta($user_id, MC_CFT, 'on');
            }
        } else {
            error_log("Can't found user " . $user_id);
        }
    }

    function mc_user_profile_updated($user_id)
    {

        $user = get_userdata($user_id);
        if ($user) {
            if (!isset($_POST[MC_ENABLED])) {
                $_POST[MC_ENABLED] = 'off';
            }
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
                }
            }
            $post_id = get_user_meta($ID, MC_POST_BIND, true);
            $post_id = intval($post_id);

            $post_data = ["ID" => $post_id, "meta_input" => &$meta_input];

            $maybe_name = $_POST['first_name'] ?? ($_POST[MC_NAME] ?? "");
            if (!empty($maybe_name)) {
                $meta_input[MC_NAME] = $maybe_name;
                $post_data['post_title'] = $maybe_name;
                wp_update_user([
                    'ID' => $ID,
                    'first_name' => $maybe_name,
                ]);
            }

            $meta_fields = [
                MC_COUNTRY,
                MC_CITY,
                MC_BIRTHDAY,
                MC_MODE,
                MC_EMAIL,
                MC_PHONE,
                MC_PROFESSION,
                MC_CFT,
                MC_ENABLED,
                MC_GENDER,
                MC_CFT_WHEN_WHERE,
                MC_ABSTRACT,
                MC_WEBSITE_MODE,
                MC_WEBSITE
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
                $meta_input[MC_ABSTRACT] = $_POST[MC_ABSTRACT];
            }

            $multiple = [
                MC_LANGUAGE,
                MC_WORKS_WITH
            ];

            foreach ($multiple as $field) {
                if (isset($_POST[$field]) && nonEmpty($_POST[$field])) {
                    $ww = $_POST[$field] ?? [];
                    $ww = is_array($ww) ? $ww : [];

                    delete_user_meta($ID, $field);
                    delete_post_meta($post_id, $field);

                    foreach ($ww as $item) {
                        add_user_meta($ID, $field, $item);
                        add_post_meta($post_id, $field, $item);
                    }
                }
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

            if (isset($_POST[MC_EMAIL]) && nonEmpty($_POST[MC_EMAIL])) {
                $user_data['user_email'] = $_POST[MC_EMAIL];
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