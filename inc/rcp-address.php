<?php
/**
 * Adds a custom textarea field to the registration form and profile editor.
 */
function bcbg_rcp_add_address_field() {

    $address = get_user_meta( get_current_user_id(), 'rcp_customer_address', true );
    ?>
    <p>
        <label for="rcp_customer_address"><?php _e( 'Business Address', 'rcp' ); ?></label>
        <textarea id="rcp_customer_address" name="rcp_customer_address"><?php echo esc_textarea( $address ); ?></textarea>
    </p>

    <?php
}

add_action( 'rcp_after_password_registration_field', 'bcbg_rcp_add_address_field' );
add_action( 'rcp_profile_editor_after', 'bcbg_rcp_add_address_field' );

/**
 * Adds the custom textarea field to the member edit screen.
 */
function bcbg_rcp_add_address_member_edit_field( $user_id = 0 ) {

    $address = get_user_meta( $user_id, 'rcp_customer_address', true );
    ?>
    <tr valign="top">
        <th scope="row" valign="top">
            <label for="rcp_customer_address"><?php _e( 'Business Address', 'rcp' ); ?></label>
        </th>
        <td>
            <textarea id="rcp_customer_address" name="rcp_customer_address" class="large-text" rows="10" cols="30"><?php echo esc_textarea( $address ); ?></textarea>
        </td>
    </tr>
    <?php
}

add_action( 'rcp_edit_member_after', 'bcbg_rcp_add_address_member_edit_field' );

/**
 * Determines if there are problems with the registration data submitted.
 * Remove this block if you want the textarea to be optional.
 */
function bcbg_rcp_validate_address_on_register( $posted ) {

    if ( rcp_get_subscription_id() ) {
        return;
    }

    if ( empty( $posted['rcp_customer_address'] ) ) {
        rcp_errors()->add( 'invalid_customer_address', __( 'Please enter an address', 'rcp' ), 'register' );
    }

}

add_action( 'rcp_form_errors', 'bcbg_rcp_validate_address_on_register', 10 );

/**
 * Stores the information submitted during registration.
 */
function bcbg_rcp_save_address_field_on_register( $posted, $user_id ) {

    if ( ! empty( $posted['rcp_customer_address'] ) ) {
        update_user_meta( $user_id, 'rcp_customer_address', wp_filter_nohtml_kses( $posted['rcp_customer_address'] ) );
    }

}

add_action( 'rcp_form_processing', 'bcbg_rcp_save_address_field_on_register', 10, 2 );

/**
 * Stores the information submitted during profile update.
 */
function bcbg_rcp_save_address_field_on_profile_save( $user_id ) {

    if ( ! empty( $_POST['rcp_customer_address'] ) ) {
        update_user_meta( $user_id, 'rcp_customer_address', wp_filter_nohtml_kses( $_POST['rcp_customer_address'] ) );
    }

}

add_action( 'rcp_user_profile_updated', 'bcbg_rcp_save_address_field_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'bcbg_rcp_save_address_field_on_profile_save', 10 );