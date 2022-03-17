<?php

global $wp_query;
const TOOK_CFT = 'tookCFT';
const SORTBY = "orderby";
const PAFTER = "after";
const PBEFORE = "before";

$search_fields = ['tag', 'gender', 'country', 'city', TOOK_CFT, SORTBY, PBEFORE, PAFTER, 's', 'post_type'];

$tags = get_tags();
$selected_tags = $_GET['tag'] ?? [];
$selected_tags = is_array($selected_tags) ? $selected_tags : [$selected_tags];

$orderby = $_GET[SORTBY] ?? 'date';

$countries = (get_option(MC_SETTING) ?? [])[MC_COUNTRIES] ?? [];
$selected_countries = $_GET['country'] ?? [];
$selected_countries = is_array($selected_countries) ? $selected_countries : [$selected_countries];
$gender = $_GET['gender'] ?? [];
$work_with = $_GET['work_with'] ?? [];

get_header();
?>
    <div class="container py-8 mt-40 md:mt-24 mx-auto flex flex-col md:flex-row space-x-3" x-init="loaderOn=false">
        <form class="space-y-2" method="get">
            <?php foreach ($_GET as $k => $v): ?>
                <?php if (!empty(array_filter($search_fields, fn($x) => $x === $k))): continue; endif; ?>
                <input type="hidden" name="<?= $k ?>" value="<?= get_query_var($k) ?>">
            <?php endforeach; ?>
            <div class="md:mb-0">
                <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                    <span><?= __('Search') ?></span>
                    <input type="text" class="w-full border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded" name="s" value="<?= $_GET['s'] ?? '' ?>" autofocus>
                </label>
            </div>
            <div class="md:mb-0">
                <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                    <span><?= __('Country') ?></span>
                    <input type="text" class="w-full border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded" name="country" value="<?= $_GET['country'] ?? '' ?>">
                </label>
            </div>
            <div class="md:mb-0">
                <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                    <span><?= __('City') ?></span>
                    <input type="text" class="w-full border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded" name="city" value="<?= $_GET['city'] ?? '' ?>">
                </label>
            </div>
            <div class="md:mb-0">
                <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                    <span><?= __('Gender') ?></span>
                    <select class="w-full" name="gender[]" multiple>
                        <option value="female" <?= in_array("female", $gender) ? 'selected' : '' ?>><?= __('Female') ?></option>
                        <option value="male" <?= in_array("male", $gender) ? 'selected':'' ?>><?= __('Male') ?></option>
                    </select>
                </label>
            </div>

            <div class="md:mb-0">
                <label class="uppercase text-gray-700 text-xs font-bold mb-2">
                    <span><?= __('Works with') ?></span>
                    <select class="w-full" name="works_with[]" multiple>
                        <option value="children" <?= in_array("children", $work_with) ? 'selected' : '' ?>><?= __('Children') ?></option>
                        <option value="teenager" <?= in_array("teenager", $work_with) ? 'selected' : '' ?>><?= __('Teenager') ?></option>
                        <option value="adult" <?= in_array("adult", $work_with) ? 'selected' : '' ?>><?= __('Adult') ?></option>
                        <option value="couple" <?= in_array("couple", $work_with) ? 'selected' : '' ?>><?= __('Couple') ?></option>
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
            <div class="space-y-2">
                <?php

                if (have_posts()) {
                    while (have_posts()): the_post();
                        $post = get_post();
                        render_cft($post);
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