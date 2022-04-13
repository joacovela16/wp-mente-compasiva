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
        $works_with_option = get_option(MC_WORKS_WITH, []);
        $language_option = get_option(MC_LANGUAGE, []);

        $professions = $_POST[MC_PROFESSION] ?? null;
        $works_with = $_POST[MC_WORKS_WITH] ?? null;
        $language = $_POST[MC_LANGUAGE] ?? null;

        if (!empty($professions)) {
            $professions_option[] = $professions;
        }

        if (!empty($works_with)) {
            $works_with_option[] = $works_with;
        }

        if (!empty($language)) {
            $language_option[] = $language;
        }

        $professions = array_values(array_filter($professions_option, fn($x) => ($_POST["delete-pro-" . $x] ?? "off") === "off"));
        $works_with = array_values(array_filter($works_with_option, fn($x) => ($_POST["delete-ww-" . $x] ?? "off") === "off"));
        $language = array_values(array_filter($language_option, fn($x) => ($_POST["delete-lang-" . $x] ?? "off") === "off"));

        update_option(MC_PROFESSION_OPTIONS, $professions);
        update_option(MC_WORKS_WITH, $works_with);
        update_option(MC_LANGUAGE, $language);
    }

    public function mc_options_page_html()
    {
        $professions = get_option(MC_PROFESSION_OPTIONS, []);
        $works_with = get_option(MC_WORKS_WITH, []);
        $languages = get_option(MC_LANGUAGE, []);

        $generateCount = $_POST[MC_PASSWORD_GEN] ?? 0;
        $generateCount = intval($generateCount);
        $tokenGen = [];

        if ($generateCount > 0) {
            $keyService = new MCKeyService();
            $now = time();
            $pending = get_option(MC_PASSWORD_GEN, []);
            $pending = array_values(array_filter($pending, fn($x) => $x['created'] + MC_MAX_TIMEOUT > $now));

            for ($i = 0; $i < $generateCount; $i++) {
                $t = $keyService->generate_recovery_mode_token();
                $k = $keyService->generate_and_store_recovery_mode_key($t);
                $pending[] = ['created' => $now, 'key' => $k, 'token' => $t];
                $tokenGen[] = site_url('/register/' . $k);
            }
            update_option(MC_PASSWORD_GEN, $pending);
        }
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
                    <button class="btn mt-5 btn-sm btn-primary"><?= __('Save') ?></button>
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
                    <button class="btn mt-5 btn-sm btn-primary"><?= __('Save') ?></button>
                </div>

                <div class="space-y-3 shadow-lg rounded p-3 bg-white">
                    <div class="label-text"><?= __('Language') ?></div>
                    <div class="space-y-3">
                        <input type="text" name="<?= MC_LANGUAGE ?>" placeholder="<?= __('Write lenguages') ?>" class="">
                        <?php foreach ($languages as $item): ?>
                            <div class="flex flex-row gap-5">
                                <label>
                                    <input type="checkbox" class="" name="delete-lang-<?= $item ?>">
                                    <?= __('delete') ?>
                                </label>
                                <div><?= $item ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="btn mt-5 btn-sm btn-primary"><?= __('Save') ?></button>
                </div>

                <div class="space-y-3 shadow-lg rounded p-3 bg-white">
                    <div class="label-text"><?= __('Invitation link generator') ?></div>
                    <div class="flex flex-col space-y-2">
                        <div>
                            <label>
                                <input class="w-1/3" type="number" min="0" maxlength="50" name="<?= MC_PASSWORD_GEN ?>" placeholder="<?= __('Who many do you want generate?') ?>">
                            </label>
                        </div>
                        <div class="flex flex-row space-x-2 max-h-52 ">

                            <div class="flex-1 overflow-auto relative">
                                <?php if ($generateCount > 0): ?>
                                    <div title="<?= __('Copy') ?>">
                                        <svg
                                                class="absolute right-0 top-0 cursor-pointer"
                                                onclick="navigator.clipboard && navigator.clipboard.writeText('<?= implode('\n', $tokenGen) ?>')"

                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                stroke-width="2"
                                                stroke="currentColor"
                                                fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                                            <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                                            <line x1="9" y1="12" x2="9.01" y2="12"></line>
                                            <line x1="13" y1="12" x2="15" y2="12"></line>
                                            <line x1="9" y1="16" x2="9.01" y2="16"></line>
                                            <line x1="13" y1="16" x2="15" y2="16"></line>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <div class=" h-full ">
                                    <?php foreach ($tokenGen as $item): ?>
                                        <div>
                                            <a href="<?= $item ?>" target="_blank"><?= $item ?></a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn mt-5 btn-sm btn-primary"><?= __('Generate') ?></button>
                </div>

            </form>
        </div>
        <?php
    }

}
