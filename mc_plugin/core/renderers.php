<?php
function mc_render_post_item(WP_Post $post)
{
    $author = get_userdata($post->post_author);
    $image_url = get_post_meta($post->ID, MC_METABOX_IMAGE . "_id", true);
    $abstract = get_post_meta($post->ID, MC_METABOX_ABSTRACT, true);
    $terms = wp_get_post_terms($post->ID, CLASSIFICATION_TAXONOMY);
    $is_person = array_exists($terms, fn($x) => $x->name === TERM_PERSON);

    ?>
    <div class="flex flex-col">
        <div class="h-48 w-full overflow-hidden">
            <img src="<?= $image_url ?>" alt="" class="object-cover w-full h-48">
        </div>
        <?php if (!is_wp_error($terms) && count($terms) > 0): ?>
            <div class="flex flex-wrap space-x-1 my-1">
                <?php foreach ($terms as $term): ?>
                    <div class="rounded-full px-1 text-xs ring-1 ring-blue-500"><?= $term->name ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif ?>
        <div class="font-bold text-lg"><?= $post->post_title ?></div>
        <?php if (!$is_person): ?>
            <div class="flex py-2 items-center text-sm">
                <div> <?= __('By') . ' ' . $author->display_name ?></div>
                <div class="flex-1"></div>
                <div><?= get_the_time("F j, Y", $post) ?></div>
            </div>
        <?php endif; ?>
        <div class="text-gray-600 text-sm"><?= $abstract ?></div>
        <div class="flex-1"></div>
        <div class="underline mt-3">
            <a href="<?= get_permalink($post) ?>">
                <?= __('Read more') ?>...
            </a>
        </div>
    </div>
    <?php
}
