<?php
include_once "constants.php";

function mc_register_taxonomy()
{
    $labels = array(
        'name' => _x('Classification', 'taxonomy general name'),
        'singular_name' => _x('Classification', 'taxonomy singular name'),
        'search_items' => __('Search Classification'),
        'all_items' => __('All Classification'),
        'parent_item' => __('Parent Classification'),
        'parent_item_colon' => __('Parent Classification:'),
        'edit_item' => __('Edit Classification'),
        'update_item' => __('Update Classification'),
        'add_new_item' => __('Add New Classification'),
        'new_item_name' => __('New Classification Name'),
        'menu_name' => __('Classification'),
    );
    $args = array(
        'hierarchical' => true, // make it hierarchical (like categories)
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'classification'],
    );
    register_taxonomy(CLASSIFICATION_TAXONOMY, [DIRECTORY_CATALOG], $args);
    mc_terms_builder();
}

function mc_unregister_taxonomy()
{
    clean_taxonomy_cache(CLASSIFICATION_TAXONOMY);
    mc_terms_cleaner();
    unregister_taxonomy(CLASSIFICATION_TAXONOMY);
}

function mc_terms_builder()
{
    function iterator(int $parent, array $term)
    {
        foreach ($term as $term_item) {
            $id = $term_item["id"];
            $children = $term_item["children"];
            $term_query = term_exists($id, CLASSIFICATION_TAXONOMY);

            if (is_null($term_query)) {
                // don't exists
                $term_result = wp_insert_term($id, CLASSIFICATION_TAXONOMY, array("parent" => $parent));
            } else {
                // term exists
                $local_parent = intval($term_query["term_id"]);
                iterator($local_parent, $children);
            }
        }
    }

    iterator(0, mc_main_terms());
}

function mc_terms_cleaner()
{

    $taxonomy_name = CLASSIFICATION_TAXONOMY;

    $terms = get_terms(array(
        'taxonomy' => $taxonomy_name,
        'hide_empty' => false
    ));
    foreach ($terms as $term) {
        wp_delete_term($term->term_id, $taxonomy_name);
    }
}

