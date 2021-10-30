<?php
function mc_get_datetime() {
	return date( "Y-m-d H:i:s" );
}

function runQuery( array $q ) {
	global $wpdb;
	foreach ( $q as $item ) {
		$wpdb->query( $item );
	}
}