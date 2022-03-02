<?php

class MCPermissionLib
{
    public function init()
    {
        add_action('pre_get_posts', [$this, "pre_get_posts"], 10, 1);
        add_filter('query_vars', [$this, 'query_vars']);
    }

    public function query_vars($qvars)
    {
        $qvars[] = "ptype";
        $qvars[] = "country";
        return $qvars;
    }

    public function pre_get_posts(WP_Query $query)
    {
        if ($query->is_search()) {

            $meta_query = ['relation' => 'AND'];

            // country filter
            $countries = $_GET['country'] ?? [];
            $country_query = array_map(fn($x) => ['key' => MC_METABOX_COUNTRIES, 'value' => $x, 'compare' => '='], $countries);
            if (count($country_query) > 0) {
                $meta_query[] = ['relation' => 'OR', ...$country_query];
            }

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

            $settings = get_option(MC_SETTING);

            if (is_user_logged_in()) {
                $user = wp_get_current_user();
                $user_rules = get_user_meta($user->ID, MC_METABOX_PERMISSION_RULE);
                $ptype = $_GET['ptype'] ?? null;
                $pbase = empty($ptype) ? null : array_find($settings[MC_PERMISSIONS], fn($x) => $x[MC_ID] === $ptype);

                if (!empty($pbase)) {
                    $indexer = fn($x) => $pbase[MC_ID];
                } else {
                    $indexer = fn($x) => $x[MC_ID];
                }

                if ($user_rules === []) {
                    $prm = $settings[MC_DEFAULTS][MC_USER] ?? [];

                    foreach ($prm ?? [] as $item) {
                        foreach ($item[MC_CAPABILITIES] ?? [] as $cap) {
                            $user_rules[] = $indexer($item) . "::" . $cap;
                        }
                    }
                }
                if (count($user_rules) > 0) {
                    $user_rules_query = array_map(fn($x) => ['key' => MC_METABOX_PERMISSION_RULE, 'value' => $x, 'compare' => '='], $user_rules);
                    $meta_query[] = ['relation' => 'OR', ...$user_rules_query];
                }
            } else {
                $perms = $settings[MC_PERMISSIONS] ?? [];
                $post_allowed = [];
                foreach ($perms as $item) {
                    if (!isset($item[MC_LOGGED_REQUIRED]) || $item[MC_LOGGED_REQUIRED] !== 'on') {
                        $post_allowed = array_merge($post_allowed, $item[MC_POST_TYPES] ?? []);
                    }
                }
                if (count($post_allowed) === 0) {
                    $query->set("post_type", ['mc' . rand()]);
                } else {
                    $query->set("post_type", $post_allowed);
                }
            }
            if (count($meta_query) > 1){
                $query->set('meta_query', $meta_query);
            }

            $query->set("posts_per_page", 12);
            $query->set("post_status", "publish");
            $query->set('order', 'DESC');
            $query->set('post_status', 'publish');
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

                $user_permissions_map = $user_permissions;

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
