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

    if ($user):
        $ID = $user->ID;
        $data = $user->data;
        $description = get_user_meta($ID, MC_ABSTRACT, true);
        $birthday = get_user_meta($ID, MC_BIRTHDAY, true);
        $country = get_user_meta($ID, MC_COUNTRY, true) ?? '';
        $city = get_user_meta($ID, MC_CITY, true) ?? '';
        $phone = get_user_meta($ID, MC_PHONE, true) ?? '';
        $user_avatar_url = get_user_meta($ID, MC_AVATAR_URL, true);
        $user_avatar_url = $user_avatar_url === "" ? get_avatar_url($ID) : $user_avatar_url;
        $work_with = get_user_meta($ID, MC_WORKS_WITH);
        $website = get_user_meta($ID, MC_WEBSITE, true);
        $gender = get_user_meta($ID, MC_GENDER, true);
        $is_cft = get_user_meta($ID, MC_CFT, true) === 'on';
        ?>
        <div class="mx-auto relative">
            <div class="h-48 w-full overflow-hidden ">
                <video x-init="loaderOn=false" src="<?= $url ?>" autoplay muted loop></video>
            </div>
            <div class="flex flex-col ">
                <div class="p-2  w-full flex-grow-0">
                    <img src="<?= $user_avatar_url ?>" alt="AV" class="mx-auto shadow-lg h-64 w-64 -mt-36 rounded-full border-8 border-white z-20 relative">
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
                        class="card mx-auto px-5 w-full md:w-2/3 flow-grow-0 p-3 bg-white shadow-xl   "
                        enctype="multipart/form-data"
                >
                    <input type="hidden" name="action" value="update_user">
                    <div class="flex flex-row items-center">
                        <div class="font-bold text-3xl flex-1"><?= __('My profile') ?></div>
                        <div class=>
                            <a href="/" class="btn gap-2 btn-primary">
                                <svg width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1"></path>
                                </svg>
                                <span><?= __('Back') ?></span>
                            </a>
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Name') ?></span>
                        </label>
                        <input type="text" class="input input-bordered w-full" name="<?= MC_NAME ?>" value="<?= $data->display_name ?>">
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('About me') ?></span>
                        </label>
                        <textarea placeholder="<?= __('Write about you') ?>" type="text" class="textarea textarea-bordered w-full" name="<?= MC_ABSTRACT ?>" value="<?= $data->display_name ?>"><?= $description ?? '' ?></textarea>
                    </div>

                    <?php if ($is_cft): ?>
                        <div class="form-control w-full ">
                            <label class="label">
                                <span class="label-text"><?= __('Works with') ?></span>
                            </label>
                            <select class="select select-bordered w-full h-auto" name="<?= MC_WORKS_WITH ?>[]" multiple>
                                <option value="children" <?= in_array("children", $work_with) ? 'selected' : '' ?>><?= __('Children') ?></option>
                                <option value="teenager" <?= in_array("teenager", $work_with) ? 'selected' : '' ?>><?= __('Teenager') ?></option>
                                <option value="adult" <?= in_array("adult", $work_with) ? 'selected' : '' ?>><?= __('Adult') ?></option>
                                <option value="couple" <?= in_array("couple", $work_with) ? 'selected' : '' ?>><?= __('Couple') ?></option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Gender') ?></span>
                        </label>
                        <select class="select select-bordered w-full h-18 overflow-hidden " name="<?= MC_GENDER ?>">
                            <option disabled></option>
                            <option value="female" <?= $gender === "female" ? 'selected' : '' ?> ><?= __('Female') ?></option>
                            <option value="male" <?= $gender === "male" ? 'selected' : '' ?>><?= __('Male') ?></option>
                        </select>
                    </div>

                    <div class="flex flex-row items-center space-x-3">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text"><?= __('Country') ?></span>
                            </label>
                            <input type="text" class="input input-bordered w-full" name="<?= MC_COUNTRY ?>" value="<?= $country ?>">
                        </div>
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text"><?= __('City') . '/' . __('Province') ?></span>
                            </label>
                            <input type="text" class="input input-bordered w-full" name="<?= MC_CITY ?>" value="<?= $city ?>">
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Birthdate') ?></span>
                        </label>
                        <input type="date" class="input input-bordered w-full" name="<?= MC_BIRTHDAY ?>" value="<?= $birthday ?>">
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Phone') ?></span>
                        </label>
                        <input type="text" class="input input-bordered w-full" name="<?= MC_PHONE ?>" value="<?= $phone ?>">
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Web site') ?></span>
                        </label>
                        <input type="text" class="input input-bordered w-full" name="<?= MC_WEBSITE ?>" value="<?= $website ?>">
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Change picture') ?></span>
                        </label>
                        <input class="px-3" name="<?= MC_PICTURE ?>" type="file" accept="image/png,image/jpeg"  placeholder="<?= __('Change picture') ?>" >
                    </div>

                    <details class="my-3">
                        <summary class="font-bold"><?= __("Security") ?></summary>
                        <div class="flex flow-row gap-2">
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text"><?= __('New password') ?></span>
                                </label>
                                <input type="text" class="input input-bordered w-full" name="<?= MC_PASSWORD_1 ?>" >
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text"><?= __('Confirm password') ?></span>
                                </label>
                                <input type="text" class="input input-bordered w-full" name="<?= MC_PASSWORD_2 ?>">
                            </div>
                        </div>
                    </details>

                    <div class="flex flex-row">
                        <button type="submit" class="btn btn-primary"><?= __('Save changes') ?></button>
                        <div class="flex-1"></div>
                        <a class="btn btn-error gap-1" href="<?= wp_logout_url(site_url()) ?>">

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
                </form>
            </div>

        </div>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>