<?php
const MC_LOGGED_REQUIRED = "logged_required";
const MC_CAPABILITIES = "capabilities";
const MC_NAME = "name";
const MC_POST_TYPES = "post_types";
const MC_PERMISSIONS = 'permissions';

const MC_DEFAULT_PERMISSION = [
    MC_NAME => "New item",
    MC_LOGGED_REQUIRED => true,
    MC_POST_TYPES => [],
    MC_CAPABILITIES => []
];


function mc_can_show_post($post_type): bool
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

            if (is_user_logged_in()){

                foreach ($permissions as $item) {
                    $name = $item[MC_NAME];
                    $user_permission = $user_permissions_map[$name];

                    if (isset($user_permission)) {

                    }
                }
            }else{
            }
            return $result;
        }
    }
    return false;
}