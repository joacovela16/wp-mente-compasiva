<?php

class MCPermissionLib
{
    public function init()
    {
        add_action('pre_get_posts', [$this, "pre_get_posts"], 10, 1);
        add_filter('query_vars', function ($qvars) {
            $qvars[] = "ptype";
            $qvars[] = "country";
            return $qvars;
        });
    }


    public function pre_get_posts(WP_Query $query)
    {
        if ($query->is_search()) {
            if (isset($_GET['ptype'])) {
                $ptype = $_GET['ptype'];
                $user = wp_get_current_user();
                $settings = get_option(MC_SETTING);
                $user_perm = get_user_meta($user->ID, MC_METABOX_PERMISSION, true);
                $user_rules = get_user_meta($user->ID, MC_METABOX_PERMISSION_RULE);
                $pbase = array_find($settings[MC_PERMISSIONS], fn($x) => $x[MC_ID] === $ptype);

                if ($user_rules === []) {
                    $prm = $settings[MC_DEFAULTS][MC_USER] ?? [];
                    $user_perm = $prm;

                    foreach ($prm ?? [] as $item) {
                        foreach ($item[MC_CAPABILITIES] ?? [] as $cap) {
                            $user_rules[] = $pbase[MC_ID] . "::" . $cap;
                        }
                    }
                }

                $allowed_post_types = array_unique(array_merge(...array_values(array_map(fn($x) => $x[MC_POST_TYPES] ?? [], $user_perm))));

//                $ptypes = is_null($pbase) ? [] : $pbase[MC_POST_TYPES] ?? [];
//                $is_ok = array_forall($ptypes, fn($x) => in_array($x, $allowed_post_types));

                if ($settings) {

                    $query->set("post_type", $allowed_post_types);

                    $countries = $_GET['country'] ?? [];
                    $country_query = array_map(fn($x) => ['key' => MC_METABOX_COUNTRIES, 'value' => $x, 'compare' => '='], $countries);

                    $array_map = array_map(fn($x) => ['key' => MC_METABOX_PERMISSION_RULE, 'value' => $x, 'compare' => '='], $user_rules);
                    $query->set("meta_query", [
                        'relation' => 'AND',
                        ['relation' => 'OR', ...$array_map],
                        ['relation' => 'OR', ...$country_query]
                    ]);
                    $query->set('order', 'DESC');
                    $query->set('post_status', 'publish');

                    $has_date = false;
                    $container = [];
                    if (!empty($_GET['before'])) {
                        $has_date = true;
                        $container['before'] = $_GET['before'];
                    }

                    if (!empty($_GET['after'])) {
                        $has_date = true;
                        $container['after'] = $_GET['after'];
                    }

                    if ($has_date) {
                        $container['inclusive'] = true;
                        $query->set('date_query', [$container]);
                    }

                } else {
                    wp_redirect("/", 301);
                    wp_die();
                }
            }
            $query->set("posts_per_page", 12);
            $query->set("post_status", "publish");
        }
    }

    public static function get_permissions(): array
    {
        $settings = get_option(MC_SETTING);
        $result = [];

        if ($settings) {
            $user = wp_get_current_user();
            $userID = $user->ID;
            if ($user) {
                $permissions = $settings[MC_PERMISSIONS] ?? [];
                $user_permissions = get_user_meta($userID, MC_METABOX_PERMISSION, true);
                $user_permissions = $user_permissions === '' ? $settings[MC_DEFAULTS][MC_USER] ?? [] : $user_permissions;

                $user_permissions_map = $user_permissions;//array_as_map($user_permissions, fn($x) => $x[MC_NAME]);

                if (is_user_logged_in()) {

                    foreach ($permissions as $item) {
                        $name = $item[MC_NAME];
                        $user_permission = $user_permissions_map[$name];

                        if (isset($user_permission) && count($user_permission[MC_POST_TYPES]) > 0) {
                            $result[] = $user_permission;
                        }
                    }
                } else {
                    foreach ($permissions as $item) {
                        if ($item[MC_LOGGED_REQUIRED] !== "on" && count($item[MC_POST_TYPES]) > 0) {
                            $result[] = $item;
                        }
                    }
                }
            }

        }
        return $result;
    }

}
