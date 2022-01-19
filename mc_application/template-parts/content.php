<?php
$post = get_post();
setup_postdata($post);

?>
<div class="mx-auto container py-3">
    <article x-init="loaderOn=false" class="space-y-10">
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