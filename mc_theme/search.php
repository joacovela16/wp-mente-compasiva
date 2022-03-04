<?php
global
$wp_query;
const TOOK_CFT = 'tookCFT';
const SORTBY = "orderby";
const PAFTER = "after";
const PBEFORE = "before";

$search_fields = ['tag', 'country', TOOK_CFT, SORTBY, PBEFORE, PAFTER];

$tags = get_tags();
$selected_tags = $_GET['tag'] ?? [];
$selected_tags = is_array($selected_tags) ? $selected_tags : [$selected_tags];

$orderby = $_GET[SORTBY] ?? 'date';

$countries = (get_option(MC_SETTING) ?? [])[MC_COUNTRIES] ?? [];
$selected_countries = $_GET['country'] ?? [];
$selected_countries = is_array($selected_countries) ? $selected_countries : [$selected_countries];

get_header();
?>
    <div class="container py-8 mt-40 md:mt-24 mx-auto flex flex-col md:flex-row">
        <form class="space-y-2" method="get">
            <?php foreach ($_GET as $k => $v): ?>
                <?php if (!empty(array_filter($search_fields, fn($x) => $x === $k))): continue; endif; ?>
                <input type="hidden" name="<?= $k ?>" value="<?= get_query_var($k) ?>">
            <?php endforeach; ?>

            <?php if (!isset($_GET['ptype'])): ?>
                <div class="md:mb-0">
                    <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                        <span><?= __('Took CFT') ?></span>

                        <input
                                class="border border-gray-200 rounded py-3 px-4 focus:bg-white focus:border-gray-500"
                                type="checkbox" name="<?= TOOK_CFT ?>" <?= isset($_GET[TOOK_CFT]) ? 'checked' : '' ?>>
                    </label>
                </div>
            <?php endif; ?>
            <div class="md:mb-0">
                <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                    <span><?= __('Category') ?></span>
                    <select class="w-full border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded focus:outline-none focus:bg-white
                        focus:border-gray-500" name="tag[]" multiple>
                        <?php if (!is_wp_error($tags) && is_array($tags)): ?>
                            <?php foreach ($tags as $tag): ?>
                                <option value="<?= $tag->slug ?>" <?= in_array($tag->slug, $selected_tags) ? 'selected' : '' ?> ><?= $tag->name ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </label>
            </div>
            <div class="md:mb-0">
                <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                    <span><?= __('Country') ?></span>
                    <select class="w-full border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded" name="country[]" multiple>
                        <?php foreach ($countries as $c): ?>
                            <option value="<?= $c ?>" <?= in_array($c, $selected_countries) ? 'selected' : '' ?> ><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <div class="md:mb-0">
                <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                    <div><?= __('Published before') ?></div>
                    <input
                            class="appearance-none w-full  text-gray-700 border border-gray-200 rounded py-3 px-4 focus:outline-none focus:bg-white focus:border-gray-500"
                            type="date" name="<?= PBEFORE ?>" value="<?= $_GET[PBEFORE] ?>">
                </label>
            </div>
            <div class="md:mb-0">
                <label class="uppercase  text-gray-700 text-xs font-bold mb-2">
                    <div><?= __('Published after') ?></div>
                    <input
                            class="appearance-none w-full text-gray-700 border border-gray-200 rounded py-3 px-4 focus:outline-none focus:bg-white focus:border-gray-500"
                            type="date" name="<?= PAFTER ?>" value="<?= $_GET[PAFTER] ?>">
                </label>
            </div>
            <div class="md:mb-0">
                <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                    <span><?= __('Sort by') ?></span>
                    <select class="w-full border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded" name="<?= SORTBY ?>">
                        <option <?= $orderby === 'date' ? 'selected' : '' ?> value="date"><?= __('Published date') ?></option>
                        <option <?= $orderby === 'date' ? '' : 'selected' ?> value="author"><?= __('Author name') ?></option>
                    </select>
                </label>
            </div>

            <div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"><?= __('Search') ?></button>
            </div>
        </form>
        <div class="flex-1 p-2 space-y-2 mt-5 sm:mt-0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php

                if (have_posts()) {
                    while (have_posts()): the_post();
                        $post = get_post();
                        render_post($post);
                    endwhile;
                }
                $previous_link = get_previous_posts_link();
                $next_link = get_next_posts_link();
                ?>
            </div>
            <?php if (!(is_null($next_link) && is_null($previous_link))): ?>
                <div class="flex flex-row space-x-2 border rounded p-4 bg-zinc-100">
                    <div><?= $previous_link ?></div>
                    <div><?= $next_link ?></div>
                </div>
            <?php endif; ?>
        </div>

    </div>
<?php
get_footer();