<?php

function install_styles() {
	wp_enqueue_style( 'windicss', get_template_directory_uri() . "/assets/styles/windi.css" );
	wp_enqueue_script( "vue-js", get_template_directory_uri() . "/assets/javascript/lib/vue.js" );
	wp_enqueue_script( "axios", get_template_directory_uri() . "/assets/javascript/lib/axios.js" );
	wp_enqueue_script( "widgets", get_template_directory_uri() . "/assets/javascript/app/widgets.js" );
}

add_action( 'wp_enqueue_scripts', 'install_styles' );