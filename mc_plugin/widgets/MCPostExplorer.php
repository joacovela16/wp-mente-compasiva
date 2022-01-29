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
        if (!$settings ){
            $settings = [MC_PERMISSIONS=>[], MC_DEFAULT_PERMISSION];
        }
//        $b = MCPermissionLib::get_permissions();
//        $a=1;
        ?>
        <div class="p-5 flex flex-row space-x-5 bg-zinc-100">
            <div class="border-b-2 border-blue-500 max-w-xs p-1 font-bold"><?= __('Last posts') ?></div>
            <div class="max-w-xs p-1"><?= __('Most readed') ?></div>
            <div class="max-w-xs p-1"><?= __('Most commented') ?></div>
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
