<?php

$current_user = wp_get_current_user();
$name = get_user_meta($current_user->ID, MC_NAME, true);
$user_avatar_url = get_user_meta($current_user->ID, MC_AVATAR_URL, true);
$user_avatar_url = $user_avatar_url === "" ? get_avatar_url($current_user->ID) : $user_avatar_url;

?>

<div class="navbar bg-base-100 shadow-lg">
    <div class="navbar-start flex-1">
        <div class="dropdown md:hidden">
            <label tabindex="0" class="btn btn-ghost btn-circle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
            </label>
            <ul class="menu menu-compact dropdown-content mt-3 p-2 shadow bg-base-100 rounded-box w-auto">
                <li>
                    <form action="/" method="get" class="w-auto">
                        <input type="text" placeholder="<?= __('Search') ?>" class="input input-bordered w-auto" name="s">
                    </form>
                </li>
                <?php wp_nav_menu(['theme_location' => 'header-menu', 'menu_class' => 'mc_menu_mobile', 'items_wrap' => '%3$s']); ?>
            </ul>
        </div>
        <a href="/" class="btn btn-ghost normal-case sm:text-xl text-lg">Mente Compasiva</a>
        <div class="hidden md:block">
            <?php wp_nav_menu(['theme_location' => 'header-menu', 'menu_class' => 'mc_menu']); ?>
        </div>
    </div>

    <div class="flex-grow-0 gap-2">
        <div class="form-control hidden sm:block">
            <form action="/" method="get">
                <input type="text" placeholder="<?= __('Search') ?>" class="input input-bordered" name="s">
            </form>
        </div>
        <?php if (is_user_logged_in()): ?>
            <div class="dropdown dropdown-end">
                <div tabindex="0" class="flex flex-row items-center cursor-pointer">
                    <label class="btn btn-ghost btn-circle avatar ">
                        <div class="w-10 rounded-full shadow-lg">
                            <img src="<?= $user_avatar_url ?>"/>
                        </div>
                    </label>
                    <div class="font-bold hidden sm:block"><?= __('My profile') ?></div>
                </div>
                <ul tabindex="0" class="mt-3 p-2 shadow menu menu-compact  dropdown-content bg-base-100 rounded-box w-52">
                    <li>
                        <a class="justify-between" href="<?= site_url('/profile') ?>"><?= __('Profile') ?></a>
                    </li>
                    <li>
                        <a href="<?= wp_logout_url('/') ?>"><?= __('Logout') ?></a>
                    </li>
                </ul>
            </div>
        <?php else: ?>
            <div class="flex-grow-0 cursor-pointer px-3">
                <a href="<?= site_url('/login') ?>" class="font-bold">
                    <?= __('Login/Register') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>