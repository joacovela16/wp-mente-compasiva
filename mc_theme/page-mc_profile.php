<html <?php language_attributes(); ?> lang="en">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= __('MC') ?></title>

    <?php wp_head(); ?>
</head>


<body x-data="mc_app" class="text-gray-600">
<?php if (is_user_logged_in()): ?>
    <?php
    $index = random_int(1, 7);
    $url = get_template_directory_uri() . "/assets/video/video-$index.mp4";
    $author = get_query_var('author');
    $user = wp_get_current_user();
    $countries = (get_option(MC_SETTING) ?? [])[MC_COUNTRIES] ?? [];

    if ($user):
        $data = $user->data;
        $description = get_user_meta($user->ID, "description", true);
        $birthday = get_user_meta($user->ID, "birthday", true);
        $country = get_user_meta($user->ID, "country", true) ?? '';
        $user_avatar_url = get_user_meta($user->ID, "user_avatar_url", true);
        $user_avatar_url = $user_avatar_url === "" ? get_avatar_url($user->ID) : $user_avatar_url;

        ?>
        <div class=" mx-auto ">
            <div class=" h-48 w-full overflow-hidden ">
                <video class="filter blur" x-init="loaderOn=false" src="<?= $url ?>" autoplay muted loop></video>
            </div>
            <div class="flex  flex-col ">
                <div class="p-2  w-full flex-grow-0">
                    <img src="<?= $user_avatar_url ?>" alt="AV" class="mx-auto shadow-lg h-64 w-64 -mt-36 rounded-full border-8 border-white z-20">
                    <div class="text-5xl text-center">
                        <?= $data->display_name ?? "" ?>
                    </div>
                    <div class=" text-center">
                        <?= $data->user_email ?? "" ?>
                    </div>
                </div>

                <form
                        action="<?= admin_url('admin-post.php') ?>"
                        method="post"
                        class="mx-auto px-5 w-full md:w-2/3 flow-grow-0 space-y-3 p-3 shadow-lg bg-white rounded-lg"
                        enctype="multipart/form-data"
                >
                    <div class="flex flex-row items-center">
                        <div class="font-bold text-3xl flex-1"><?= __('My profile') ?></div>
                        <div class=>
                            <a href="/" class="flex flex-row ring-2 ring-blue-500 py-1 px-2 shadow-lg rounded hover:bg-blue-500 hover:text-white transition-colors">
                                <svg width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1"></path>
                                </svg>
                                <span><?= __('Back') ?></span>
                            </a>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="update_user">
                    <div class="field">
                        <div class="field-label"><?= __('Name') ?></div>
                        <div class="field-content">
                            <input value="<?= $data->display_name ?>" name="mc_name" type="text" class="field-text" placeholder="<?= __('Name') ?>">
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label"><?= __('About me') ?></div>
                        <div class="field-content">
                            <textarea rows="10" name="mc_about" class="field-text" placeholder="<?= __('Write about you') ?>"><?= $description ?? '' ?></textarea>
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label"><?= __('Birthdate') ?></div>
                        <div class="field-content">
                            <input name="mc_birthday" type="date" value="<?= $birthday ?? '' ?>" class="field-text" placeholder="<?= __('Birthdate') ?>">
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label"><?= __('Country') ?></div>
                        <div class="field-content">
                            <select name="mc_country" class="field-select">
                                <? foreach ($countries as $item): ?>
                                    <option <?= $item === $country ? 'selected' : '' ?>><?= $item ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="field">
                        <div class="field-label"><?= __('Web site') ?></div>
                        <div class="field-content">
                            <input name="mc_website" type="url" value="<?= $data->user_url ?? '' ?>" class="field-text" placeholder="<?= __('Web site') ?>">
                        </div>
                    </div>

                    <div class="field">
                        <div class="field-label"><?= __('Change picture') ?></div>
                        <div class="field-content">
                            <input name="mc_picture" type="file" accept="image/png,image/jpeg" class="field-text" placeholder="<?= __('Change picture') ?>">
                        </div>
                    </div>

                    <details>
                        <summary class="font-bold"><?= __("Security") ?></summary>
                        <div class="p-3 space-y-3">
                            <div class="field">
                                <div class="field-label"><?= __('New password') ?></div>
                                <div class="field-content">
                                    <input name="mc_password_1" type="password" class="field-text" placeholder="<?= __('New password') ?>">
                                </div>
                            </div>

                            <div class="field">
                                <div class="field-label"><?= __('Confirm password') ?></div>
                                <div class="field-content">
                                    <input name="mc_password_2" type="password" class="field-text" placeholder="<?= __('Confirm password') ?>">
                                </div>
                            </div>
                        </div>
                    </details>


                    <div class="flex flex-row">
                        <button type="submit" class="transition-all bg-blue-500 text-white p-2 rounded shadow-lg inline-block w-35 text-center cursor-pointer"><?= __('Save changes') ?></button>
                        <div class="flex-1"></div>
                        <div class="transition-all ring-red-500 ring-2 rounded p-2 rounded shadow-lg inline-block text-center cursor-pointer hover:text-white hover:bg-red-500">
                            <a href="<?= wp_logout_url(site_url()) ?>" class="flex flex-row">
                                <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                                        <path d="M7 12h14l-3 -3m0 6l3 -3"></path>
                                    </svg>
                                </span>
                                <span><?= __('Logout') ?></span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>