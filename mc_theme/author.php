<html <?php language_attributes(); ?> lang="en">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= __('Mente Compasiva') ?></title>

    <?php wp_head(); ?>
</head>


<body x-data="mc_app" class="text-gray-600">
<?php if (is_user_logged_in()): ?>
    <?php
    $index = random_int(1, 7);
    $url = get_template_directory_uri() . "/assets/video/video-$index.mp4";
    $author = get_query_var('author');
    $user = get_user_by('ID', $author);

    if ($user):
        $data = $user->data;
        $user_avatar_url = get_user_meta($user->ID, "user_avatar_url", true);
        $user_avatar_url = $user_avatar_url === "" ? get_avatar_url($user->ID) : $user_avatar_url;
        ?>
        <div class=" mx-auto ">
            <div class=" h-48 w-full overflow-hidden ">
                <video class="filter blur" x-init="loaderOn=false" src="<?= $url ?>" autoplay muted loop></video>
            </div>
            <div class="flex flex-row">
                <div class="p-2">
                    <img src="<?= $user_avatar_url ?>" alt="AV" class="shadow-lg h-64 w-64 -mt-32 rounded-full border-8 border-white z-20">
                    <div class="text-5xl text-center">
                        <?= $data->display_name ?? "" ?>
                    </div>
                    <div class=" text-center">
                        <?= $data->user_email ?? "" ?>
                    </div>
                </div>
                <form
                        action="<?= admin_url( 'admin-post.php' ) ?>"
                        method="post"
                        class="mx-auto w-1/2 space-y-3 p-3 -mt-5 bg-white rounded-t-lg"
                >
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
                            <textarea rows="10" name="mc_about" value="<?= $data->description ?? '' ?>" class="field-text" placeholder="<?= __('Write about you') ?>"></textarea>
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

                    <details open>
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

                    <button class="transition-all bg-blue-500 text-white p-2 rounded shadow-lg inline-block w-35 text-center cursor-pointer"><?= __('Save changes') ?></button>
                </form>
            </div>

        </div>
    <?php endif; ?>
<?php else: ?>
<?php endif; ?>

</body>
</html>