<?php

class MCRestAPI extends WP_REST_Controller
{
    public function register_routes()
    {
        $version = "v1";
        $ns = "mcplugin/" . $version;

       /* register_rest_route($ns, "/resume", [
            "methods" => "GET",
            "permission_callback" => "__return_true",
            "callback" => [$this, "mc_rest_api_resume"]
        ]);*/

       /* register_rest_route($ns, "/user", [
            "methods" => "PUT",
            "permission_callback" => "__return_true",
            "callback" => [$this, "create_user"]
        ]);*/

        register_rest_route($ns, "/user", [
            "methods" => "POST",
            "permission_callback" => function () {
                return is_user_logged_in();
            },
            "callback" => [$this, "do_update_user"]
        ]);

       /* register_rest_route($ns, "/search", [
            "methods" => "GET",
            "permission_callback" => "__return_true",
            "callback" => [$this, "do_search"]
        ]);*/

        add_filter("insert_user_meta", [$this, "mc_insert_user_meta"], 10, 4);
    }

    public function mc_insert_user_meta($meta, $user, $update, $userdata): array
    {
        return array_merge($meta, ["user_avatar_url" => $userdata["user_avatar_url"], "user_avatar_id" => $userdata["user_avatar_id"]]);
    }

    public function do_update_user(WP_REST_Request $request): array
    {
        $user = wp_get_current_user();
        $avatarURL = null;
        $attachmentID = null;
        $user_data = ["ID" => $user->ID];

        if (!is_null($_FILES["file"])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            $lastAttachment = get_user_meta($user->ID, "user_avatar_id", true);

            if (!empty($lastAttachment)) {

                $id = intval($lastAttachment);
                $wp_delete_attachment_result = wp_delete_attachment($id);

                if (is_wp_error($wp_delete_attachment_result)) {
                    error_log("problem deleting attachment $id");
                }
            }

            $attachmentID = media_handle_upload("file", null);
            $avatarURL = wp_get_attachment_url($attachmentID);
            $user_data = array_merge($user_data, ["user_avatar_url" => $avatarURL, "user_avatar_id" => $attachmentID]);
        }

        if ($user->ID !== 0) {
            $post_id = get_user_meta($user->ID, MC_USER_REF, true);

            $meta = [
                MC_METABOX_ABSTRACT => $_POST["description"]
            ];
            if (!$avatarURL == null){
                $meta["user_avatar_url"] = $avatarURL;
            }
            $update_result = wp_update_post([
                "ID" => intval($post_id),
                "post_content" => $_POST["description"],
                "meta_input" => $meta
            ]);

            $user_data = array_merge($user_data, [
                "display_name" => $_POST["display_name"],
                "user_email" => $_POST["user_email"],
                "description" => $_POST["description"],
                "user_url" => $_POST["user_url"],
            ]);

            $new_password = $_POST["new_password"];
            $confirm_password = $_POST["confirm_password"];

            if ($new_password == $confirm_password) {
                if (!empty($new_password) && !empty($confirm_password)) {
                    $user_data = array_merge($user_data, ["user_pass" => $new_password]);
                }
            }
            $wp_update_user_result = wp_update_user($user_data);

            if (!is_wp_error($wp_update_user_result)) {
                return [
                    "isSuccess" => true,
                    "userData" => $user_data
                ];
            }
        }

        return ["isSuccess" => false];
    }

    public function do_search(WP_REST_Request $request): array
    {
        $is_logged = is_user_logged_in();
        $maybePage = $request->get_param("page");
        if (is_null($maybePage)) {
            $page = 1;
        } else {
            $page = intval($maybePage);
        }
        $query_result = ["post_type" => DIRECTORY_CATALOG, "posts_per_page" => 10, "paged" => $page];

        if (!is_null($request->get_param("s"))) {
            $query_result = array_merge($query_result, ["s" => $request->get_param("s")]);
        }

        if (!is_null($request->get_param("author_name"))) {
            $query_result = array_merge($query_result, ["author_name" => $request->get_param("author_name")]);
        }

        if (!is_null($request->get_param("tag"))) {
            $func = function ($v) {
                return [
                    'taxonomy' => CLASSIFICATION_TAXONOMY,
                    "field" => "slug",
                    "terms" => $v
                ];
            };
            $args = array_map($func, explode(",", $request->get_param("tag")));
            $query_result = array_merge($query_result, [
                "tax_query" => array_merge(
                    [
                        'relation' => 'OR',
                    ],
                    $args
                )
            ]);
        }

        $date_query = [];

        if (!is_null($request->get_param("before"))) {
            $date = explode('/', $request->get_param("before"));
            $date_query = array_merge($date_query, [
                "before" => ["day" => $date[0], "month" => $date[1], "year" => $date[2]]
            ]);
        }

        if (!is_null($request->get_param("after"))) {
            $date = explode('/', $request->get_param("after"));
            $date_query = array_merge($date_query, [
                "after" => ["day" => $date[0], "month" => $date[1], "year" => $date[2]]
            ]);
        }

        if (!$is_logged) {
            $query_result = array_merge($query_result, [
                "meta_query" => [
                    ["key" => "kind", "compare" => "NOT EXISTS"]
                ]
            ]);
        }

        if (count($date_query) > 0) {
            $query_result = array_merge($query_result, ["date_query" => $date_query]);
        }

        $query = new WP_Query($query_result);
        $posts = $query->get_posts();

        $result = [];
        foreach ($posts as $post) {
            array_push($result, [
                "id" => $post->ID,
                "title" => $post->post_title,
                "publishDate" => $post->post_date,
                "author" => get_userdata($post->post_author)->display_name,
                "postType" => $post->post_type
            ]);
        }

        return $result;
    }

    public function create_user(WP_REST_Request $request): array
    {
        $body = json_decode($request->get_body(), true);
        $result = wp_insert_user([
            "first_name" => $body["name"],
            "user_login" => $body["email"],
            "user_pass" => $body["password"],
        ]);
        if (is_wp_error($result)) {
            return ["result" => $result->get_error_message()];
        }

        return ["result" => "ok"];
    }

    public function mc_rest_api_resume(WP_REST_Request $request): array
    {
        error_log("pepe");

        return array("result" => "test2!!!");
    }
}

