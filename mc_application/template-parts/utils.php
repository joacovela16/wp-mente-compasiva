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

function goBack()
{
    echo div(["class" => "cursor-pointer font-bold underline", "@click" => "goBack()"], __("Back"));
}

function field(array $config): string
{
    $label = isset($config["label"]) ? div(['class' => "font-bold z-index-10"], render($config, 'label')) : '';

    return div(['class' => "flex flex-col space-y-2 flex-1 justify-center"], $label, render($config, 'content'));
}

function modal(array $config, string $handler): string
{
    $header = isset($config["header"]) ? div(["class" => 'font-bold z-index-10'], render($config, 'header')) : '';
    $footer = isset($config["footer"]) ? div(["class" => 'py-3 px-2 bg-cool-gray-100'], render($config, 'footer')) : '';

    return div(["class" => "flex justify-center items-center fixed top-0 left-0 w-full h-full z-index-20", "x-show" => $handler],
        div(["class" => "absolute top-0 left-0 bg-black opacity-90 w-full h-full"]),
        div(["class" => "rounded-lg w-auto max-h-4/5 bg-white overflow-hidden shadow-md flex flex-col z-index-10"],
            div(["class" => "flex flex-row py-5 px-2 bg-cool-gray-100 relative"],
                $header,
                div(['class' => "flex-1"]),
                div(['class' => "cursor-pointer z-index-10", "@click" => $handler . "=false"],
                    '    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>'
                )
            ),
            div(["class" => "flex-1 p-2 container mx-auto flex overflow-auto"], render($config, "content")),
            $footer
        )
    );
}


function render(array $data, $field)
{
    if (isset($data[$field])) {
        $fieldValue = $data[$field];
        if (is_string($fieldValue)) {
            if (function_exists($fieldValue)) {
                return ($fieldValue)();
            } else {
                return $fieldValue;
            }
        } else if (is_callable($fieldValue)) {
            return $fieldValue();
        }
    }
    return "";
}

function mc_get_user_avatar_url()
{
    $currentUser = wp_get_current_user();
    $user_avatar_url = get_user_meta($currentUser->ID, "user_avatar_url", true);

    if (empty($user_avatar_url)) {
        $user_avatar_url = get_avatar_url($currentUser->ID);
    }

    return $user_avatar_url;
}

function render_post(WP_Post $post)
{
    $abstract = get_post_meta($post->ID, MC_METABOX_ABSTRACT, true);
    $image_url = get_post_meta($post->ID, MC_METABOX_IMAGE, true);
    $tags = get_the_tags();

    $a = 1
    ?>
    <div class=" ">
        <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
            <?php if (!is_numeric($image_url)): ?>
                <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="<?= get_template_directory_uri() . "/assets/images/img" . random_int(1, 3) . ".svg" ?>" alt="blog">
            <?php else: ?>
                <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="<?= wp_get_attachment_url($image_url) ?>" alt="blog">
            <?php endif; ?>
            <div class="p-6 flex-col">
                <h2 class="text-xs title-font font-medium text-gray-400 mb-1">
                    <?php if ($tags): ?>
                        <?php foreach ($tags as $tag): ?>
                            <span><?= $tag->name ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </h2>
                <h1 class="title-font text-lg font-medium text-gray-900 mb-3"><?= $post->post_title ?></h1>
                <p class=" mb-3"><?= $abstract ?></p>
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
    </div>
    <?php
}