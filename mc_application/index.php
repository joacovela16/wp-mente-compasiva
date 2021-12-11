<?php
get_header();
get_template_part("template-parts/loader");
?>

<?php if (is_home()): ?>
    <?php get_template_part("template-parts/main"); ?>
    <div class="mx-auto container py-3">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>
<?php else: ?>
        <?php get_template_part("template-parts/content", get_post_type()); ?>
<?php endif; ?>
<?php get_footer() ?>