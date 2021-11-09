<?php

function install_styles() {
	wp_enqueue_style( 'windicss', get_template_directory_uri() . "/assets/styles/windi.css" );
	wp_enqueue_script( "vue-js", get_template_directory_uri() . "/assets/javascript/lib/vue.js" );
	wp_enqueue_script( "axios", get_template_directory_uri() . "/assets/javascript/lib/axios.js" );

	$components = [ "VideoPlayer", "Footer", "Dropdown", "PostResume", "MainLayout", "Widgets" ];
	foreach ( $components as $lib ) {
		wp_enqueue_script( $lib, get_template_directory_uri() . "/assets/javascript/app/" . $lib . ".js" );
	}
}

add_action( 'wp_enqueue_scripts', 'install_styles' );