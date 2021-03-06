<html <?php language_attributes(); ?> lang="en">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= __('MC') ?></title>

    <?php wp_head(); ?>
    <?php wp_dequeue_script('alpine'); ?>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&display=swap');
    </style>
    <script src="<?= get_template_directory_uri() . "/assets/javascript/alpine.js" ?>"></script>
</head>


<body x-data="mc_app" class="text-gray-600 font-roboto">
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
        $dni = get_user_meta($ID, MC_DNI, true) ?? '';
        $city = get_user_meta($ID, MC_CITY, true) ?? '';
        $phone = get_user_meta($ID, MC_PHONE, true) ?? '';
        $email = get_user_meta($ID, MC_EMAIL, true);
        $email = empty($email) ? $data->user_email : $email;
        $user_avatar_url = get_user_meta($ID, MC_AVATAR_URL, true);
        $has_user_avatar = $user_avatar_url !== "";
        $user_avatar_url = $user_avatar_url === "" ? get_avatar_url($ID) : $user_avatar_url;
        $work_with = get_user_meta($ID, MC_WORKS_WITH);
        $cft_when_where = get_user_meta($ID, MC_CFT_WHEN_WHERE, true);
        $website = get_user_meta($ID, MC_WEBSITE, true);
        $gender = get_user_meta($ID, MC_GENDER, true);
        $profession = get_user_meta($ID, MC_PROFESSION, true);
        $mode = get_user_meta($ID, MC_MODE, true);
        $is_cft = get_user_meta($ID, MC_CFT, true) === 'on';
        $policy1 = get_user_meta($ID, MC_POLICY, true) === 'on';
        $url_mode = get_user_meta($ID, MC_WEBSITE_MODE, true);
        $professions = get_option(MC_PROFESSION_OPTIONS, []);
        $languages = get_user_meta($ID, MC_LANGUAGE, []);
        $display_name = empty($user->first_name) ? $user->display_name : $user->first_name;

        $showCFT = $is_cft || current_user_can('administrator');
        $dataInit = json_encode(
            [
                'selection' => null,
                'values' => [
                    MC_LINK_WEBSITE => 'Ingresa enlace, ejemplo: https://google.com',
                    MC_LINK_INSTAGRAM => 'Ingresa t?? usuario de instagram , ejemplo: @miusuario',
                    MC_LINK_FACEBOOK => 'Ingresa enlace a t?? perfil',
                    MC_LINK_LINKEDIN => 'Ingresa enlace a tu perfil'
                ]
            ]

        ) ?>
        <div class="mx-auto relative">
            <div class="h-48 w-full overflow-hidden ">
                <video x-init="loaderOn=false" src="<?= $url ?>" autoplay muted loop></video>
            </div>
            <div class="flex flex-col ">
                <div class="p-2 w-full flex-grow-0">
                    <img src="<?= $user_avatar_url ?>" alt="AV" class="mx-auto shadow-lg h-64 w-64 -mt-36 rounded-full border-8 border-white z-20 relative">

                    <div class="text-5xl text-center">
                        <?= $display_name ?>
                    </div>
                </div>
                <?php if (isset($_GET['a'])): ?>
                <div class="card mx-auto md:w-2/3 bg-blue-50 rounded ring-1 ring-blue-500 p-3 my-3">
                    <p>Bienvenido a Mente Compasiva.</p>
                    <p>Te agradecemos que te tomes unos minutos para completar los siguientes datos. Esto facilitar?? tu b??squeda en el
                        <a class="text-blue-500" href="<?= site_url('/directorio-cft') ?>">Directorio de profesionales CFT</a>.
                    </p>
                    <p>Muchas gracias.</p>
                    <?php endif; ?>
                </div>
                <form
                        action="<?= admin_url('admin-post.php') ?>"
                        method="post"
                        class="card mx-auto px-5 w-full md:w-2/3 flow-grow-0 p-3 bg-white shadow-xl space-y-3"
                        enctype="multipart/form-data"
                >

                    <p><b>Importante:</b> Los campos marcados con <span class="!text-red-500 mr-1">*</span> son requeridos. En caso de no completarlos no quedar?? visible en el directorio.</p>

                    <input type="hidden" name="action" value="update_user">
                    <div class="flex flex-row items-center">
                        <div class="font-bold text-3xl flex-1"><?= __('My profile') ?></div>
                        <div class=>
                            <a href="<?= site_url('/directorio-cft') ?>" class="btn gap-2 btn-primary">
                                <svg width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <polyline points="15 6 9 12 15 18"></polyline>
                                </svg>
                                <span><?= __('Back') ?></span>
                            </a>
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">

                            <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('Name and lastname') ?></span>
                        </label>
                        <input type="text" class="input input-bordered w-full" name="<?= MC_NAME ?>" value="<?= $display_name ?>" required>
                    </div>
                    <?php if ($showCFT): ?>
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('cft_when_and_where') ?></span>
                            </label>
                            <input type="text" class="input input-bordered w-full" name="<?= MC_CFT_WHEN_WHERE ?>" value="<?= $cft_when_where ?>" required>
                        </div>
                    <?php endif; ?>

                    <div class="flex flex-row items-center space-x-3">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('Country') ?></span>
                            </label>
                            <input type="text" class="input input-bordered w-full" name="<?= MC_COUNTRY ?>" value="<?= $country ?>" required>
                        </div>
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('City') . '/' . __('Province') ?></span>
                            </label>
                            <input type="text" class="input input-bordered w-full" name="<?= MC_CITY ?>" value="<?= $city ?>">
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('DNI or RUT') ?></span>
                        </label>
                        <input type="text" class="input input-bordered w-full" name="<?= MC_DNI ?>" value="<?= $dni ?>">
                    </div>

                    <div class="form-control w-full " x-data="{showOther: '<?= in_array($profession, $professions) ? $profession : MC_OTHER ?>'}">
                        <label class="label">
                            <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('Profession') ?></span>
                        </label>
                        <div class="flex flex-row space-x-2">
                            <select class="select select-bordered w-1/2" name="<?= MC_PROFESSION ?>" x-model="showOther" required>
                                <option value="" disabled> <?= __('select') ?></option>
                                <?php foreach ($professions as $item): ?>
                                    <option value="<?= $item ?>"><?= $item ?></option>
                                <?php endforeach; ?>
                                <option value="<?= MC_OTHER ?>"><?= __(MC_OTHER) ?></option>
                            </select>
                            <input
                                    x-bind:disabled="showOther!=='<?= MC_OTHER ?>'"
                                    type="text"
                                    class="input input-bordered w-1/2 input-primary"
                                    name="<?= MC_PROFESSION ?>"
                                    value="<?= in_array($profession, $professions) ? '' : $profession ?>"
                            >
                        </div>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('Work mode') ?></span>
                        </label>
                        <select class="select select-bordered w-full" name="<?= MC_MODE ?>" required>
                            <option value="" <?= $mode === '' ? 'selected' : '' ?> disabled> <?= __('select') ?></option>
                            <option <?= $mode === 'onsite' ? 'selected' : '' ?> value="onsite"> <?= __('onsite') ?></option>
                            <option <?= $mode === 'online' ? 'selected' : '' ?> value="online"> <?= __('online') ?></option>
                            <option <?= $mode === 'online-onsite' ? 'selected' : '' ?> value="online-onsite"> <?= __('online-onsite') ?></option>
                        </select>
                    </div>

                    <?php if ($showCFT): ?>
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('Works with') ?></span>
                            </label>

                            <div class="space-y-2">
                                <?php foreach (get_option(MC_WORKS_WITH, []) as $item): ?>
                                    <label class="flex flex-row items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" class="checkbox" value="<?= $item ?>" name="<?= MC_WORKS_WITH ?>[]" <?= in_array($item, $work_with) ? 'checked' : '' ?>>
                                        <span><?= $item ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('which language(s) do you offer therapy?') ?></span>
                            </label>

                            <div class="space-y-2">
                                <?php foreach (get_option(MC_LANGUAGE, []) as $item): ?>
                                    <label class="flex flex-row items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" class="checkbox" value="<?= $item ?>" name="<?= MC_LANGUAGE ?>[]" <?= in_array($item, $languages) ? 'checked' : '' ?>>
                                        <span><?= $item ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('About me') ?></span>
                        </label>
                        <textarea maxlength="500"
                                  rows="4"
                                  placeholder="<?= __('about_me_placeholder') ?>"
                                  type="text"
                                  class="textarea textarea-bordered w-full"
                                  name="<?= MC_ABSTRACT ?>"
                                  value="<?= $data->display_name ?>" required><?= $description ?? '' ?></textarea>
                    </div>

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

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('Birthdate') ?></span>
                        </label>
                        <input type="date" class="input input-bordered w-full" name="<?= MC_BIRTHDAY ?>" value="<?= $birthday ?>" required>
                    </div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><span class="!text-red-500 mr-1">*</span><?= __('Contact email') ?></span>
                        </label>
                        <input type="email" class="input input-bordered w-full" name="<?= MC_EMAIL ?>" value="<?= $email ?>" required>
                    </div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Phone') ?></span>
                        </label>
                        <input type="text" class="input input-bordered w-full" name="<?= MC_PHONE ?>" value="<?= $phone ?>" placeholder="<?= __('phone_enter_and_show') ?>">
                    </div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Website or Social Network') ?></span>
                        </label>
                        <div
                                class="input-group"
                                x-init="selection='<?= $url_mode ?>'"
                                x-data="<?= htmlspecialchars($dataInit) ?>"

                        >
                            <select class="select select-bordered" name="<?= MC_WEBSITE_MODE ?>" x-model="selection">
                                <option value="<?= MC_LINK_WEBSITE ?>">Sitio web</option>
                                <option value="<?= MC_LINK_INSTAGRAM ?>">Instagram</option>
                                <option value="<?= MC_LINK_FACEBOOK ?>">Facebook</option>
                                <option value="<?= MC_LINK_LINKEDIN ?>">Linkedin</option>
                            </select>
                            <input
                                    type="text"
                                    class="input input-bordered w-full"
                                    x-bind:placeholder="values[selection]" name="<?= MC_WEBSITE ?>" value="<?= $website ?>">
                        </div>
                    </div>

                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-box">
                        <input type="checkbox" checked>
                        <div class="collapse-title font-medium">
                            <span class="!text-red-500 mr-1">*</span> <?= __('Upload profile picture') ?>
                        </div>
                        <div class="collapse-content">
                            <p class="text-sm font-bold"><?= __('picture_desc') ?></p>
                            <input class="mt-3" name="<?= MC_PICTURE ?>" type="file" accept="image/png,image/jpeg" placeholder="<?= __('Change picture') ?>" <?= $has_user_avatar ? '' : 'required' ?> >
                        </div>
                    </div>

                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-box">
                        <input type="checkbox">
                        <div class="collapse-title font-medium"><?= __("Security") ?></div>
                        <div class="collapse-content flex flow-row gap-2">
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text"><?= __('New password') ?></span>
                                </label>
                                <input type="text" class="input input-bordered w-full" name="<?= MC_PASSWORD_1 ?>">
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text"><?= __('Confirm password') ?></span>
                                </label>
                                <input type="text" class="input input-bordered w-full" name="<?= MC_PASSWORD_2 ?>">
                            </div>
                        </div>
                    </div>

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