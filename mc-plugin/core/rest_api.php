<?php

class MCRestAPI extends WP_REST_Controller
{
    public function register_routes()
    {
        $version = "v1";
        $ns = "mcplugin/" . $version;

        register_rest_route($ns, "/resume", [
            "methods" => "GET",
            "permission_callback" => "__return_true",
            "callback" => [$this, "mc_rest_api_resume"]
        ]);

        register_rest_route($ns, "/create_user", [
            "methods" => "POST",
            "permission_callback" => "__return_true",
            "callback" => [$this, "create_user"]
        ]);

        register_rest_route($ns, "/search", [
            "methods" => "GET",
            "permission_callback" => "__return_true",
            "callback" => [$this, "do_search"]
        ]);
    }

    public function do_search(WP_REST_Request $request): array
    {
        $query_result = ["post_type" => DIRECTORY_CATALOG];

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
                "tax_query" => [
                    'relation' => 'OR',
                    $args
                ]
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

