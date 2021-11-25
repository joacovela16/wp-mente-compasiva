<?php

include_once "template-parts/widgets/MCLastPost.php";

add_action('wp_enqueue_scripts', 'mc_install_assets');
add_action('enqueue_block_editor_assets', 'mc_install_assets');
add_action('widgets_init', 'mc_widgets_init');
add_action("wp_footer", "svelte_installer");
add_filter('show_admin_bar', '__return_false');

function svelte_installer()
{
    $uri = get_template_directory_uri() . "/components/dist/mc-lib.es.js";
    ?>
    <script type='module'>
        // @formatter:off
        import {renderApp} from '<?= $uri ?>';
        // @formatter:on

        renderApp(
            'mc-app',
            {
                mainContent: `<?php dynamic_sidebar('sidebar-1') ?>`,
                adminUrl: '<?= admin_url('admin-ajax.php') ?>'
            }
        );
    </script>
    <?php

}

function mc_widgets_init()
{
    register_widget("MC_Last_Post");
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for Block Styles.
    add_theme_support('wp-block-styles');

    register_sidebar(array(
        'name' => __('Main content', 'mc_theme'),
        'id' => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

function mc_install_assets()
{
    wp_enqueue_style('windicss', get_template_directory_uri() . "/assets/styles/windi.css");
//	wp_enqueue_script( "vue-js", get_template_directory_uri() . "/assets/javascript/lib/vue.js" );
//	wp_enqueue_script( "axios", get_template_directory_uri() . "/assets/javascript/lib/axios.js" );
    wp_enqueue_style("svelte-lib", get_template_directory_uri() . "/components/dist/style.css");

//    $components = ["VideoPlayer", "Footer", "Dropdown", "PostResume", "MainLayout", "Widgets", "TodoWidget"];
//    foreach ($components as $lib) {
//        wp_enqueue_script($lib, get_template_directory_uri() . "/assets/javascript/app/" . $lib . ".js");
//    }
}
