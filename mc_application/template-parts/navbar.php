<?php
$currentUser = wp_get_current_user();
$displayName = $currentUser->display_name;
$avatarUrl = get_avatar_url($currentUser->ID);

$user_avatar_url = get_user_meta($currentUser->ID, "user_avatar_url", true);

if (empty($user_avatar_url)) {
    $user_avatar_url = get_avatar_url($currentUser->ID);
}
?>
<div class="sticky top-0 left-0 w-full z-index-20 shadow-lg shadow-dark-500 bg-white px-2">
    <div class="flex flex-row p-2 items-center">
        <div class="flex-grow-0 cursor-pointer flex flex-row space-x-2">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="24"
                     height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
            </span>
            <span class="font-bold"><?= __("Menu") ?></span>
        </div>
        <div class="flex-1"></div>

        <?php get_template_part("template-parts/filter") ?>

        <div class="flex-1"></div>
        <div class="flex-grow-0 cursor-pointer font-bold">
            <?php if (is_user_logged_in()) { ?>
                <div class="flex flex-row space-x-2 cursor-pointer" @click="profileOn=true">
                    <div><?= $displayName ?></div>
                    <img src="<?= $user_avatar_url ?>" class="w-28px h-28px rounded-full shadow-lg object-cover" alt="User profile" x-ref="navbarAvatar"/>
                </div>
            <?php } else { ?>
                <span> <?= __("login") . "/" . __("register") ?> </span>
            <?php } ?>
        </div>
    </div>


</div>