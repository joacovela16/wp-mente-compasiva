<html <?php language_attributes(); ?> lang="en">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= __('MC') ?></title>

    <?php wp_head(); ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&display=swap');
    </style>
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
        $user_avatar_url = $user_avatar_url === "" ? get_avatar_url($ID) : $user_avatar_url;
        $work_with = get_user_meta($ID, MC_WORKS_WITH);
        $cft_when_where = get_user_meta($ID, MC_CFT_WHEN_WHERE, true);
        $website = get_user_meta($ID, MC_WEBSITE, true);
        $gender = get_user_meta($ID, MC_GENDER, true);
        $profession = get_user_meta($ID, MC_PROFESSION, true);
        $mode = get_user_meta($ID, MC_MODE, true);
        $is_cft = get_user_meta($ID, MC_CFT, true) === 'on';
        $policy1 = get_user_meta($ID, MC_POLICY_1, true) === 'on';
        $url_mode = get_user_meta($ID, MC_WEBSITE_MODE, true);
        $professions = get_option(MC_PROFESSION_OPTIONS, []);

        $showCFT = $is_cft || current_user_can('administrator')
        ?>
        <div class="mx-auto relative">
            <div class="h-48 w-full overflow-hidden ">
                <video x-init="loaderOn=false" src="<?= $url ?>" autoplay muted loop></video>
            </div>
            <div class="flex flex-col ">
                <div class="p-2 w-full flex-grow-0">
                    <img src="<?= $user_avatar_url ?>" alt="AV" class="mx-auto shadow-lg h-64 w-64 -mt-36 rounded-full border-8 border-white z-20 relative">
                    <div class="text-5xl text-center">
                        <?= $data->display_name ?? "" ?>
                    </div>
                </div>

                <form
                        action="<?= admin_url('admin-post.php') ?>"
                        method="post"
                        class="card mx-auto px-5 w-full md:w-2/3 flow-grow-0 p-3 bg-white shadow-xl space-y-3"
                        enctype="multipart/form-data"
                >
                    <input type="hidden" name="action" value="update_user">
                    <div class="flex flex-row items-center">
                        <div class="font-bold text-3xl flex-1"><?= __('My profile') ?></div>
                        <div class=>
                            <a href="#/" class="btn gap-2 btn-primary" onclick="history.back();return -1;">
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
                            <span class="label-text"><?= __('Name and lastname') ?></span>
                        </label>
                        <input type="text" class="input input-bordered w-full" name="<?= MC_NAME ?>" value="<?= $data->display_name ?>">
                    </div>
                    <?php if ($showCFT): ?>
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text"><?= __('cft_when_and_where') ?></span>
                            </label>
                            <input type="text" class="input input-bordered w-full" name="<?= MC_CFT_WHEN_WHERE ?>" value="<?= $cft_when_where ?>">
                        </div>
                    <?php endif; ?>

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
                            <span class="label-text"><?= __('DNI or RUT') ?></span>
                        </label>
                        <input type="text" class="input input-bordered w-full" name="<?= MC_DNI ?>" value="<?= $dni ?>">
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text"><?= __('Profession') ?></span>
                        </label>
                        <select class="select select-bordered w-full" name="<?= MC_PROFESSION ?>">
                            <option value="" disabled> <?= __('select') ?></option>
                            <?php foreach ($professions as $item): ?>
                                <option value="<?= $item ?>" <?= $item === $profession ? 'selected' : '' ?>><?= $item ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-control w-full ">
                        <label class="label">
                            <span class="label-text"><?= __('Work mode') ?></span>
                        </label>
                        <select class="select select-bordered w-full" name="<?= MC_MODE ?>">
                            <option value="" <?= $mode === '' ? 'selected' : '' ?> disabled> <?= __('select') ?></option>
                            <option <?= $mode === 'onsite' ? 'selected' : '' ?> value="onsite"> <?= __('onsite') ?></option>
                            <option <?= $mode === 'online' ? 'selected' : '' ?> value="online"> <?= __('online') ?></option>
                            <option <?= $mode === 'online-onsite' ? 'selected' : '' ?> value="online-onsite"> <?= __('online-onsite') ?></option>
                        </select>
                    </div>

                    <?php if ($showCFT): ?>
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text"><?= __('Works with') ?></span>
                            </label>
                            <select class="select select-bordered w-full h-auto" name="<?= MC_WORKS_WITH ?>[]" multiple>
                                <?php foreach (get_option(MC_WORKS_WITH, []) as $item): ?>
                                    <option value="<?= $item ?>" <?= in_array($item, $work_with) ? 'selected' : '' ?>><?= $item ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('About me') ?></span>
                        </label>
                        <textarea maxlength="500"
                                  rows="4"
                                  placeholder="<?= __('about_me_placeholder') ?>"
                                  type="text"
                                  class="textarea textarea-bordered w-full"
                                  name="<?= MC_ABSTRACT ?>"
                                  value="<?= $data->display_name ?>"><?= $description ?? '' ?></textarea>
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
                            <span class="label-text"><?= __('Birthdate') ?></span>
                        </label>
                        <input type="date" class="input input-bordered w-full" name="<?= MC_BIRTHDAY ?>" value="<?= $birthday ?>">
                    </div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Contact email') ?></span>
                        </label>
                        <input type="email" class="input input-bordered w-full" name="<?= MC_EMAIL ?>" value="<?= $email ?>">
                    </div>
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text"><?= __('Phone') ?></span>
                        </label>
                        <input type="text" class="input input-bordered w-full" name="<?= MC_PHONE ?>" value="<?= $phone ?>" placeholder="<?= __('phone_enter_and_show') ?>">
                    </div>
                    <div class="form-control w-full">
                        <div class="input-group">
                            <select class="select select-bordered" name="<?= MC_WEBSITE_MODE ?>">
                                <option disabled <?= $url_mode === "" ? 'selected' : '' ?> ><?= __('Select') ?></option>
                                <option value="<?= MC_LINK_WEBSITE ?>" <?= $url_mode === MC_LINK_WEBSITE ? 'selected' : '' ?> >Sitio web</option>
                                <option value="<?= MC_LINK_INSTAGRAM ?>" <?= $url_mode === MC_LINK_INSTAGRAM ? 'selected' : '' ?>>Instagram</option>
                                <option value="<?= MC_LINK_FACEBOOK ?>" <?= $url_mode === MC_LINK_FACEBOOK ? 'selected' : '' ?>>Facebook</option>
                                <option value="<?= MC_LINK_LINKEDIN ?>" <?= $url_mode === MC_LINK_LINKEDIN ? 'selected' : '' ?>>Linkedin</option>
                                <option value="<?= MC_LINK_TWITTER ?>" <?= $url_mode === MC_LINK_TWITTER ? 'selected' : '' ?>>Twitter</option>
                            </select>
                            <input type="text" class="input input-bordered w-full" placeholder="<?= __("Set value") ?>" name="<?= MC_WEBSITE ?>" value="<?= $website ?>">
                        </div>
                    </div>

                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-box">
                        <input type="checkbox">
                        <div class="collapse-title font-medium">
                            <?= __('Upload profile picture') ?>
                        </div>
                        <div class="collapse-content">
                            <p class="text-sm font-bold"><?= __('picture_desc') ?></p>
                            <input class="mt-3" name="<?= MC_PICTURE ?>" type="file" accept="image/png,image/jpeg" placeholder="<?= __('Change picture') ?>">
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

                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-box">
                        <input type="checkbox">
                        <div class="collapse-title font-medium">
                            <?= __('agreement_policy') ?>
                        </div>
                        <div class="collapse-content">
                            <ul>
                                <li>Al proporcionar mis datos acepto voluntariamente que estos datos sean publicados en el directorio de profesionales de la salud mental
                                    formados en el modelo CFT gestionado por Cultivar la Mente y Mente Compasiva.</li>
                                <li>Comprendo que este directorio cumple con el fin de dar visibilidad a los profesionales con orientación CFT y facilitar el contacto entre
                                    posibles pacientes interesados en seguir un tratamiento centrado en la compasión y profesionales de la salud mental.
                                </li>
                                <li>
                                    Cultivar la Mente y Mente Compasiva se reserva el derecho de quitar un registro de este listado ante eventuales quejas o denuncias de mala
                                    praxis o problemas de ética profesional.
                                </li>
                            </ul>
                            <div class="form-control">
                                <label class="label cursor-pointer space-x-2">
                                    <input type="checkbox" class="checkbox" name="<?= MC_POLICY_1 ?>" <?= $policy1 ? 'checked' : '' ?>>
                                    <span class="label-text">Acepto todos los términos</span>
                                </label>
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