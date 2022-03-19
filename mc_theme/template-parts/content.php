<?php
$post = get_post();
$settings = get_option(MC_SETTING);
$permissions = $settings[MC_PERMISSIONS] ?? [];
$post_include = [];
/*$cond = is_user_logged_in() ? fn($x) => $x[MC_LOGGED_REQUIRED] === 'on' : fn($x) => empty($x[MC_LOGGED_REQUIRED]) || $x[MC_LOGGED_REQUIRED] !== 'on';

foreach ($permissions as $item) {
    if ($cond($item)) {
        $post_include = array_merge($post_include, $item[MC_POST_TYPES] ?? []);
    }
}*/
$allowed = true; //array_exists($post_include, fn($x) => $x === $post->post_type);

setup_postdata($post);
$tags = get_the_tags();
$is_person = get_post_meta($post->ID, MC_KIND, true) === MC_PERSON;
?>
    <div class="mx-auto container md:mt-24 mt-48 h-screen " x-init="loaderOn=false">
        <?php
        if ($is_person):

            $image_url = get_post_meta($post->ID, MC_AVATAR_URL, true);
            $image_url = $image_url === "" ? get_avatar_url(0) : $image_url;

            $abstract = get_post_meta($post->ID, MC_ABSTRACT, true);
            $details = get_post_meta($post->ID, MC_USER_DETAILS, true);
            $location = get_post_meta($post->ID, MC_CITY, true);
            $country = get_post_meta($post->ID, MC_COUNTRY, true);
            $email = get_post_meta($post->ID, MC_EMAIL, true);
            $website = get_post_meta($post->ID, MC_WEBSITE, true);
            $phone = get_post_meta($post->ID, MC_PHONE, true);
            $cft_when_where = get_post_meta($post->ID, MC_CFT_WHEN_WHERE, true);
            $works_with = array_map(fn($x) => __(ucfirst($x)), get_post_meta($post->ID, MC_WORKS_WITH));

            ?>
            <div class="mx-auto w-full max-w-4xl card shadow-lg pb-10 gap-2 rounded-box px-4">
                <img class="mx-auto rounded-full shadow-lg shadow-black ring-8 ring-blue-500 w-64 m-3" src="<?= $image_url ?>" alt="profile image">
                <div class="text-5xl text-center mt-4"><?= $post->post_title ?></div>
                <div class="text-lg text-center mt-4">
                    <span class="font-bold"><?= __('Location') ?>: </span>
                    <span><?= $location . ', ' . $country ?></span>
                </div>

                <div class="text-center ">
                    <span class="font-bold"><?= __('Works with') ?>:</span>
                    <span>
                        <?php if (empty($works_with)): ?>
                            <?= __('undeclared') ?>
                        <?php else: ?>
                            <?= implode(", ", $works_with) ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="text-center ">
                    <span class="font-bold"><?= __('cft_when_and_where') ?>:</span>
                    <span>
                        <?php if (empty($cft_when_where)): ?>
                            <?= __('undeclared') ?>
                        <?php else: ?>
                            <?= $cft_when_where ?>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="italic">
                    <?= $abstract ?>
                </div>
                <div class="mx-auto mb-5">
                    <div class="flex flex-col md:flex-row divide-x-2 flex-1 items-center">
                        <?php if (nonEmpty($phone)): ?>
                            <div class="flex-row flex flex-1 p-3 space-x-3 items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                                </svg>
                                <span><?= $phone ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="flex-row flex flex-1 p-3 space-x-3 items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                <polyline points="3 7 12 13 21 7"></polyline>
                            </svg>
                            <span>
                                <a href="mailto:<?= $email ?>"><?= $email ?></a>
                            </span>
                        </div>
                        <div class="flex-row flex flex-1 p-3 space-x-3 items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-dribbble" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                 stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M9 3.6c5 6 7 10.5 7.5 16.2"></path>
                                <path d="M6.4 19c3.5 -3.5 6 -6.5 14.5 -6.4"></path>
                                <path d="M3.1 10.75c5 0 9.814 -.38 15.314 -5"></path>
                            </svg>
                            <span>
                                <a href="<?= $website ?>" target="_blank"><?= $website ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="space-y-10">
                <header class="font-bold text-3xl border-b-blue-500 border-b-2 p-1 flex flex-row items-center gap-2">
                    <svg title="<?= __('Back') ?>" onclick="history.back(); return false;" class="cursor-pointer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                         fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1"></path>
                    </svg>

                    <div><?php the_title() ?></div>
                    <div class="flex-1"></div>
                    <div class="text-base">
                        <div><?php the_date() ?></div>
                        <div> <?= __("by") . " " . get_the_author() ?></div>
                    </div>
                </header>
                <div class="p-5">
                    <?php the_content(); ?>
                </div>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <?php comments_template(); ?>
                <?php endif; ?>
            </div>

        <?php endif; ?>
    </div>

<?php
