<?php

class MCPermissionNavbar extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'mc_permission_navbar',  // Base ID
            'MC - Permission Navbar'
        );
    }

    public function widget($args, $instance)
    {
        $current_user = wp_get_current_user();
        $user_avatar_url = get_user_meta($current_user->ID, "user_avatar_url", true);
        $user_avatar_url = $user_avatar_url === "" ? get_avatar_url($current_user->ID) : $user_avatar_url;

        $ptype = $_GET["ptype"] ?? "";
        $settings = get_option(MC_SETTING);
        $permissions = $settings ? $settings[MC_PERMISSIONS] : [];

        if (!is_user_logged_in()) {
            $permissions = array_values(array_filter($permissions, fn($x) => !isset($x[MC_LOGGED_REQUIRED])));
        }
        ?>
        <div class="flex md:flex-row flex-col items-center transition-all text-lg bg-white shadow-lg p-1 space-x-2 min-h-[5rem]">
            <div class="flex flex-row items-center ">
                <div class="flex-grow-0 cursor-pointer ">
                    <div class="flex flex-row px-3 items-center">
                        <a href="/" class="flex w-full">
                            <svg width="24" height="24" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 9.5L12 4L21 9.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M19 13V19.4C19 19.7314 18.7314 20 18.4 20H5.6C5.26863 20 5 19.7314 5 19.4V13" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class=" ml-1"><?= __("Home") ?></span>
                        </a>
                    </div>
                </div>

            </div>
            <div class="w-full flex-1 flex flex-row items-center divide-x">
                <?php foreach ($permissions as $item): ?>
                    <?php if (count($item[MC_POST_TYPES] ?? []) > 0): ?>
                        <?php if ($item[MC_ID] === $ptype): ?>
                            <div class="px-1 py-5 border-b-2 border-b-blue-500 cursor-pointer text-center font-bold flex-1">
                                <a class="w-full" href="<?= ("/?s=&ptype=" . $item[MC_ID]) ?>">
                                    <?= $item[MC_NAME] ?>
                                </a>
                            </div>
                        <?php else: ?>
                            <div
                                    class="px-1 py-5 border-b-2 border-b-transparent hover:border-b-blue-500 hover:bg-zinc-100 cursor-pointer transition text-center font-bold flex-1 "
                            >
                                <a
                                        class="w-full"
                                        href="<?= ("/?s=&ptype=" . $item[MC_ID]) ?>"
                                >
                                    <?= $item[MC_NAME] ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="flex flex-row items-center">
                <form action="/" method="get" class="flex flex-row items-center border-gray-200 px-2 py-1 border-1 rounded-full bg-white m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         class="flex-grow-0 text-gray-600"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="10" cy="10" r="7"></circle>
                        <line x1="21" y1="21" x2="15" y2="15"></line>
                    </svg>
                    <input
                            type="text"
                            class="w-full focus:outline-none"
                            autofocus
                            placeholder="<?= __("Search") ?>..." name="s" value="<?= $_GET['s'] ?? '' ?>">
                </form>
                <?php if (is_user_logged_in()): ?>
                    <div class="flex-grow-0 cursor-pointer px-3" title="<?= __('Profile') ?>">
                        <a href="<?= site_url('/profile') ?>">
                            <img src="<?= $user_avatar_url ?>" alt="AV" class="w-[48px] h-[48px] rounded-full shadow-lg border-2">
                        </a>
                    </div>
                <?php else: ?>
                    <div class="flex-grow-0 cursor-pointer px-3">
                        <a href="<?= wp_login_url() ?>" class="font-bold">
                            <?= __('Login/Register') ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public function form($instance)
    {
        ?>
        <p>This widget uses MC Panel settings.</p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {

    }

}