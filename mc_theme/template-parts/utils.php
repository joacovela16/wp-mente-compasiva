<?php

function get_post_views()
{
    $id = get_the_ID();
    $count = get_post_meta($id, "mc_count_views", true);
    if (!$count) $count = 0;
    update_post_meta($id, "mc_count_views", $count);

//    if($count>1000000000000) return round(($count/1000000000000),1).' trillion';
//    else if($count>1000000000) return round(($count/1000000000),1).' billion';
    if ($count > 1000000) return round(($count / 1000000), 1) . ' M';
    else if ($count > 1000) return round(($count / 1000), 1) . ' K';
    return $count;
}


function render_cft(WP_Post $post)
{
    $abstract = get_post_meta($post->ID, MC_ABSTRACT, true);

    $image_url = get_post_meta($post->ID, MC_AVATAR_URL, true);
    $image_url = empty($image_url) ? get_avatar_url(0) : $image_url;

    $country = get_post_meta($post->ID, MC_COUNTRY, true);
    $profession = get_post_meta($post->ID, MC_PROFESSION, true);
    $city = get_post_meta($post->ID, MC_CITY, true);;
    ?>
    <div class="flex flex-row items-center space-x-2 hover:bg-gray-100 p-1 rounded-lg">
        <img class="object-cover object-center w-24 h-24 rounded-full shadow-lg" src="<?= $image_url ?>" alt="blog">
        <a class="flex-1 max-w-md overflow-hidden" href="<?= get_permalink($post) ?>">
            <p class="text-lg font-bold"><?= $post->post_title ?></p>
            <p class="whitespace-nowrap truncate text-gray-500 " title="<?= $abstract ?>"><?= $abstract ?></p>
            <?php if (!empty($profession)): ?>
                <p><?= $profession ?></p>
            <?php endif; ?>
            <p><?= $city . ', ' . $country ?></p>
        </a>
    </div>
    <?php
}

function render_post(WP_Post $post)
{
    $abstract = get_post_meta($post->ID, MC_ABSTRACT, true);
    $image_url = get_post_meta($post->ID, MC_METABOX_IMAGE, true);
    $tags = get_the_tags();
    $is_person = get_post_meta($post->ID, MC_KIND, true) === MC_PERSON;
    $rounded_img = $is_person ? 'rounded-full lg:w-48 md:w-36 border-4' : 'w-full lg:h-48 md:h-36';
    ?>
    <div class="h-full shadow-lg rounded-lg overflow-hidden flex flex-col">
        <?php if (!is_numeric($image_url)): ?>
            <img class="mx-auto object-cover object-center <?= $rounded_img ?>" src="<?= get_template_directory_uri() . "/assets/images/img" . random_int(1, 3) . ".svg" ?>" alt="blog">
        <?php else: ?>
            <img class="mx-auto object-cover object-center <?= $rounded_img ?>" src="<?= wp_get_attachment_url($image_url) ?>" alt="blog">
        <?php endif; ?>
        <div class="p-6 flex flex-col flex-1">
            <div class="flex flex-row">
                <h2 class="text-xs title-font font-medium text-gray-400 mb-1 flex space-x-2">
                    <?php if (is_array($tags)): ?>
                        <?php foreach ($tags as $tag): ?>
                            <span><?= $tag->name ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </h2>
                <div class="text-gray-400 py-1 flex-1 text-right">
                    <?php
                    if ($is_person):
                        $country = get_post_meta($post->ID, MC_COUNTRY, true);
                        $location = get_post_meta($post->ID, MC_CITY, true);
                        echo $country . ' - ' . $location;
                    else:
                        the_date();
                    endif;
                    ?>
                </div>
            </div>
            <h1 class="title-font text-lg font-medium text-gray-900 mb-3"><?= $post->post_title ?></h1>
            <div class="flex-1 mb-3 max-h-48 overflow-hidden text-ellipsis whitespace-nowrap"><?= $abstract ?></div>
            <div class="flex items-center flex-wrap">
                <a class="text-blue-500 inline-flex items-center md:mb-2 lg:mb-0" href="<?= get_permalink($post) ?>">
                    <?= __('Read more') ?>
                    <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="M12 5l7 7-7 7"></path>
                    </svg>
                </a>
                <span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                    <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <?= get_post_views() ?>
                </span>
                <span class="text-gray-400 inline-flex items-center leading-none text-sm">
                    <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                    </svg>
                    <?php
                    $args = ["post_id" => $post->ID, 'count' => true];
                    echo get_comments($args);
                    ?>
                </span>
            </div>
        </div>
    </div>
    <?php

}