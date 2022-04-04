<?php
get_header();
get_template_part("template-parts/loader");

// RfiybnZ6UCmS8by2kCJ62RUAVjwAfQY+TmfnyxPJD2Q=
?>

<?php if (is_home()): ?>
    <?php get_template_part("template-parts/main"); ?>
    <div class="mx-auto container py-3">
        <?php dynamic_sidebar(2); ?>
    </div>
<?php else: ?>
    <?php if (is_post_type_archive(CFT_DIRECTORY)): ?>
        <?php get_template_part(locate_template('archive-cft.php')) ?>
    <?php else: ?>
        <?php get_template_part("template-parts/content", get_post_type()); ?>
    <?php endif; ?>
<?php endif; ?>
<?php get_footer() ?>