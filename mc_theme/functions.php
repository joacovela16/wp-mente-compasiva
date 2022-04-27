<?php
include_once "template-parts/utils.php";
include_once "constants.php";
include_once "template-parts/dom.php";
include_once "template-parts/assets_installer.php";

add_action('after_setup_theme', 'rad_theme_setup');
add_action("wp_head", "mc_wp_head");
add_filter('show_admin_bar', '__return_false');
add_action('login_head', 'mc_inject_favicon');
add_action('wp_head', 'mc_inject_favicon');
add_action("init", 'mc_init_function');
add_filter('template_include', 'mc_template_include');
add_action('login_header', 'mc_login_header');
add_filter('login_display_language_dropdown', 'mc_login_display_language_dropdown');
;
function mc_login_display_language_dropdown(): bool
{
    return false;
}

function rad_theme_setup()
{
    load_theme_textdomain('default', get_template_directory() . '/languages');
}

function mc_template_include($template)
{
    global $wp_query;
    $post_type = get_query_var('post_type');
    if ($wp_query->is_search && $post_type == MC_CFT) {
        return locate_template('template-parts/content-cft.php');  //  redirect to archive-search.php
    }
    return $template;
}

function mc_init_function()
{
    add_rewrite_rule("^profile\/?", "index.php?pagename=profile", 'top');
    register_nav_menu("header-menu", 'Header Menu');
}

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


function mc_inject_favicon()
{
    ?>
    <link rel="icon" type="image/x-icon" href="<?= get_stylesheet_directory_uri() . '/assets/images/favicon.png' ?>"/>
    <?php
}

function mc_login_header()
{
    if (!is_admin()) {
        $logo_cm = get_template_directory_uri() . "/assets/images/logo.png";
        ?>
        <div class="mt-5 text-center md:hidden z-10 text-white text-shadow">
            <div class="text-4xl sm:text-5xl mx-auto ">MENTE COMPASIVA</div>
            <div class="">
                <div class="italic text-sm sm:text-lg">Un proyecto de</div>
                <div class="text-lg">CULTIVAR LA MENTE</div>
                <div class="italic text-sm sm:text-lg">Salud mental y prácticas contemplativas</div>
            </div>
        </div>
        <div class="w-full md:w-1/2 h-full md:flex-row hidden md:flex">
            <div class="shapedividers_com-213 flex-1 h-full">
                <video class="object-cover absolute top-0 left-0 w-screen h-screen filter blur-sm" autoplay muted loop src="<?= get_template_directory_uri() . "/assets/video/initial-video.mp4"
                ?>"></video>
                <div class="bg-transparent absolute top- left-0 w-full h-full flex ">
                    <div class="my-auto text-white w-full text-shadow-lg space-x-5">
                        <div class="text-center text-4xl sm:text-5xl mx-auto">MENTE COMPASIVA</div>
                        <div class="flex flex-row items-center justify-center sm:space-x-3 space-x-1 mt-10 sm:mt-20">
                            <img src="<?= $logo_cm ?>" alt="logo" class="w-20">
                            <div class="">
                                <div class="italic text-sm sm:text-lg">Un proyecto de</div>
                                <div class="font-bold text-lg sm:text-2xl">CULTIVAR LA MENTE</div>
                                <div class="italic text-sm sm:text-lg">Salud mental y prácticas contemplativas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}


add_action('login_header', function () {
    ?>
    <div class="fixed top-0 left-0 w-full h-full z-0 md:hidden">
        <video class="object-cover absolute top-0 left-0 w-screen h-screen filter blur-sm" autoplay muted loop src="<?= get_template_directory_uri() . "/assets/video/initial-video.mp4" ?>"</div>
    </div>
    <?php
});
