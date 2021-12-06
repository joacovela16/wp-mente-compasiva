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
        $is_logged = is_user_logged_in();
        $query = [
            'post_type' => $source,
            'post_status' => 'publish',
            'posts_per_page' => 10
        ];
        if (!$is_logged){
            $query = array_merge($query, [
                "meta_query"=>[
                    ["key"=>"kind", "compare"=> "NOT EXISTS"]
                ]
            ]);
        }
        $query = new WP_Query($query);
        $posts = $query->get_posts();
        ?>

        <div class="">
            <div class="border-b-blue-500 border-b-width-2">
                <div class="flex-grow-0 text-3xl font-bold">
                    <?= $title ?>
                </div>
            </div>
            <div class="flex flex-row flex-wrap overflow-auto p-4">

                <?php foreach ($posts as $post) {
                    $kind = get_post_meta($post->ID, "kind", true);
                    if ($kind === "person") {
                        $this->render_common_entry($post);
                    } else {
                        $this->render_common_entry($post);
                    }
                }
                ?>

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

    public function render_common_entry(WP_Post $post)
    {
        $source = $post->post_type;
        $author = get_user_by("ID", $post->post_author);
        $image_url = get_post_meta($post->ID, MC_METABOX_IMAGE . "_id", true);
        $abstract = get_post_meta($post->ID, MC_METABOX_ABSTRACT, true);

        ?>
        <div class="rounded-lg shadow-md m-3 overflow-hidden bg-white flex flex-col md:flex-row lg:w-110 w-full h-auto h-auto md:h-50">
            <?php if (!empty($image_url)) { ?>
                <div class="md:max-w-1/3 mx-auto mt-1 md:m-0">
                    <img src="<?= $image_url ?>" alt="" class="w-32 h-32 object-cover w-full  md:h-full rounded-full md:rounded-none">
                </div>
            <?php } ?>
            <div class="flex-1 flex flex-col">
                <div class="font-bold bg-cool-gray-50">
                    <div class="p-3 text-center md:text-left">
                        <?= $post->post_title ?>
                    </div>
                </div>
                <div class="relative overflow-hidden flex-grow-0 md:flex-1">
                    <div class="px-2 text-sm">
                        <?= $abstract ?>
                    </div>
                    <div class="absolute left-0 top-0 w-full h-full bg-gradient-to-t to-transparent from-white via-transparent z-index-0"></div>
                </div>
                <span class="p-2">
                    <a href='<?= get_permalink($post) ?>' class=" cursor-pointer text-sm underline"> <?= __("Read more") ?> ... </a>
                </span>
                <div class="flex flex-row bg-cool-gray-50 text-sm">
                    <div class="p-2"><?= __("By $author->display_name") ?></div>
                    <div class="flex-1"></div>
                    <div class="p-2"><?= get_the_time("F j, Y g:i a", $post) ?></div>
                </div>
            </div>
        </div>
        <?php
    }

}

?>
