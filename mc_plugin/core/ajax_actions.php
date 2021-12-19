<?php
add_action("wp_ajax_mc_search_dc", "mc_search_dc");
add_action("wp_ajax_nopriv_mc_search_dc", "mc_search_dc");

function mc_search_dc()
{
    $mode = $_GET['mode'];
    $page = $_GET['page'] ? intval($_GET['page']) : 1;
    $selections = [
        "post_type" => DIRECTORY_CATALOG,
        'paged' => $page,
        "posts_per_page" => 10,
    ];
    if ($mode === TERM_PERSON) {
        $selections['tax_query'] = [
            ['taxonomy' => CLASSIFICATION_TAXONOMY, "field" => "slug", 'terms' => [TERM_PERSON]]
        ];
    } elseif ($mode === "Resource" || !is_user_logged_in()) {
        $selections['tax_query'] = [
            ['taxonomy' => CLASSIFICATION_TAXONOMY, "field" => "slug", 'terms' => [TERM_PERSON], 'operator' => 'NOT IN']
        ];
    }

    $query = new WP_Query($selections);
    $posts = $query->get_posts();
    $result = ['hasNext' => $query->max_num_pages > 0];

    if ($query->post_count > 0) {
        ob_start();
        foreach ($posts as $post) {
            mc_render_post_item($post);
        }
        $main_content = ob_get_contents();
        $result['content'] = $main_content;
        ob_end_clean();
    }
    wp_send_json($result);
    wp_die();
}