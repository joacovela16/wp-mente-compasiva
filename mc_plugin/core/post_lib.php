<?php

function mc_do_pages(): void
{
    foreach (mc_obtain_pages() as $item) {
        $page = get_page_by_path($item["post_name"]);

        if (is_null($page)) {
            $result = wp_insert_post($item);
            if (is_wp_error($result)) {
                error_log($result->get_error_message());
            }
        }
    }
}

function mc_undo_pages(): void
{
    foreach (mc_obtain_pages() as $item) {
        $page = get_page_by_path($item["post_name"]);

        if (!is_null($page)) {
            wp_delete_post($page->ID, true);

        }
    }
}


function mc_do_post(): void
{
    foreach (mc_obtain_model() as $k => $v) {
        $result = register_post_type($k, $v);
        if (is_wp_error($result)) {
            error_log($result->get_error_message());
        } else {
            error_log($k . " active");
        }
    }
}

function mc_undo_post()
{
    foreach (mc_obtain_model() as $k => $v) {
        $result = unregister_post_type($k);
        if (is_wp_error($result)) {
            error_log($result->get_error_message());
        } else {
            error_log("Post " . $k . " has been deleted.");
        }
    }
}

function mc_get_post_by_id()
{
    $post_id = intval($_POST['post_id']);

    // Check if the input was a valid integer
    if ($post_id == 0) {

        $response['error'] = 'true';
        $response['result'] = 'Invalid Input';

    } else {

        // get the post
        $thispost = get_post($post_id);

        // check if post exists
        if (!is_object($thispost)) {

            $response['error'] = 'true';
            $response['result'] = 'There is no post with the ID ' . $post_id;

        } else {

            $response['error'] = 'false';
            $response['result'] = wpautop($thispost->post_content);

        }

    }

    wp_send_json($response);
}

function mc_obtain_pages(): array
{
    return [
        ["post_title" => "About", "post_type" => "page", 'public' => true, 'post_status' => 'publish', "post_name" => "mc_about"],
        ["post_title" => "Contact", "post_type" => "page", 'public' => true, 'post_status' => 'publish', "post_name" => "mc_contact"],
        ["post_title" => "Login", "post_type" => "page", 'public' => true, 'post_status' => 'publish', "post_name" => "mc_login"],
        ["post_title" => "Profile", "post_type" => "page", 'public' => true, 'post_status' => 'publish', "post_name" => "mc_profile"],
    ];
}

function mc_obtain_model(): array
{
    return array(
        DIRECTORY_CATALOG => [
            'labels' => ["name" => 'Catalog', 'singular_name' => 'Catalog'],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'rewrite' => [
                "slug" => "catalog"
            ]
        ],
        PROFESSIONAL_CATALOG => [
            'labels' => ["name" => 'Prof. Catalog', 'singular_name' => 'Prof. Catalog'],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'rewrite' => [
                "slug" => "pro_catalog"
            ]
        ],
        "news" => [
            "labels" => ["name" => "News", "singular_name" => "news"],
            "public" => true,
            'has_archive' => true,
            'show_in_rest' => true,
        ]
    );
}