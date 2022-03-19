<?php
include_once "template-parts/utils.php";
include_once "constants.php";
include_once "template-parts/dom.php";
include_once "template-parts/assets_installer.php";
add_action('after_setup_theme', 'rad_theme_setup');

function rad_theme_setup()
{
    load_theme_textdomain('default', get_template_directory() . '/languages');
}

add_action("wp_head", "mc_wp_head");
add_filter('show_admin_bar', '__return_false');

add_action("init", function () {
    add_rewrite_rule("^profile\/?", "index.php?pagename=profile", 'top');
    register_nav_menu("header-menu", 'Header Menu' );
});

add_filter('template_include', function ($template){
    global $wp_query;
    $post_type = get_query_var('post_type');
    if( $wp_query->is_search && $post_type == MC_CFT )
    {
        return locate_template('template-parts/content-cft.php');  //  redirect to archive-search.php
    }
    return $template;
});

function mc_wp_head()
{
    $nonce = wp_create_nonce('wp_rest');
    ?>
    <script>
        window.MCApp = window.MCApp || {
            nonce: "<?= $nonce ?>",
            baseUrl: "<?= get_rest_url() ?>",
        };
    </script>
    <?php
}

add_action('login_header', function () {
    if (!is_admin()) {
        ?>
        <div class="absolute top-0 left-0 w-full h-full flex flex-col sm:flex-row">
            <div class="shapedividers_com-213  flex-1 h-full ">
                <video class="object-cover absolute top-0 left-0 w-screen h-screen" autoplay muted loop src="<?= get_template_directory_uri() . "/assets/video/initial-video.mp4" ?>"></video>
                <div class="bg-transparent absolute top- left-0 w-full h-full flex ">
                    <p class="text-center my-auto text-white text-6xl w-full text-shadow-lg">Mente Compasiva</p>
                </div>
            </div>
            <div class="flex-1"></div>
        </div>
        <?php
    }
});

