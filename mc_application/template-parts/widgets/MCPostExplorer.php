<?php

class MCPostExplorer extends WP_Widget
{
    //public $show_instance_in_rest = true;

    public function __construct()
    {
        parent::__construct(
            'mc_post_explorer',  // Base ID
            'Mente Compasiva - Explorador de catálogo'
        );
    }


    public function widget($args, $instance)
    {
//        $selections = $instance['selections'] ?? [];
        $is_logged = is_user_logged_in();
        $tab_items = $is_logged ? ['All', 'Resource', 'Person'] : ['All'];
        ?>
        <div x-data="$store.explorer_widget('<?= admin_url('admin-ajax.php') ?>')" x-init="doCall(0,'All')" class="space-y-10">
            <div class="flex">
                <div class="flex-1"></div>
                <div class="flex flex-row rounded-full overflow-hidden rounded shadow  ">
                    <?php foreach ($tab_items as $key => $item): ?>
                        <div
                                :class="selectedItem===<?= $key ?> ? 'text-white bg-blue-500': 'hover:bg-gray-300 hover:font-bold'"
                                class="p-2 cursor-pointer w-50 text-center"
                                @click="select(<?= $key ?>, '<?= $item ?>')"
                        >
                            <span><?= $item ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="flex-1"></div>
            </div>
            <div class="grid lg:grid-cols-3 gap-3" x-html="contents">
            </div>
            <div class="flex justify-center">
                <div x-show="hasNext" class="bg-blue-500 p-2 rounded text-white cursor-pointer" @click="showMore()">
                    <?= __('Show more') ?>
                </div>
                <div x-show="!hasNext"><?= __('No more results') ?></div>
            </div>
            <div></div>
        </div>
        <?php

    }

    public function form($instance)
    {

        $post = get_post_types(["public" => true]);
        $post_name = array_values($post);

        $selections = $instance['selections'] ?? [];
        $selectionAsJson = esc_html(wp_json_encode($selections));
        $fieldName = esc_attr($this->get_field_name('selections'));
        ?>
        <?=
        div(['x-data' => "\$store.explorer_widget_editor($selectionAsJson, '$fieldName')", 'class' => 'space-y-3'],
            div(['class' => 'space-y-5'],
                el('template', ['x-for' => '(selection, index) in selections '],
                    div(['class' => 'space-y-5 border-1 rounded p-2', 'x-show' => '!selection.hidden'],
                        input([
                            'type' => 'checkbox',
                            ':id' => "fieldName + '_id_'+index",
                            ':name' => "fieldName+'['+index+'][hidden]'",
                            "@change" => 'removeItem(index)',
                            "class" => '!hidden'
                        ],
                        ),
                        field([
                            'label' => __('Title'),
                            'content' => input([
                                "class" => 'appearance-none outline-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg',
                                'x-model' => 'selection.title',
                                ':name' => "fieldName+'['+index+'][title]'"
                            ])
                        ]),
                        div(['class' => ''],
                            span(['class' => 'font-bold'], __('Content')),
                            div(['class' => 'flex items-center space-x-3'],
                                ...array_map(fn($item) => el('label', [],
                                    input(
                                        ['value' => $item,
                                            'type' => 'checkbox',
                                            'x-model' => 'selection.post',
                                            ':name' => "fieldName+'['+index+'][post][]'"
                                        ]
                                    ),
                                    span([], __($item)),
                                ), $post_name)
                            ),
                        ),
                        el('label', [':for' => "fieldName+'_id_'+index"],
                            div([
                                'class' => 'transition-all bg-red-500 text-white p-1 rounded shadow-lg inline-block w-25 text-center cursor-pointer text-sm',
                            ], __('Delete'))
                        )
                    )
                ),
            ),
            div(
                [
                    'class' => 'transition-all bg-blue-500 text-white p-2 rounded shadow-lg inline-block w-35 text-center cursor-pointer',
                    '@click' => 'addSelection()'
                ],
                __('Add')
            )
        );
    }

    public function update($new_instance, $old_instance)
    {

        $instance = array();

        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['text'] = (!empty($new_instance['text'])) ? $new_instance['text'] : '';
        $instance['source'] = (!empty($new_instance['source'])) ? $new_instance['source'] : '';
        $instance['selections'] = array_filter($new_instance['selections'] ?? [], function ($x) {
            return ($x['hidden'] ?? '') !== 'on';
        });
        return $instance;
    }

    public function render_common_entry(WP_Post $post)
    {
        $author = get_user_by("ID", $post->post_author);
        $image_url = get_post_meta($post->ID, MC_METABOX_IMAGE . "_id", true);
        $abstract = get_post_meta($post->ID, MC_METABOX_ABSTRACT, true);

        ?>
        <div class="rounded-lg shadow-black shadow-md m-3 overflow-hidden bg-white flex flex-col md:flex-row lg:w-110 w-full h-auto h-auto md:h-50 ">
            <?php if (!empty($image_url)) { ?>
                <div class="md:max-w-1/3 mx-auto mt-1 md:m-0">
                    <img src="<?= $image_url ?>" alt="" class="w-32 h-32 object-cover w-full  md:h-full rounded-full md:rounded-none">
                </div>
            <?php } ?>
            <div class="flex-1 flex flex-col">
                <div class="font-bold bg-cool-gray-100">
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
                <div class="flex flex-row bg-cool-gray-100 text-sm">
                    <div class="p-2"><?= __("By $author->display_name") ?></div>
                    <div class="flex-1"></div>
                    <div class="p-2"><?= get_the_time("F j, Y g:i a", $post) ?></div>
                </div>
            </div>
        </div>
        <?php
    }
}
