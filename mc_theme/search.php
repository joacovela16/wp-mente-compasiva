<?php

get_header();

if (have_posts()) {

    ?>
    <div class="mt-24 container mx-auto p-5 ">
        <div class="text-2xl border-b-2 border-b-blue-500"><?= __('Results') ?></div>
        <?php

        $date_format = get_option('date_format');
        while (have_posts()) {
            the_post();
            $post = get_post();
            $is_person = get_post_meta($post->ID, MC_KIND, true) === MC_PERSON;
            $is_cft = get_post_meta($post->ID, MC_CFT, true) === 'on';
            $perma = get_the_permalink();

            ?>

            <div class="flex flex-row p-3 rounded items-center hover:bg-gray-100 transition-colors h-24 border-b-2 border-b-blue-500">
                <div class="flex-1 text-lg">
                    <a href="<?= $perma ?>">
                        <?php if ($is_cft): ?>
                            <?= __('CFT Professional') ?>:
                        <?php endif; ?>
                        <?= $post->post_title ?>
                    </a>
                </div>
                <div class="text-right">
                    <div><?= get_the_date($date_format, $post) ?></div>
                    <?php if (!$is_person): ?>
                        <div><?= __("by") . " " . get_the_author() ?></div>
                    <?php endif; ?>
                    <div>
                        <a class="text-blue-500 underline font-bold" href="<?= $perma ?>"><?= __('Read more') ?></a>
                    </div>
                </div>
            </div>

            <?php
        }

        $previous_link = get_previous_posts_link();
        $next_link = get_next_posts_link();
        ?>

        <?php if (!(is_null($next_link) && is_null($previous_link))): ?>
            <div class="flex flex-row space-x-2 border rounded p-4 bg-zinc-100">
                <div><?= $previous_link ?></div>
                <div><?= $next_link ?></div>
            </div>
        <?php endif; ?>

    </div>
    <?php
}

get_footer();