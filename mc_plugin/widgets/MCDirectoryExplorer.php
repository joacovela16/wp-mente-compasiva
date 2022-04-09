<?php

class MCDirectoryExplorer extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'mc_cft_explorer',  // Base ID
            'MC - CFT explorer'
        );
    }

    public function widget($args, $instance)
    {
        $query_args['s'] = '';
        $query_args['post_type'] = CFT_DIRECTORY;
        $query_args['posts_per_page'] = 6;
        $query_args['posts_per_archive_page'] = 6;
        $query_args['page'] = 1;
        $query_args['order'] = 'DESC';

        // MODES
        $mode = $_GET['m'] ?? 'latest';
        if ($mode === 'most_readed') {
            $query_args['orderby'] = 'date';
        } elseif ($mode === 'most_commented') {
            $query_args['orderby'] = 'comment_count';
        } else {
            $query_args ['orderby'] = 'date';
        }

        ?>
        <div class="sm:shadow rounded p-3">
            <div class="font-bold p-1 text-md sm:text-lg"><?= __('lastes CFT Professionals') ?></div>

            <div class="flex flex-col sm:flex-row space-x-3 bg-gray-50 rounded items-center p-1 sm:p-3">
                <a id="latest" href="?m=latest" class="border-b-2 <?= $mode === 'latest' ? 'border-blue-500 font-bold' : 'border-transparent' ?> max-w-xs p-1"><?= __('Last posts') ?></a>
                <a id="most_readed" href="?m=most_readed"
                   class="max-w-xs p-1 border-b-2 <?= $mode === 'most_readed' ? 'border-blue-500 font-bold' : 'border-transparent' ?>"><?= __('Most readed') ?></a>
                <a id="most_commented" href="?m=most_commented"
                   class="max-w-xs p-1 border-b-2 <?= $mode === 'most_commented' ? 'border-blue-500 font-bold' : 'border-transparent' ?>"><?= __('Most commented') ?></a>
                <div class="flex-1"></div>
                <a href="/directorio-cft" class="text-blue-500 font-bold underline"><?= __('Explore') ?></a>
            </div>
            <div class="grid md:grid-cols-2 gap-4 mt-5 overflow-hidden">
                <?php
                $query = new WP_Query($query_args);
                $posts = $query->posts;
                foreach ($posts as $post) {
                    render_cft($post);
                }
                ?>
            </div>
        </div>
        <?php
    }

    public function form($instance)
    {
        ?>
        <p>
            This widget displays CFT Directory.
        </p>
        <?php
    }

    public function update($new_instance, $old_instance): array
    {

        return $new_instance;
    }

}
