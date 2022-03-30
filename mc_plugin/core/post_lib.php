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
        register_taxonomy_for_object_type('post_tag', $k);
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
        unregister_taxonomy_for_object_type('post_tag', $k);
        if (is_wp_error($result)) {
            error_log($result->get_error_message());
        } else {
            error_log("Post " . $k . " has been deleted.");
        }
    }
}

function mc_obtain_pages(): array
{
    return [
        ["post_title" => "Profile", "post_type" => "page", 'public' => true, 'post_status' => 'publish', "post_name" => MC_PAGE_PROFILE],
        ["post_title" => "Register", "post_type" => "page", 'public' => true, 'post_status' => 'publish', "post_name" => MC_PAGE_REGISTER],
    ];
}

function mc_obtain_model(): array
{
    return [
        CFT_DIRECTORY => [
            'labels' => ["name" => 'Directorio CFT', 'singular_name' => 'directory_cft'],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'rewrite' => [
                "slug" => "directorio-cft"
            ]
        ]
    ];
}