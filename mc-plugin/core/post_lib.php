<?php

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

function mc_obtain_model(): array
{
    return array(
        DIRECTORY_CATALOG => [
            'labels' => ["name" => 'Catalog', 'singular_name' => 'Catalog'],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
        ],
        "news" => [
            "labels" => ["name" => "News", "singular_name" => "news"],
            "public" => true,
            'has_archive' => true,
            'show_in_rest' => true,
        ]
    );
}