<?php
$post = get_post();
$settings = get_option(MC_SETTING);
$permissions = $settings[MC_PERMISSIONS] ?? [];
$post_include = [];
$cond = is_user_logged_in() ? fn($x) => $x[MC_LOGGED_REQUIRED] === 'on' : fn($x) => empty($x[MC_LOGGED_REQUIRED]) || $x[MC_LOGGED_REQUIRED] !== 'on';

foreach ($permissions as $item) {
    if ($cond($item)) {
        $post_include = array_merge($post_include, $item[MC_POST_TYPES] ?? []);
    }
}
$allowed = array_exists($post_include, fn($x) => $x === $post->post_type);

if ($allowed):
    setup_postdata($post);
    $tags = get_the_tags();
    ?>
    <div class="mx-auto container mt-8 h-screen" x-init="loaderOn=false">
        <article  class="space-y-10">
            <?php goBack(); ?>
            <header class="font-bold text-3xl border-b-blue-500 border-b-2 p-1 flex flex-row items-center">
                <div><?php the_title() ?></div>
                <div class="flex-1"></div>
                <div class="text-base">
                    <div><?php the_date() ?></div>
                    <div> <?= __("by") . " " . get_the_author() ?></div>
                </div>
            </header>
            <div>
                <?php the_content(); ?>
            </div>

            <?php if (comments_open() || get_comments_number()) : ?>
                <?php comments_template(); ?>
            <?php endif; ?>

        </article>
    </div>
<?php else: ?>
    <div x-init="loaderOn=false" class="h-screen flex place-content-center">
        <div class="my-auto flex flex-row items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="" width="128" height="128" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                 stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <circle cx="12" cy="12" r="9"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div class="font-bold text-lg"><?= __('Content unavailable') ?></div>
        </div>
    </div>
<?php
endif;