<?php
/**
 * The plugin file
 *
 * @link              https://www.briancoords.com
 * @since             1.0.0
 * @package           BC_Business_Guards_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       Business Guards Custom Addon (Brian Coords)
 * Plugin URI:        https://www.cbcustommodules.com
 * Description:       Adds additional functionality for Business Guards Site.
 * Version:           1.0.3
 * Author:            Brian Coords
 * Author URI:        https://www.briancoords.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bg-business-guards
 * Domain Path:       /languages
 */

require_once('inc/rcp-business.php');
require_once('inc/rcp-address.php');
require_once('inc/rest-filter.php');
require_once('inc/wp-query-multisite.php');
require_once('inc/search-meta.php');

add_action( 'init', 'codex_book_init' );
/**
 * Register a book post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function codex_book_init() {
	$labels = array(
		'name'               => _x( 'Complaints', 'post type general name', 'bg-business-guards' ),
		'singular_name'      => _x( 'Complaint', 'post type singular name', 'bg-business-guards' ),
		'menu_name'          => _x( 'Complaints', 'admin menu', 'bg-business-guards' ),
		'name_admin_bar'     => _x( 'Complaint', 'add new on admin bar', 'bg-business-guards' ),
		'add_new'            => _x( 'Add New', 'Complaint', 'bg-business-guards' ),
		'add_new_item'       => __( 'Add New Complaint', 'bg-business-guards' ),
		'new_item'           => __( 'New Complaint', 'bg-business-guards' ),
		'edit_item'          => __( 'Edit Complaint', 'bg-business-guards' ),
		'view_item'          => __( 'View Complaint', 'bg-business-guards' ),
		'all_items'          => __( 'All Complaints', 'bg-business-guards' ),
		'search_items'       => __( 'Search Complaints', 'bg-business-guards' ),
		'parent_item_colon'  => __( 'Parent Complaints:', 'bg-business-guards' ),
		'not_found'          => __( 'No Complaints found.', 'bg-business-guards' ),
		'not_found_in_trash' => __( 'No Complaints found in Trash.', 'bg-business-guards' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'bg-business-guards' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'complaint' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author' ),
		  'public'       => true,
		  'show_in_rest' => true,
		  'label'        => 'Complaints'
	);

	register_post_type( 'complaint', $args );
}



add_action( 'cmb2_init', 'cmb2_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_sample_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '';

	/**
	 * Initiate the metabox
	 */
	$cmb = new_cmb2_box( array(
		'id'            => 'metabox',
		'title'         => __( 'Complaint Info Metabox', 'cmb2' ),
		'object_types'  => array( 'complaint', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_in_rest' => WP_REST_Server::READABLE,
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );
	
	$cmb->add_field( array(
		'name' => __( 'Consumer Email', 'cmb2' ),
//		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'consumer_email',
		'type' => 'text_email',
		'show_in_rest' => false,
		// 'repeatable' => true,
	) );

	$cmb->add_field( array(
		'name'       => __( 'Complaint Type', 'cmb2' ),
//		'desc'       => __( 'field description (optional)', 'cmb2' ),
		'id'         => $prefix . 'complaint_type',
		'type'       => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );

	$cmb->add_field( array(
		'name'       => __( 'Physical Address', 'cmb2' ),
//		'desc'       => __( 'field description (optional)', 'cmb2' ),
		'id'         => $prefix . 'physical_address',
		'type'       => 'textarea',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );

	$cmb->add_field( array(
		'name'       => __( 'Phone Number', 'cmb2' ),
//		'desc'       => __( 'field description (optional)', 'cmb2' ),
		'id'         => $prefix . 'phone',
		'type'       => 'text',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );

	$cmb->add_field( array(
		'name'       => __( 'Facebook URL', 'cmb2' ),
//		'desc'       => __( 'field description (optional)', 'cmb2' ),
		'id'         => $prefix . 'facebook',
		'type'       => 'text_url',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );

	$cmb->add_field( array(
		'name'       => __( 'Twitter URL', 'cmb2' ),
//		'desc'       => __( 'field description (optional)', 'cmb2' ),
		'id'         => $prefix . 'twitter',
		'type'       => 'text_url',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );

	$cmb->add_field( array(
		'name'       => __( 'Instagram URL', 'cmb2' ),
//		'desc'       => __( 'field description (optional)', 'cmb2' ),
		'id'         => $prefix . 'instagram',
		'type'       => 'text_url',
		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
	) );
	
//	
//	$cmb = new_cmb2_box( array(
//		'id'            => 'metabox',
//		'title'         => __( 'Complaint Author Information Metabox', 'cmb2' ),
//		'object_types'  => array( 'complaint', ), // Post type
//		'context'       => 'normal',
//		'priority'      => 'high',
//		'show_in_rest' => WP_REST_Server::READABLE,
//		'show_names'    => true, // Show field names on the left
//		// 'cmb_styles' => false, // false to disable the CMB stylesheet
//		// 'closed'     => true, // Keep the metabox closed by default
//	) );
//	
//	$cmb->add_field( array(
//		'name' => __( 'Post  Email', 'cmb2' ),
////		'desc' => __( 'field description (optional)', 'cmb2' ),
//		'id'   => $prefix . 'consumer_email',
//		'type' => 'text_email',
//		'show_in_rest' => false,
//		// 'repeatable' => true,
//	) );
//
//	$cmb->add_field( array(
//		'name'       => __( 'Complaint Type', 'cmb2' ),
////		'desc'       => __( 'field description (optional)', 'cmb2' ),
//		'id'         => $prefix . 'complaint_type',
//		'type'       => 'text',
//		'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
//		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
//		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
//		// 'on_front'        => false, // Optionally designate a field to wp-admin only
//		// 'repeatable'      => true,
//	) );

}


function bcbg_searchfilter($query) {

    if (!is_admin() && $query->is_main_query() && $query->is_search && rcp_is_active() ) {
		
		$query->set('multisite', 1);
		
		$query->set('sites__in', array(3));
		
        $query->set('post_type',array('complaint'));
	
    } 
	
//	if($query->is_search() && $_POST['meta_key']) {
//		
//        $query->query_vars['meta_key'] = $_POST['meta_key'];
//        $query->query_vars['meta_value'] = $_POST['meta_value'];
//        return;
//    }
 
	return $query;
}
 
add_filter('pre_get_posts','bcbg_searchfilter');



// Hide if fields empty
function bcbg_check_field_connections( $is_visible, $node ) {
	
	if ( isset( $node->settings->connections ) ) {
		foreach ( $node->settings->connections as $key => $connection ) {
			if ( ! empty( $connection ) && empty( $node->settings->$key ) ) {
				return false;
			}
		}
	}
	
	return $is_visible;
}

add_filter( 'fl_builder_is_node_visible', 'bcbg_check_field_connections', 10, 2 );