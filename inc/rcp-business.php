<?php
/**
 * Adds the custom fields to the registration form and profile editor
 *
 */
function bcbg_rcp_add_user_fields() {
	
	$business_phone = get_user_meta( get_current_user_id(), 'rcp_business_phone', true );
	$business_name   = get_user_meta( get_current_user_id(), 'rcp_business_name', true );
	?>
	<p>
		<label for="rcp_business_phone"><?php _e( 'Your Business Phone', 'rcp' ); ?></label>
		<input name="rcp_business_phone" id="rcp_business_phone" type="text" value="<?php echo esc_attr( $business_phone ); ?>"/>
	</p>
	<p>
		<label for="rcp_business_name"><?php _e( 'Your Business Name', 'rcp' ); ?></label>
		<input name="rcp_business_name" id="rcp_business_name" type="text" value="<?php echo esc_attr( $business_name ); ?>"/>
	</p>
	<?php
}
add_action( 'rcp_after_password_registration_field', 'bcbg_rcp_add_user_fields' );
add_action( 'rcp_profile_editor_after', 'bcbg_rcp_add_user_fields' );

/**
 * Adds the custom fields to the member edit screen
 *
 */
function bcbg_rcp_add_member_edit_fields( $user_id = 0 ) {
	
	$business_phone = get_user_meta( $user_id, 'rcp_business_phone', true );
	$business_name   = get_user_meta( $user_id, 'rcp_business_name', true );
	?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_business_phone"><?php _e( 'Business Phone', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="rcp_business_phone" id="rcp_business_phone" type="text" value="<?php echo esc_attr( $business_phone ); ?>"/>
			<p class="description"><?php _e( 'The member\'s Business Phone', 'rcp' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_business_phone"><?php _e( 'Business Name', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="rcp_business_name" id="rcp_business_name" type="text" value="<?php echo esc_attr( $business_name ); ?>"/>
			<p class="description"><?php _e( 'The member\'s Business Name', 'rcp' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'rcp_edit_member_after', 'bcbg_rcp_add_member_edit_fields' );


/**
 * Determines if there are problems with the registration data submitted
 *
 */
function bcbg_rcp_validate_user_fields_on_register( $posted ) {
	if ( rcp_get_subscription_id() ) {
	   return;
    	}
	if( empty( $posted['rcp_business_phone'] ) ) {
		rcp_errors()->add( 'invalid_business_phone', __( 'Please enter your Business Phone', 'rcp' ), 'register' );
	}
	if( empty( $posted['rcp_business_name'] ) ) {
		rcp_errors()->add( 'invalid_business_name', __( 'Please enter your Business Name', 'rcp' ), 'register' );
	}
}
add_action( 'rcp_form_errors', 'bcbg_rcp_validate_user_fields_on_register', 10 );



/**
 * Stores the information submitted during registration
 *
 */
function bcbg_rcp_save_user_fields_on_register( $posted, $user_id ) {
	if( ! empty( $posted['rcp_business_phone'] ) ) {
		update_user_meta( $user_id, 'rcp_business_phone', sanitize_text_field( $posted['rcp_business_phone'] ) );
	}
	if( ! empty( $posted['rcp_business_name'] ) ) {
		update_user_meta( $user_id, 'rcp_business_name', sanitize_text_field( $posted['rcp_business_name'] ) );
	}
}
add_action( 'rcp_form_processing', 'bcbg_rcp_save_user_fields_on_register', 10, 2 );


/**
 * Stores the information submitted profile update
 *
 */
function bcbg_rcp_save_user_fields_on_profile_save( $user_id ) {
	if( ! empty( $_POST['rcp_business_phone'] ) ) {
		update_user_meta( $user_id, 'rcp_business_phone', sanitize_text_field( $_POST['rcp_business_phone'] ) );
	}
	if( ! empty( $_POST['rcp_business_name'] ) ) {
		update_user_meta( $user_id, 'rcp_business_name', sanitize_text_field( $_POST['rcp_business_name'] ) );
	}
}
add_action( 'rcp_user_profile_updated', 'bcbg_rcp_save_user_fields_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'bcbg_rcp_save_user_fields_on_profile_save', 10 );