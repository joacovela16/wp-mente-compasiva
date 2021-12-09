<?php
if (is_user_logged_in()) {
    $url = mc_get_user_avatar_url();
    $current_user = wp_get_current_user();
    $display_name = $current_user->display_name;
    $user_email = $current_user->user_email;
    context(["user"=>$current_user], function ($ctx){
       $a =$ctx->user;
       $b =1;
    });

    modal([
        "header" => function () use ($current_user, $url) {
            el("div", ["class" => "flex flex-row space-x-2 items-center"], function () use ($current_user, $url) {
                el("img", ["src" => $url, "class" => "w-32px h-32px rounded-full items-center shadow-lg object-cover"]);
                el("div", $current_user->display_name);
            });
        },
        "content" => function () use ($user_email, $display_name, $current_user) {
            div(["class" => "space-y-5 flex flex-col bg-white p-3"], function () use ($current_user) {
                div(["class" => "flex flex-col md:flex-row md:space-x-5"], function () use ($current_user) {
                    field([
                        "label" => __('Name'),
                        "content" => function () use ($current_user) {
                            $display_name = $current_user->display_name;
                            el(
                                'input',
                                [
                                    "type" => 'text',
                                    "class" => 'appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg',
                                    "x-init" => "profile.name='$display_name'",
                                    "x-model" => "profile.name",
                                ]);
                        }
                    ]);

                    field([
                        "label" => __('Email'),
                        "content" => function () use ($current_user) {
                            $user_email = $current_user->user_email;
                            el(
                                'input',
                                [
                                    "type" => 'email',
                                    "class" => 'appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg',
                                    "x-init" => "profile.email='$user_email'",
                                    "x-model" => "profile.email",
                                ]);
                        }
                    ]);
                });
            });
            ?>
            <div class="space-y-5 flex flex-col bg-white p-3">
                <div class="flex flex-col md:flex-row md:space-x-5">
                    <?php

                    field([
                        "label" => __('Name'),
                        "content" => "
                            <input
                                    type='text'
                                    class='appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg'
                                    x-init=\"profile.name='${display_name}'\"
                                    x-model='profile.name' >
                            "
                    ]);

                    field([
                        "label" => __('Email'),
                        "content" => "
                            <input 
                            type=\"email\" 
                            class=\"appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg\"
                            x-init=\"profile.email='$user_email'\" 
                            x-model=\"profile.email\"
                            >
                            "
                    ]);

                    ?>
                </div>
                <div class="flex flex-col md:flex-row md:space-x-5">
                    <?php

                    field([
                        "label" => __('Website'),
                        "content" => "
                            <input 
                                type=\"url\" 
                                class=\"appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg\" 
                                x-model=\"profile.website\">"
                    ]);
                    $lbl = __("Select image");
                    field([
                        "label" => __('Change avatar'),
                        "content" => "<label class='p-2 rounded ring-2 ring-gray-100 cursor-pointer hover:ring-blue-500 hover:shadow-lg'>
                                    <input type='file' class='hidden' accept='image/png, image/jpeg' x-model='profile.file'>
                                    $lbl
                                  </label>"
                    ]);
                    ?>
                </div>
                <?php
                field([
                    "label" => __('About you'),
                    "content" => '<textarea class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg" x-model="profile.about"></textarea>'
                ]);
                ?>
                <details>
                    <summary class="font-bold"><?= __("Security") ?></summary>
                    <div class="space-y-3 mt-5">
                        <?php
                        field([
                            "label" => __("Current password"),
                            "content" => '<input type="password" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg" x-model="profile.pwd">'
                        ]);
                        ?>
                        <div class="flex flex-row space-x-5">
                            <?php
                            field([
                                "label" => __("New password"),
                                'content' => '<input type="password" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg" x-model="profile.new_pwd">'
                            ]);

                            field([
                                "label" => __("Confirm password"),
                                'content' => '<input type="password" class="appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg" x-model="profile.confirm_pwd">'
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
            ?>
            <div class="flex flex-row space-x-2 font-bold items-center">
                <div class="cursor-pointer" @click="profileOn=false"><?= __("Cancel") ?></div>
                <div class="flex-1"></div>
                <div class="transition-all bg-blue-500 text-white p-2 rounded shadow-lg inline-block w-35 text-center cursor-pointer" @click="submitProfile()">
                    <?= __("Apply changes") ?>
                </div>
            </div>
            <?php
        }
    ],
        "profileOn"
    );

}
?>


