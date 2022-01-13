<?php
//include_once 'constants.php';

/*function mc_process_post( WP_Post $post, WP_User $user ) {
	global $wpdb;
	$result = $wpdb->get_results( "select * from " . TABLE_RESOURCE . " where entity_ref = " . $post->ID );
	$terms  = wp_get_post_terms( $post->ID , "course");
	if ( is_null( $result ) || sizeof( $result ) == 0 ) {
		mc_create_md_from_post( $post, $user );
	} else {
		mc_update_md_from_post( $post, $user );
	}
}*/

/*function mc_create_md_from_post( WP_Post $post, WP_User $user ) {
	global $wpdb;
	$datetime = mc_get_datetime();

	$user_data = $user->data;
	$data      = array(
		"kind"              => "post",
		"creation_date"     => $datetime,
		"modification_date" => $datetime,
		"author"            => $user_data->user_email,
		"entity_ref"        => $post->ID
	);

	$result = $wpdb->insert( TABLE_RESOURCE, $data );
	error_log( $result );
}*/


/*function mc_update_md_from_post( WP_Post $post, WP_User $user ) {
	global $wpdb;
	$datetime = mc_get_datetime();
	$result   = $wpdb->update(
		TABLE_RESOURCE,
		array(
			"modification_date" => $datetime,
		),
		array(
			"entity_ref" => $post->ID
		)
	);

	error_log( $result );
}*/

