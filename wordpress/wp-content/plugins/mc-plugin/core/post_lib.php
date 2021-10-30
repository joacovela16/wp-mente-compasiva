<?php

function mc_do_post(): void {
	foreach ( mc_obtain_model() as $k => $v ) {
		$result = register_post_type( $k, $v );
		if ( is_wp_error( $result ) ) {
			error_log( $result->get_error_message() );
		} else {
			error_log( $k . " active" );
		}
	}
}

function mc_undo_post() {
	foreach ( mc_obtain_model() as $k => $v ) {
		$result = unregister_post_type( $k );
		if ( is_wp_error( $result ) ) {
			error_log( $result->get_error_message() );
		} else {
			error_log( "Post " . $k . " has been deleted." );
		}
	}
}


function mc_obtain_model(): array {
	return array(
		DIRECTORY_CATALOG => array(
			'labels' => array(
				"name"          => 'Directory Catalog',
				'singular_name' => 'Catalog'
			),
			'public' => true
		)
	);
}