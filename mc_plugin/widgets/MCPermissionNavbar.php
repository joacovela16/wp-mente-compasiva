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
        $permissions = $settings ? $settings[MC_PERMISSIONS] : [];

        if (!is_user_logged_in()) {
            $permissions = array_values(array_filter($permissions, fn($x) => !isset($x[MC_LOGGED_REQUIRED])));
        }
        ?>
        <div class="flex sm:flex-row flex-col items-center transition-all text-lg bg-white shadow-lg p-1 space-x-2">
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
            <div class="flex flex-row flex-1 justify-center ">
                <div class="w-2/3 flex flex-row">
                    <?php foreach ($permissions as $item): ?>
                        <?php if (count($item[MC_POST_TYPES] ?? []) > 0): ?>
                            <?php if ($item[MC_ID] === $pname): ?>
                                <div class="px-3 py-5 border-b-2 border-blue-500 cursor-pointer text-center items-center font-bold flex-1">
                                    <a class="w-full" href="<?= ("/?s=&ptype=" . $item[MC_ID]) ?>">
                                        <?= $item[MC_NAME] ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="px-3 py-5 border-b-2 border-transparent hover:border-blue-500 hover:bg-zinc-100 cursor-pointer transition text-center
                            items-center font-bold flex-1">
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
            </div>
            <form action="/" method="get" class="flex flex-row items-center border-gray-200 px-2 py-1 border-1 rounded-full bg-white m-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     class="flex-grow-0 text-gray-600"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <circle cx="10" cy="10" r="7"></circle>
                    <line x1="21" y1="21" x2="15" y2="15"></line>
                </svg>
                <input type="text" class="w-full focus:outline-none" placeholder="<?= __("Search") ?>..." name="s">
            </form>
            <div class="flex-grow-0 cursor-pointer font-bold px-3 mc_svelte_profile text-gray-600">

            </div>
        </div>


        <?php

        add_action("wp_footer", function () {
            $current_user = wp_get_current_user();
            $display_name = $current_user->display_name;
            $user_email = $current_user->user_email;
            $user_url = $current_user->user_url;
            $description = $current_user->description;
            $user_avatar_url = get_user_meta($current_user->ID, "user_avatar_url", true);
            $user_avatar_url = $user_avatar_url === "" ? get_avatar_url($current_user->ID) : $user_avatar_url;
            $config = [
                "isLogged" => is_user_logged_in(),
                "user_avatar_url" => $user_avatar_url,
                "display_name" => $display_name,
                "user_email" => $user_email,
                "user_url" => $user_url,
                "description" => $description,
                "i18n" => []
            ];
            ?>
            <script type="module">
                <?= 'import {renderProfile} from "' . plugins_url() . '/mc_plugin/assets/mc_svelte_lib.es.js"' ?>

                renderProfile("mc_svelte_profile", <?= wp_json_encode($config) ?>);
                /* const H = "80px";
                 const H2 = "45px";
                 document.getElementById("navbar").style.height = H;
                 window.addEventListener("scroll", () => {
                     const ref = 110;
                     if (document.body.scrollTop > ref || document.documentElement.scrollTop > ref) {
                         document.getElementById("navbar").style.height = H2;
                     } else {
                         document.getElementById("navbar").style.height = H;
                     }
                 });*/
            </script>
            <?php
        });
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