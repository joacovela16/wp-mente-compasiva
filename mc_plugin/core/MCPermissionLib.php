<?php

class MCPermissionLib
{
    public function init()
    {
        add_action('pre_get_posts', [$this, "pre_get_posts"], 10, 1);
        add_filter('query_vars', function ($qvars) {
            $qvars[] = "ptype";
            return $qvars;
        });


//        add_filter("previous_posts_link_attributes", [$this, "linksAttr"]);
//        add_filter("next_posts_link_attributes", [$this, "linksAttr"]);
    }


    public function linksAttr($attrs){
        return 'class="text-red-500"';
    }


    public function pre_get_posts(WP_Query $query)
    {
        if ($query->is_search()) {
            if (isset($_GET['ptype'])) {
                $ptypes = isset($_GET['ptype']) ? explode(",", $_GET['ptype'] ?? "") : [];
                $settings = get_option(MC_SETTING);
                if ($settings) {

                    $query->set("post_type", $ptypes);
                } else {
                    wp_redirect("/");
                    wp_die();
                }
            }
            $query->set("posts_per_page", 4);
            $query->set("post_status", "publish");


        }

    }

    public static function get_permissions($user): array
    {
        $settings = get_option(MC_SETTING);
        $result = [];

        if ($settings) {
            if ($user) {
                $permissions = $settings[MC_PERMISSIONS] ?? [];
                $user_permissions = get_user_meta($user, MC_METABOX_PERMISSION, true);
                $user_permissions = $user_permissions === '' ? [] : $user_permissions;

                $user_permissions_map = $user_permissions;//array_as_map($user_permissions, fn($x) => $x[MC_NAME]);
                $is_user_logged_in = is_user_logged_in();

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

    public static function mc_can_show_post($post_type): bool
    {

        $settings = get_option(MC_SETTING);
        if ($settings) {
            $user = get_current_user_id();
            if ($user) {
                $permissions = $settings[MC_PERMISSIONS] ?? [];
                $user_permissions = get_user_meta($user, MC_METABOX_PERMISSION, true);
                $user_permissions = $user_permissions === '' ? [] : $user_permissions;

                $user_permissions_map = array_as_map($user_permissions, fn($x) => $x[MC_NAME]);
                $is_user_logged_in = is_user_logged_in();
                $result = false;

                if (is_user_logged_in()) {

                    foreach ($permissions as $item) {
                        $name = $item[MC_NAME];
                        $user_permission = $user_permissions_map[$name];

                        if (isset($user_permission)) {

                        }
                    }
                } else {
                }
                return $result;
            }
        }
        return false;
    }
}
