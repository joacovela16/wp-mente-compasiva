<!doctype html>

<html <?php language_attributes(); ?> >
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= __('MC') ?></title>

    <?php wp_head(); ?>
</head>

<body x-data="mc_app" class="text-gray-600">
<div class="">
<?php
get_template_part("template-parts/navbar");
//get_template_part("template-parts/profile");
?>
