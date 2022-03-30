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
        $professions_option = get_option(MC_PROFESSION_OPTIONS, []);
        $works_with_option  = get_option(MC_WORKS_WITH, []);

        $professions = $_POST[MC_PROFESSION] ?? null;
        $works_with = $_POST[MC_WORKS_WITH] ?? null;

        if (!empty($professions)){
            $professions_option[] = $professions;
        }

        if (!empty($works_with)){
            $works_with_option[] = $works_with;
        }

        $professions = array_values(array_filter($professions_option, fn($x) => ($_POST["delete-pro-" . $x] ?? "off") === "off"));
        $works_with = array_values(array_filter($works_with_option, fn($x) => ($_POST["delete-ww-" . $x] ?? "off") === "off"));

        update_option(MC_PROFESSION_OPTIONS, $professions);
        update_option(MC_WORKS_WITH, $works_with);
    }

    public function mc_options_page_html()
    {
        $professions = get_option(MC_PROFESSION_OPTIONS, []);
        $works_with = get_option(MC_WORKS_WITH, []);
        ?>
        <div class="container mx-auto p-5 mc_plugin_setting_panel space-y-3">
            <p class="text-lg font-bold"><?php echo esc_html(get_admin_page_title()); ?></p>
            <form action="<?= menu_page_url('mc_panel', false) ?>" method="post" class="space-y-2">
                <div class="space-y-3 shadow-lg rounded p-3 bg-white">
                    <div class="label-text"><?= __('Professions') ?></div>
                    <div class="space-y-3">
                        <input type="text" name="<?= MC_PROFESSION ?>" placeholder="<?= __('Write a new one') ?>" class="">
                        <?php foreach ($professions as $item): ?>
                            <div class="gap-5 flex flex-row">
                                <label>
                                    <input type="checkbox" class="" name="delete-pro-<?= $item ?>">
                                    <?= __('delete') ?>
                                </label>
                                <div><?= $item ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="space-y-3 shadow-lg rounded p-3 bg-white">
                    <div class="label-text"><?= __('Works with') ?></div>
                    <div class="space-y-3">
                        <input type="text" name="<?= MC_WORKS_WITH ?>" placeholder="<?= __('Write a new one') ?>" class="">
                        <?php foreach ($works_with as $item): ?>
                            <div class="flex flex-row gap-5">
                                <label>
                                    <input type="checkbox" class="" name="delete-ww-<?= $item ?>">
                                    <?= __('delete') ?>
                                </label>
                                <div><?= $item ?></div>
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
