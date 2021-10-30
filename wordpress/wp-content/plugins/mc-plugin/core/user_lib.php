<?php
include_once "constants.php";

function mc_user_register_interceptor( int $user_id ) {

	$maybe_user = get_userdata( $user_id );
	if ( $maybe_user ) {

		$post_linked_to_user = wp_insert_post(
			array(
				"post_author" => $maybe_user->ID,
				"post_title"  => "Person entry: " . $maybe_user->display_name,
				"post_status" => "publish",
				"post_type"   => DIRECTORY_CATALOG,
				"meta_input"  => array(
					"kind"       => "person",
					"entity_ref" => $user_id,
				)
			)
		);

		if ( is_wp_error( $post_linked_to_user ) ) {
			error_log( $post_linked_to_user->get_error_message() );
		} else {

			error_log( "Post created for user " . $maybe_user->user_email );
			$result = wp_set_post_terms( $post_linked_to_user, array( TERM_PERSON ), CLASSIFICATION_TAXONOMY );

			if ( is_wp_error( $result ) ) {
				error_log( $result->get_error_message() );
			}
		}

	} else {
		error_log( "Can't found user " . $user_id );
	}
}

function mc_user_profile_updated() {

}

function wporg_usermeta_form_field_birthday( $user ){
	?>
	<h3>It's Your Birthday</h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="birthday">Birthday</label>
			</th>
			<td>
				<input type="date"
				       class="regular-text ltr"
				       id="birthday"
				       name="birthday"
				       value="<?= esc_attr( get_user_meta( $user->ID, 'birthday', true ) ) ?>"
				       title="Please use YYYY-MM-DD as the date format."
				       pattern="(19[0-9][0-9]|20[0-9][0-9])-(1[0-2]|0[1-9])-(3[01]|[21][0-9]|0[1-9])"
				       required>
				<p class="description">
					Please enter your birthday date.
				</p>
			</td>
		</tr>
	</table>
	<?php
}