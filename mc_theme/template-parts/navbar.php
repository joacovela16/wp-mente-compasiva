<?php

$current_user = wp_get_current_user();
$user_avatar_url = get_user_meta($current_user->ID, MC_AVATAR_URL, true);
$user_avatar_url = $user_avatar_url === "" ? get_avatar_url($current_user->ID) : $user_avatar_url;

?>
<div class="fixed top-0 left-0 w-full z-20 bg-white shadow-lg flex flex-col  md:flex-row items-center space-x-2 px-2">
    <a class="flex flex-row space-x-1" href="/">
        <svg width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <polyline points="5 12 3 12 12 3 21 12 19 12"></polyline>
            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
            <rect x="10" y="12" width="4" height="4"></rect>
        </svg>
        <div><?= __('Home') ?></div>
    </a>
    <?php
    wp_nav_menu([
            'theme_location' => 'header-menu',
    'menu_class'=>'mc_menu'
    ]);

    ?>
    <div class="flex-1  sm:visible"></div>
    <form action="/" method="get" class=" sm:flex-grow-0 flex flex-row items-center border-gray-200 px-2 py-1 border-1 rounded-full bg-white m-0">
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
                placeholder="<?= __("Search") ?>..." name="s">
    </form>
    <?php if (is_user_logged_in()): ?>
        <div class="flex-grow-0 cursor-pointer px-3" title="<?= __('Profile') ?>">
            <a href="<?= site_url('/profile') ?>" class="flex flex-row items-center space-x-2">
                <span><?= __('Profile') ?></span>
                <img src="<?= $user_avatar_url ?>" alt="AV" class="w-[48px] min-w-[48px] h-[48px] rounded-full shadow-lg border-2">
            </a>
        </div>
    <?php else: ?>
        <div class="flex-grow-0 cursor-pointer px-3">
            <a href="<?= site_url('/login') ?>" class="font-bold">
                <?= __('Login/Register') ?>
            </a>
        </div>
    <?php endif; ?>
</div>
