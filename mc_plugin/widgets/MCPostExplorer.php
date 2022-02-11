<?php

class MCPostExplorer extends WP_Widget
{
    //public $show_instance_in_rest = true;

    public function __construct()
    {
        parent::__construct(
            'mc_post_explorer',  // Base ID
            'MC - Catalog explorer'
        );
    }


    public function widget($args, $instance)
    {
        $settings = get_option(MC_SETTING);
        $base = $settings[MC_PERMISSIONS] ?? [];
        $user_id = get_current_user_id();

        if (is_user_logged_in()) {
            $user_permissions = get_user_meta($user_id, MC_METABOX_PERMISSION, true);
            $user_permissions = empty($user_permissions) ? [] : $user_permissions;
            $allowed_posts = array_unique(
                array_merge(
                    ...array_values(array_map(fn($x) => $x[MC_POST_TYPES] ?? [], $user_permissions)
                    )
                )
            );
            $post_types = $allowed_posts;
        } else {
            $base = array_filter($base, fn($x) => isset($x[MC_LOGGED_REQUIRED]) && $x[MC_LOGGED_REQUIRED] === 'on');
            $post_types = array_unique(
                array_merge(
                    ...array_map(fn($x) => $x[MC_POST_TYPES], $base)
                )
            );
        }


        $args = [];
        $args["post_type"] = $post_types;
        $args["post_status"] = 'publish';
        $args["posts_per_page"] = 12;
        $args['order'] = 'DESC';

        // MODES
        $mode = $_GET['m'] ?? 'latest';
        if ($mode === 'most_readed') {
            $args['orderby'] = 'date';
        } elseif ($mode === 'most_commented') {
            $args['orderby'] = 'comment_count';
        } else {
            $args['orderby'] = 'date';
        }
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            ?>
            <div class="p-5 flex flex-row space-x-5 bg-zinc-100 rounded">
                <a id="latest" href="?m=latest" class="border-b-2 <?= $mode === 'latest' ? 'border-blue-500 font-bold' : 'border-transparent' ?> max-w-xs p-1"><?= __('Last posts') ?></a>
                <a id="most_readed" href="?m=most_readed"
                   class="max-w-xs p-1 border-b-2 <?= $mode === 'most_readed' ? 'border-blue-500 font-bold' : 'border-transparent' ?>"><?= __('Most readed') ?></a>
                <a id="most_commented" href="?m=most_commented"
                   class="max-w-xs p-1 border-b-2 <?= $mode === 'most_commented' ? 'border-blue-500 font-bold' : 'border-transparent' ?>"><?= __('Most commented') ?></a>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-5">
                <?php
                while ($query->have_posts()) {

                    $query->the_post();
                    $post = get_post();
                    render_post($post);
                }
                ?>
            </div>
            <?php
        }
    }

    public function form($instance)
    {
        ?>
        <p>
            This widget reads settings from <b>MC Panel</b>.
        </p>
        <?php
    }

    public function update($new_instance, $old_instance): array
    {

        return $new_instance;
    }

}
