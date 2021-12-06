<?php

modal([
    "header" => function () {
        $url = mc_get_user_avatar_url();
        $current_user = wp_get_current_user();
        ?>
        <div class="flex flex-row space-x-2">
            <img src="<?= $url ?>" alt="avatar" class="w-32px h-32px rounded-full items-center shadow-lg object-cover">
            <div><?= $current_user->display_name ?></div>
        </div>
        <?php
    },
    "content" => function () {
        ?>
        <div class="space-y-5 flex flex-col bg-white p-3">
            <div class="flex flex-col md:flex-row md:space-x-5">
                <?php

                field([
                    "label" => __('Name'),
                    "content" => '<input type="text" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg">'
                ]);

                field([
                    "label" => __('Email'),
                    "content" => '<input type="email" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg">'
                ]);

                ?>
            </div>
            <div class="flex flex-col md:flex-row md:space-x-5">
                <?php

                field([
                    "label" => __('Website'),
                    "content" => '<input type="url" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg">'
                ]);
                $lbl = __("Select image");
                field([
                    "label" => __('Change avatar'),
                    "content" => "<label class='p-2 rounded ring-2 ring-gray-100 cursor-pointer hover:ring-blue-500 hover:shadow-lg'>
                                    <input type='file' class='hidden' accept='image/png, image/jpeg'>
                                    $lbl
                                  </label>"
                ]);
                ?>
            </div>
            <?php
            field([
                "label" => __('About you'),
                "content" => '<textarea class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg"></textarea>'
            ]);
            ?>
            <details>
                <summary class="font-bold"><?= __("Security") ?></summary>
                <div class="space-y-3 mt-5">
                    <?php
                    field([
                        "label" => __("Current password"),
                        "content" => '<input type="password" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg">'
                    ]);
                    ?>
                    <div class="flex flex-row space-x-5">
                        <?php
                        field([
                            "label" => __("New password"),
                            'content' => '<input type="password" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg">'
                        ]);

                        field([
                            "label" => __("Confirm password"),
                            'content' => '<input type="password" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg">'
                        ]);
                        ?>
                    </div>
                </div>
            </details>

            <div>
                <a href="<?= wp_logout_url(home_url()) ?>" class="font-bold space-x-1 items-center flex text-red-600 text-right">
                    <span class="inline-block"><?= __("Logout") ?></span>
                    <span class="inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                             height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                            <path d="M7 12h14l-3 -3m0 6l3 -3"></path>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
        <?php
    },
    "footer" => function () {

    }
],
    "profileOn"
);

?>


