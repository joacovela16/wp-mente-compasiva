<!doctype html>

<html <?php language_attributes(); ?> >
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= __('Mente Compasiva') ?></title>
    <link rel="icon" type="image/x-icon" href="<?= get_stylesheet_directory_uri().'/assets/images/favicon.png' ?>" />
    <?php wp_head(); ?>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&display=swap');
</style>
<body x-data="mc_app" class="text-gray-600 font-roboto">

<?php
get_template_part("template-parts/navbar");
?>
