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
            $name = $item['name'] ?? '';
            $country = $item['country']?? '';
            $location = $item['location']?? '';
            $description = $item['description']?? '';
            $email = $item['email']?? '';
            $phone = $item['phone']?? '';
            $website = $item['website']?? '';
            $password = 'QgHTPqkTzg4K6u';
            $when_and_where = $item['¿Cuándo y dónde completaste el programa CFT-III?'] ?? '';
            $user = wp_create_user($name, $password, $email);

            if (is_wp_error($user)) {
                error_log(`ERROR: Usuario ${$name}/${email} no pudo ser creado`);
                $user = get_user_by('email', $email);
            }

            if ($user) {
                $post_id = get_user_meta($user, MC_POST_BIND, true);
                if (!empty($post_id)) {
                    $post = get_post(intval($post_id));
                    if (nonEmpty($post)) {
                        $metaInput = [MC_CFT => 'on'];

                        if (nonEmpty($description)) $metaInput[MC_ABSTRACT] = $description;
                        if (nonEmpty($country)) $metaInput[MC_COUNTRY] = $country;
                        if (nonEmpty($location)) $metaInput[MC_CITY] = $location;
                        if (nonEmpty($email)) $metaInput[MC_EMAIL] = $email;
                        if (nonEmpty($phone)) $metaInput[MC_PHONE] = $phone;
                        if (nonEmpty($website)) $metaInput[MC_WEBSITE] = $website;
                        if (nonEmpty($website)) $metaInput[MC_WEBSITE] = $website;
                        if (nonEmpty($when_and_where)) $metaInput[MC_CFT_WHEN_WHERE] = $when_and_where;

                        foreach ($metaInput as $k=>$v){
                            update_post_meta($post->ID, $k, $v);
                            update_user_meta($user, $k, $v);
                        }
                    }
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


    public function login_redirect($redirect_to, $requested_redirect_to, $user): string
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
        if (is_page(MC_PAGE_PROFILE) && !is_user_logged_in()) {
            wp_redirect(site_url(), 301);
            exit;
        }
    }

    public function getAndSet(array $source, array &$target, $sourceField, $targetField)
    {
        if (isset($source[$sourceField]) && !empty($source[$sourceField])) {
            $target[$targetField] = $source[$sourceField];
        }
    }

    public function update_user_action()
    {
        $user = wp_get_current_user();
        MCUserLib::update_user_data($user);
        wp_safe_redirect('/profile');
    }

}