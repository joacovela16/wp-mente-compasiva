<?php

class MC_Last_Post extends WP_Widget
{
    //public $show_instance_in_rest = true;

    public function __construct()
    {
        parent::__construct(
            'mc_last_post',  // Base ID
            'Mente Compasiva - Últimos post'
        );
    }


    public function widget($args, $instance)
    {
        $title = $instance['title'];
        $source = $instance['source'];
        $query = new WP_Query([
            'post_type' => $source,
            'post_status' => 'publish',
            'posts_per_page' => 5
        ]);
        $posts = $query->get_posts();
        ?>

        <div class="">
            <div class="flex flex-row">
                <div class="flex-grow-0 text-3xl font-bold">
                    <?= $title ?>
                </div>
                <div class="flex-1"></div>
                <div class="flex-grow-0 cursor-pointer hover:underline font-bold">

                </div>
            </div>
            <div class="space-x-2 flex flex-row h-50 overflow-auto p-4">

                <?php foreach ($posts as $post) { ?>
                    <div
                            class="transition-all shadow-dark-500 ring-1 ring-gray-100 shadow-lg p-4 rounded-lg overflow-hidden relative w-100 hover:ring-blue-500 cursor-pointer"
                            onclick="location.hash='#/post/<?= $source ?>/<?= $post->ID ?>'"
                    >
                        <div class="absolute left-0 top-0 w-full h-full bg-gradient-to-t to-transparent from-white via-transparent z-index-0"></div>
                        <div class="flex flex-row z-index-20">
                            <div class="text-lg font-bold"><?= $post->post_title ?></div>
                            <div class="flex-1"></div>
                            <a class="cursor-pointer z-index-10" href="#/post/<?= $source ?>/<?= $post->ID ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     stroke-width="2"
                                     stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M11 7h-5a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-5"></path>
                                    <line x1="10" y1="14" x2="20" y2="4"></line>
                                    <polyline points="15 4 20 4 20 9"></polyline>
                                </svg>
                            </a>
                        </div>
                        <div class="overflow-hidden p-1">
                            <?= $post->post_content ?>
                        </div>

                    </div>
                <?php } ?>

            </div>
        </div>
        <?php

    }

    public function form($instance)
    {

        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('', 'text_domain');
        $post = get_post_types(["public" => true]);
        $selectorName = esc_attr($this->get_field_name("source"));

        ?>
        <p>
            <label><?php echo esc_html__('Título:'); ?></label>
            <input name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label>Fuente</label>
            <select name="<?= $selectorName ?>" value="<?= $instance['source'] ?>">
                <?php

                foreach ($post as $item) {
                    echo "<option value='$item'>$item</option>";
                }

                ?>
            </select>
        </p>
        <?php

    }

    public function update($new_instance, $old_instance)
    {

        $instance = array();

        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['text'] = (!empty($new_instance['text'])) ? $new_instance['text'] : '';
        $instance['source'] = (!empty($new_instance['source'])) ? $new_instance['source'] : '';
        return $instance;
    }

}

?>
