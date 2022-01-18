<?php
global $wp_query;
const SORTBY = "sortby";
const PAFTER = "pafter";
const PBEFORE = "pbefore";

$search_fields = [PBEFORE, PAFTER, SORTBY];

get_header();
?>
    <div class="container px-5 py-24 mx-auto">
        <div class="border-b-2 p-2">
            <p class="font-bold text-gray-700"><?= __('Filters') ?></p>
            <form action="" class="p-1 flex flex-col sm:flex-row items-end" method="get">
                <?php foreach ($_GET as $k => $v)               : ?>
                    <?php if (!empty(array_filter($search_fields, fn($x) => $x === $k))): continue; endif; ?>
                    <input type="hidden" name="<?= $k ?>" value="<?= $v ?>">
                <?php endforeach; ?>
                <div class=" px-3 mb-6 md:mb-0">
                    <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                        <div><?= __('Published before') ?></div>
                        <input
                                class="appearance-none max-w-80 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 focus:outline-none focus:bg-white focus:border-gray-500"
                                type="date" name="<?= PBEFORE ?>" value="<?= $_GET[PBEFORE] ?>">
                    </label>
                </div>
                <div class=" px-3 mb-6 md:mb-0">
                    <label class="uppercase  text-gray-700 text-xs font-bold mb-2">
                        <div><?= __('Published after') ?></div>
                        <input
                                class="appearance-none max-w-80 bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 focus:outline-none focus:bg-white focus:border-gray-500"
                                type="date" name="<?= PAFTER ?>" value="<?= $_GET[PAFTER] ?>">
                    </label>
                </div>
                <div class=" px-3 mb-6 md:mb-0">
                    <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                        <div><?= __('Sort be') ?></div>
                        <select class="max-w-80 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded focus:outline-none focus:bg-white
                        focus:border-gray-500" name="<?= SORTBY ?>">
                            <option><?= __('Published date') ?></option>
                            <option><?= __('Author name') ?></option>
                        </select>
                    </label>
                </div>
                <div>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"><?= __('Search') ?></button>
                </div>
            </form>
        </div>
        <div class="flex flex-wrap ">
            <?php

            if (have_posts()) {
                while (have_posts()): the_post();

                    $post = get_post();
                    $abstract = get_post_meta($post->ID, MC_METABOX_ABSTRACT, true);
                    $image_url = get_post_meta($post->ID, MC_METABOX_IMAGE, true);
                    ?>
                    <div class="p-4 md:w-1/3">
                        <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                            <?php if (!is_numeric($image_url)): ?>
                                <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="<?= get_template_directory_uri()."/assets/images/img".random_int(1,3).".svg" ?>" alt="blog">
                            <?php else: ?>
                                <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="<?= wp_get_attachment_url($image_url) ?>" alt="blog">
                            <?php endif; ?>
                            <div class="p-6 flex-col">
                                <h2 class="text-xs title-font font-medium text-gray-400 mb-1"><?= __('Title') ?></h2>
                                <h1 class="title-font text-lg font-medium text-gray-900 mb-3"><?= $post->post_title ?></h1>
                                <p class=" mb-3"><?= $abstract ?></p>
                                <div class="flex items-center flex-wrap">
                                    <a class="text-blue-500 inline-flex items-center md:mb-2 lg:mb-0">
                                        <?= __('Learn More') ?>
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
                                        1.2K
                                    </span>
                                    <span class="text-gray-400 inline-flex items-center leading-none text-sm">
                                        <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                                        </svg>
                                        6
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endwhile;

            }
            ?>
        </div>
        <div class="flex flex-row space-x-2">
            <div><?= get_previous_posts_link() ?></div>
            <div><?= get_next_posts_link() ?></div>
        </div>
    </div>
<?php
get_footer();