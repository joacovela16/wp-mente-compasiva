<?php
get_header();
get_template_part("template-parts/loader");

$post = get_post();
setup_postdata($post);
//$image_url = get_post_meta($post->ID, MC_AVATAR_URL, true);
//$image_url = $image_url === "" ? get_avatar_url(0) : $image_url;

$ID = get_post_meta($post->ID, MC_USER_REF, true);
$user = get_userdata($ID);
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
$website_mode = get_user_meta($ID, MC_WEBSITE_MODE, true);
$work_mode = get_user_meta($ID, MC_MODE, true);
$is_cft = get_user_meta($ID, MC_CFT, true) === 'on';
$policy1 = get_user_meta($ID, MC_POLICY, true) === 'on';
$url_mode = get_user_meta($ID, MC_WEBSITE_MODE, true);
$professions = get_option(MC_PROFESSION_OPTIONS, []);

$langs = get_user_meta($ID, MC_LANGUAGE);

$showCFT = $is_cft || current_user_can('administrator');

?>
    <div class="mx-auto container my-5" x-init="loaderOn=false">
        <div class="mx-auto w-full max-w-4xl card sm:shadow-lg pb-10 gap-2 rounded-box px-4">

            <img class="mask rounded-full shadow-lg w-32 h-32 sm:w-48 sm:h-48 md:w-64 md:h-64 mx-auto object-cover" src="<?= $user_avatar_url ?>" alt="profile image">

            <div class="text-3xl md:text-5xl text-center my-4"><?= $user->first_name ?></div>

            <div class="text-sm">
                <div class="text-center ">
                    <span class="font-bold"><?= __('Location') ?>: </span>
                    <span>
                        <?php if (empty($city) && empty($country)): ?>
                            <?= __('undeclared') ?>
                        <?php else: ?>
                            <?= implode(", ", [$city, $country]) ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="text-center ">
                    <span class="font-bold"><?= __('Profession') ?>:</span>
                    <span>
                        <?php if (empty($profession)): ?>
                            <?= __('undeclared') ?>
                        <?php else: ?>
                            <?= $profession ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="text-center ">
                    <span class="font-bold"><?= __('Works with') ?>:</span>
                    <span>
                        <?php if (empty($work_with)): ?>
                            <?= __('undeclared') ?>
                        <?php else: ?>
                            <?= implode(", ", $work_with) ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="text-center ">
                    <span class="font-bold"><?= __('Work mode') ?>:</span>
                    <span>
                        <?php if (empty($work_mode)): ?>
                            <?= __('undeclared') ?>
                        <?php else: ?>
                            <?= __($work_mode) ?>
                        <?php endif; ?>
                    </span>
                </div>

                <div class="text-center ">
                    <span class="font-bold"><?= __('Therapy language') ?>:</span>
                    <span>
                        <?php if (!empty($langs)): ?>
                            <?= implode(", ", $langs) ?>
                        <?php endif; ?>
                    </span>
                </div>


                <div class="italic text-center my-3">
                    <?= $description ?>
                </div>
            </div>
            <div class="mx-auto mb-5">
                <div class="flex flex-col md:flex-row divide-x-0 sm:divide-x-2 flex-1 items-center">
                    <?php if (!empty($phone)): ?>
                        <div class="flex-row flex flex-1 p-3 space-x-3 items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                 fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                            </svg>
                            <span><?= $phone ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="flex-row flex flex-1 p-3 space-x-3 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <polyline points="3 7 12 13 21 7"></polyline>
                        </svg>
                        <span>
                            <a href="mailto:<?= $email ?>"><?= $email ?></a>
                        </span>
                    </div>
                    <?php if (!empty($website)): ?>
                        <div class="flex-row flex flex-1 p-3 space-x-3 items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-dribbble" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                 stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M9 3.6c5 6 7 10.5 7.5 16.2"></path>
                                <path d="M6.4 19c3.5 -3.5 6 -6.5 14.5 -6.4"></path>
                                <path d="M3.1 10.75c5 0 9.814 -.38 15.314 -5"></path>
                            </svg>
                            <span>
                                <?php if ($website_mode === "" || $website_mode === MC_LINK_WEBSITE): ?>
                                    <a href="<?= $website ?>" target="_blank"><?= $website ?></a>
                                <?php elseif ($website_mode === MC_LINK_INSTAGRAM): ?>
                                    <a href="https://www.instagram.com/<?= str_replace("@", "", $website) ?>" target="_blank"><?= $website ?></a>
                                <?php elseif ($website_mode === MC_LINK_FACEBOOK): ?>
                                    <a href="https://www.facebook.com/<?= str_replace("@", "", $website) ?>" target="_blank"><?= $website ?></a>
                                <?php elseif ($website_mode === MC_LINK_LINKEDIN): ?>
                                    <a href="https://www.linkedin.com/in/<?= str_replace("@", "", $website) ?>" target="_blank"><?= $website ?></a>
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();