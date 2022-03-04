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
            $base = array_filter($base, fn($x) => empty($x[MC_LOGGED_REQUIRED]) || $x[MC_LOGGED_REQUIRED] === 'off');
            $post_types = array_unique(
                array_merge(
                    ...array_map(fn($x) => $x[MC_POST_TYPES], $base)
                )
            );

            if (count($post_types) === 0) {
                try {
                    $post_types = ['mc' . random_int(0, 1000)];
                } catch (Exception $e) {
                    $post_types = ['re-re-re-'];
                }
            }
        }

        $query = new WP_Query(['s'=>'']);
        $lib = new MCPermissionLib();
        $lib->pre_get_posts($query);

        $query->set('posts_per_page', 12);
        $query->set('order', 'DESC');

        // MODES
        $mode = $_GET['m'] ?? 'latest';
        if ($mode === 'most_readed') {
            $query->set('orderby', 'date');
        } elseif ($mode === 'most_commented') {
            $query->set('orderby', 'comment_count');
        } else {
            $query->set('orderby', 'date');
        }

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
            if ($query->have_posts()) {
                while ($query->have_posts()) {

                    $query->the_post();
                    $post = get_post();
                    render_post($post);
                }
            }else{
                echo __('empty_content');
            }
            ?>
        </div>
        <?php
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
