<?php
$post = get_post();
setup_postdata($post);

?>
    <div class="mx-auto container md:mt-24 mt-48 h-screen " x-init="loaderOn=false">
        <div class="space-y-10">
            <header class="font-bold text-3xl border-b-blue-500 border-b-2 p-1 flex flex-row items-center gap-2">
                <svg title="<?= __('Back') ?>" onclick="history.back(); return false;" class="cursor-pointer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <polyline points="15 6 9 12 15 18"></polyline>
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
    </div>
<?php
