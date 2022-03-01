<!doctype html>

<html <?php language_attributes(); ?> >
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= __('MC') ?></title>

    <?php wp_head(); ?>
</head>

<body class="text-gray-600  h-screen">
<div class="flex flex-row h-screen">
    <div class="shapedividers_com-213  flex-1  ">
        <div class="bg-transparent absolute top-0 left-0 w-full h-full flex flex-col items-justified-center">
            <div class="text-center flex-grow-0  text-white text-3xl  text-shadow-lg">
                Bienvenido
            </div>
            <div class="text-center flex-grow-0  text-white text-6xl  text-shadow-lg">
                Mente Compasiva
            </div>
        </div>
        <video class="object-contain" autoplay muted loop src="<?= get_template_directory_uri() . "/assets/video/initial-video.mp4" ?>"></video>
    </div>
    <div class="flex-1 flex">
        <div class="m-auto ">
            <?php wp_login_form([
                'redirect' => site_url(),
                'remember' => true,
            ]); ?>
        </div>
    </div>
</div>