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
        $pname = $_GET["pname"] ?? "";
        $settings = get_option(MC_SETTING);
        $permissions = $settings[MC_PERMISSIONS];

        if (!is_user_logged_in()) {
            $permissions = array_values(array_filter($permissions, fn($x) => !isset($x[MC_LOGGED_REQUIRED])));
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
            <div class="flex-grow-0 cursor-pointer">
                <a href="/" class="flex flex-row px-3 py-5 bg-gray-100">
                    <svg width="24" height="24" stroke-width="1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 9.5L12 4L21 9.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 13V19.4C19 19.7314 18.7314 20 18.4 20H5.6C5.26863 20 5 19.7314 5 19.4V13" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    <span class=" ml-1"><?= __("Home") ?></span>
                </a>
            </div>
            <div class="flex flex-row space-x-1 ">
                <?php foreach ($permissions as $item): ?>
                    <?php if (count($item[MC_POST_TYPES] ?? []) > 0): ?>
                        <?php if ($item[MC_NAME] === $pname): ?>
                            <a
                                    class="bg-blue-600 px-3 py-5 text-white cursor-pointer"
                                    href="<?= ("?s=&ptype=" . join(",", $item[MC_POST_TYPES] ?? [])) . "&pname=" . $item[MC_NAME] ?>"
                            >
                                <?= $item[MC_NAME] ?>
                            </a>
                        <?php else: ?>
                            <a
                                    class="hover:bg-blue-600 px-3 py-5 hover:text-white cursor-pointer transition"
                                    href="<?= ("?s=&ptype=" . join(",", $item[MC_POST_TYPES] ?? [])) . "&pname=" . $item[MC_NAME] ?>"
                            >
                                <?= $item[MC_NAME] ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="flex-1"></div>
            <div class="mr-10">
                <div class="flex  flex-row items-center border-gray-300 border-1 rounded-full p-1 bg-white  bg-white w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         class="flex-grow-0"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="10" cy="10" r="7"></circle>
                        <line x1="21" y1="21" x2="15" y2="15"></line>
                    </svg>
                    <form action="/" method="get">
                        <input type="text" class="w-full focus:outline-none" placeholder="<?= __("Search") ?>..." name="s">
                    </form>
                </div>
            </div>
            <div class="flex-grow-0 cursor-pointer font-bold px-3">
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