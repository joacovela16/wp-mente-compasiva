<?php
const MC_METABOX_ABSTRACT = "mc_metabox_abstract";
const MC_METABOX_IMAGE = "mc_metabox_image";


function mc_list_term($id, $pure = null): array
{
    $taxonomy = "classification";
    $result = [];
    $term = term_exists($id, $taxonomy);

    if (!is_null($term)) {

        $items = get_term_children($term["term_id"], $taxonomy);
        if (is_null($pure)) {
            $caller = function ($slug) {
                return ["key" => strtolower($slug), "value" => $slug];
            };
        } else {
            $caller = $pure;
        }

        foreach ($items as $it) {
            $datum = get_term($it, $taxonomy);

            if (!is_wp_error($datum)) {
                array_push($result, $caller($datum->slug));
            }
        }
    }
    return $result;
}

function buildFilter(): array
{
    $result = [];

    $tagger = function ($slug) {
        return ["key" => $slug, "value" => $slug, 'queryTag' => 'tag'];
    };
    $multimedia = mc_list_term("Multimedia", $tagger);
    $journal = mc_list_term("Journal", $tagger);
    $authors = mc_get_authors();

    if (is_user_logged_in()) {

        $languages = mc_list_term("Language", $tagger);
        $countries = mc_list_term("Country", $tagger);

        $result[] = [
            "id" => "cft",
            "children" => [
                [
                    "id" => "release_date",
                    "children" => [
                        ["id" => "gte", "type" => "date", "queryTag" => 'after'],
                        ["id" => "lte", "type" => "date", "queryTag" => 'before'],
                    ]
                ],
                ["id" => "language", "multiple" => true, "enum" => $languages],
                ["id" => "country", "multiple" => true, "enum" => $countries]
            ]
        ];
    }

    $result[] = [
        "id" => "resource",
        "children" => [
            ["id" => "author", "multiple" => true, "queryTag" => 'author', 'enum' => $authors],
            ["id" => "multimedia", "multiple" => true, "enum" => $multimedia],
            ["id" => "journal", "multiple" => true, "enum" => $journal],
            [
                "id" => "publish_date",
                "multiple" => false,
                "children" => [
                    ["id" => "gte", "type" => "date", "queryTag" => 'after'],
                    ["id" => "lte", "type" => "date", "queryTag" => 'before'],
                ]
            ],
        ]
    ];

    return $result;
}

function mc_get_authors(): array
{
    global $wpdb;
    $authors = [];
    $result = $wpdb->get_results(
        "SELECT DISTINCT post_author, COUNT(ID) AS count FROM $wpdb->posts WHERE " . get_private_posts_cap_sql('post') . ' GROUP BY post_author'
    );
    foreach ($result as $row) {
        $user = get_userdata($row->post_author);
        $display_name = $user->display_name;
        $authors[] = ['key' => 'author', 'value' => $display_name];
    }
    return $authors;
}