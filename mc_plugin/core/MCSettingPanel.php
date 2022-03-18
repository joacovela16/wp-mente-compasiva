<?php

class MCSettingPanel
{
    public function init()
    {
        add_action('admin_menu', [$this, 'mc_options_page']);

    }

    public function mc_options_page()
    {

      /*  $hookname = add_menu_page(
            'MC Panel',
            'MC Panel',
            'manage_options',
            'mc_panel',
            [$this, 'mc_options_page_html'],
            null,
            3
        );*/

      //  add_action('load-' . $hookname, [$this, 'mc_options_page_submit']);
    }

    public function mc_options_page_html()
    {
        ?>
        <div class="container mx-auto p-5 mc_plugin_setting_panel">
            <p class="text-lg font-bold"><?php echo esc_html(get_admin_page_title()); ?></p>
        </div>
        <?php
    }

}
