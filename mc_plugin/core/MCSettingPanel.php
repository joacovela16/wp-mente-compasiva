<?php

class MCSettingPanel
{
    public function init()
    {
        add_action('admin_menu', [$this, 'mc_options_page']);

    }

    public function mc_options_page()
    {

        $hookname = add_menu_page(
            'MC Panel',
            'MC Panel',
            'manage_options',
            'mc_panel',
            [$this, 'mc_options_page_html'],
            null,
            3
        );

        add_action('load-' . $hookname, [$this, 'mc_options_page_submit']);
    }

    public function mc_options_page_submit()
    {
        $professions = get_option(MC_PROFESSION_OPTIONS, []);
        $profession = $_POST[MC_PROFESSION] ?? null;
        if ($profession) {
            $professions[] = $profession;
        }

        $professions = array_values(array_filter($professions, fn($x) => ($_POST["delete-" . $x] ?? "off") === "off"));
        update_option(MC_PROFESSION_OPTIONS, $professions);
    }

    public function mc_options_page_html()
    {
        $professions = get_option(MC_PROFESSION_OPTIONS, []);
        ?>
        <div class="container mx-auto p-5 mc_plugin_setting_panel space-y-3">
            <p class="text-lg font-bold"><?php echo esc_html(get_admin_page_title()); ?></p>
            <form action="<?= menu_page_url('mc_panel', false) ?>" method="post">
                <div class="space-y-3">
                    <div class="label-text"><?= __('Professions') ?></div>
                    <div class="space-y-3">
                        <input type="text" name="<?= MC_PROFESSION ?>" placeholder="<?= __('Write a new one') ?>" class="">
                        <?php foreach ($professions as $item): ?>
                            <div class="flex flex-row gap-5">
                                <div><?= $item ?></div>
                                <label>
                                    <input type="checkbox" class="" name="delete-<?= $item ?>">
                                    <?= __('delete') ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="btn mt-5 btn-sm btn-primary"><?= __('Save') ?></button>
            </form>
        </div>
        <?php
    }

}
