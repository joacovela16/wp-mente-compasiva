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

        $settings = get_option(MC_SETTING);
        $permissions = $settings[MC_PERMISSIONS];

        if (!is_user_logged_in()) {
            $permissions = array_values(array_filter($permissions, fn($x) => $x[MC_LOGGED_REQUIRED] ?? 'off' !== 'on'));
        }
        $currentUser = wp_get_current_user();
        $displayName = $currentUser->display_name;
//        $avatarUrl = get_avatar_url($currentUser->ID);

        $user_avatar_url = get_user_meta($currentUser->ID, "user_avatar_url", true);

        if (empty($user_avatar_url)) {
            $user_avatar_url = get_avatar_url($currentUser->ID);
        }


        ?>
        <div class="flex flex-row items-center">
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
                <span class="font-bold"><?= __("Mente Compasiva") ?></span>
            </div>
            <div class="flex flex-row space-x-3 px-2">
                <?php foreach ($permissions as $item): ?>
                    <?php if (count($item[MC_POST_TYPES] ?? []) > 0): ?>
                        <a
                                class="hover:bg-blue-500 p-5 hover:text-white cursor-pointer transition"
                                href="<?= get_search_link("post_type=" . join(",", $item[MC_POST_TYPES] ?? [])) ?>"
                        >
                            <?= $item[MC_NAME] ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="flex-1"></div>
            <div class="mr-10">
                <div class="flex  flex-row items-center border-b-gray-300 border-b-width-2 bg-white  bg-white w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         class="flex-grow-0"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="10" cy="10" r="7"></circle>
                        <line x1="21" y1="21" x2="15" y2="15"></line>
                    </svg>
                    <input type="text" class="w-full focus:outline-none" placeholder="<?= __("Search") ?>...">
                </div>
            </div>
            <div class="flex-grow-0 cursor-pointer font-bold">
                <?php if (is_user_logged_in()) { ?>
                    <div class="flex flex-row space-x-2 cursor-pointer items-center" @click="profileOn=true">
                        <div><?= $displayName ?></div>
                        <img src="<?= $user_avatar_url ?>" class="w-28px h-28px rounded-full shadow-lg object-cover" alt="User profile" x-ref="navbarAvatar"/>
                    </div>
                <?php } else { ?>
                    <span> <?= __("login") . "/" . __("register") ?> </span>
                <?php } ?>
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