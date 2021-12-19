<?php

function mc_search_engine($query, $selections): WP_Query
{
//    $selections = $_SESSION[SEARCH_SELECTION];
    $is_logged = is_user_logged_in();
    $maybePage = $query["page"] ?? 1;
    $post_type = $query['post_type'];

    if (is_null($maybePage)) {
        $page = 1;
    } else {
        $page = intval($maybePage);
    }
    $query_result = ["post_type" => $post_type, "posts_per_page" => 10, "paged" => $page];

    if (!is_null($query["s"])) {
        $query_result = array_merge($query_result, ["s" => $query["s"]]);
    }

    $author = array_find($selections, fn($x) => $x['key'] === 'author');
    if (!is_null($author)) {
        $query_result = array_merge($query_result, ["author_name" => $author["value"]]);
    }

    $tags = array_map(fn($x) => $x['value'], array_filter($selections, fn($x) => $x['key'] === 'tag'));
    if (!empty($tags)) {
        $func = function ($v) {
            return [
                'taxonomy' => CLASSIFICATION_TAXONOMY,
                "field" => "slug",
                "terms" => $v
            ];
        };
        $args = array_map($func, $tags);
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

    $before = array_find($selections, fn($x) => $x['key'] === "before");
    if (!is_null($before)) {
        $date = explode('/', $before);
        $date_query = array_merge($date_query, [
            "before" => ["day" => $date[0], "month" => $date[1], "year" => $date[2]]
        ]);
    }

    $after = array_find($selections, fn($x) => $x['key'] === "after");
    if (!is_null($after)) {
        $date = explode('/', $after);
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

    return new WP_Query($query_result);
}
