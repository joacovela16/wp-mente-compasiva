<?php

global $wp_query;
const SORTBY = "orderby";
const PAFTER = "after";
const PBEFORE = "before";

$search_fields = [MC_GENDER, MC_COUNTRY, MC_CITY, SORTBY, PBEFORE, PAFTER, 's', 'post_type'];

$orderby = $_GET[SORTBY] ?? '';

$selected_countries = $_GET[MC_COUNTRY] ?? [];
$selected_countries = is_array($selected_countries) ? $selected_countries : [$selected_countries];
$gender = $_GET[MC_GENDER] ?? [];
$work_with = $_GET[MC_WORKS_WITH] ?? [];

get_header();
?>
    <div class="container py-4 mt-40 md:mt-24 mx-auto flex flex-col md:flex-row space-x-3" x-init="loaderOn=false">
        <form class="" method="get">
            <?php foreach ($_GET as $k => $v): ?>
                <?php if (!empty(array_filter($search_fields, fn($x) => $x === $k))): continue; endif; ?>
                <input type="hidden" name="<?= $k ?>" value="<?= get_query_var($k) ?>">
            <?php endforeach; ?>

            <div class="form-control w-full max-w-xs">
                <label class="label">
                    <span class="label-text"><?= __('Search') ?></span>
                </label>
                <input type="text" class="input input-bordered w-full max-w-xs" name="s" value="<?= $_GET['s'] ?? '' ?>">
            </div>
            <div class="form-control w-full max-w-xs">
                <label class="label">
                    <span class="label-text"><?= __('Country') ?></span>
                </label>
                <input type="text" class="input input-bordered  w-full max-w-xs" name="<?= MC_COUNTRY ?>" value="<?= $_GET[MC_COUNTRY] ?? '' ?>">
            </div>

            <div class="form-control w-full max-w-xs">
                <label class="label">
                    <span class="label-text"><?= __('City') ?></span>
                </label>
                <input type="text" class="input input-bordered  w-full max-w-xs" name="<?= MC_CITY ?>" value="<?= $_GET[MC_CITY] ?? '' ?>">
            </div>

            <div class="form-control w-full max-w-xs">
                <label class="label">
                    <span class="label-text"><?= __('Gender') ?></span>
                </label>
                <select class="select select-bordered w-full max-w-xs h-18 overflow-hidden " name="<?= MC_GENDER ?>[]" multiple>
                    <option value="female" <?= in_array("female", $gender) ? 'selected' : '' ?>><?= __('Female') ?></option>
                    <option value="male" <?= in_array("male", $gender) ? 'selected' : '' ?>><?= __('Male') ?></option>
                </select>
            </div>

            <div class="form-control w-full max-w-xs">
                <label class="label">
                    <span class="label-text"><?= __('Works with') ?></span>
                </label>
                <select class="select select-bordered w-full max-w-xs h-auto " name="<?= MC_WORKS_WITH ?>[]" multiple>
                    <option value="children" <?= in_array("children", $work_with) ? 'selected' : '' ?>><?= __('Children') ?></option>
                    <option value="teenager" <?= in_array("teenager", $work_with) ? 'selected' : '' ?>><?= __('Teenager') ?></option>
                    <option value="adult" <?= in_array("adult", $work_with) ? 'selected' : '' ?>><?= __('Adult') ?></option>
                    <option value="couple" <?= in_array("couple", $work_with) ? 'selected' : '' ?>><?= __('Couple') ?></option>
                </select>
            </div>

            <div class="form-control w-full max-w-xs">
                <label class="label">
                    <span class="label-text"><?= __('Published before') ?></span>
                </label>
                <input type="date" class="input input-bordered  w-full max-w-xs" name="<?= PBEFORE ?>" value="<?= $_GET[PBEFORE] ?? '' ?>">
            </div>

            <div class="form-control w-full max-w-xs">
                <label class="label">
                    <span class="label-text"><?= __('Published after') ?></span>
                </label>
                <input type="date" class="input input-bordered  w-full max-w-xs" name="<?= PAFTER ?>" value="<?= $_GET[PAFTER] ?? '' ?>">
            </div>

            <select class="mt-3 select select-bordered w-full max-w-xs h-18 overflow-hidden" name="<?= SORTBY ?>">
                <option disabled <?= $orderby === '' ? 'selected' : '' ?>><?= __('Sort by') ?></option>
                <option <?= $orderby === 'date' ? 'selected' : '' ?> value="date"><?= __('Published date') ?></option>
                <option <?= $orderby === 'author' ? 'selected' : '' ?> value="author"><?= __('Author name') ?></option>
            </select>

            <button class="mt-3 btn btn-primary"><?= __('Search') ?></button>
        </form>
        <div class="flex-1 p-2 space-y-2 mt-5 sm:mt-0">
            <?php
            $previous_link = get_previous_posts_link();
            $next_link = get_next_posts_link();
            ?>

            <div class="space-y-2">
                <?php
                if (have_posts()) {
                    while (have_posts()): the_post();
                        $post = get_post();
                        render_cft($post);
                    endwhile;
                }
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