<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function space_header_template_part() {
	/**
	 * Fires before the display of a Spaces's header.
	 */
	do_action( 'bp_before_space_header' );

	// Get the template part for the header
	include SPACES_ENGINE_PLUGIN_DIR . '/templates/single/cover-image-header.php';

	/**
	 * Fires after the display of a Spaces's header.
	 */
	do_action( 'bp_after_space_header' );

	bp_nouveau_template_notices();
}

/**
 * Retrieves the menu items.
 *
 * This function retrieves the menu items for the custom navigation. The menu items are returned as an associative array,
 * where the keys are the item IDs and the values are the item names.
 *
 * @return array The menu items.
 */
function get_menu_items() {
	$items = get_screens();

	if ( ! is_space_admin() ) {
		unset( $items['settings'] );
	}

	return apply_filters( 'wpe_wps_custom_nav_items', $items );
}

function get_settings_tabs() {
	$settings_tabs = array(
		'general'           => esc_html__( 'General Settings', 'wpe-wps' ),
		'avatar'            => esc_html__( 'Photo', 'wpe-wps' ),
		'cover'             => esc_html__( 'Cover Image', 'wpe-wps' ),
		'action_button'     => esc_html__( 'Action Button', 'wpe-wps' ),
		'delete'            => sprintf(
		// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( 'Delete %s', 'wpe-wps' ),
			esc_html( strtolower( get_singular_label() ) )
		),
	);

	return apply_filters( 'spaces_engine_single_settings_tabs', $settings_tabs );
}

/**
 * Get account endpoint URL.
 */
function single_endpoint_url( $endpoint ) {
	if ( 'home' === $endpoint ) {
		return get_permalink();
	}

	return get_permalink() . $endpoint;
}

/**
 * Get account menu item classes.
 */
function get_menu_item_classes( $endpoint ) {
	global $wp;

	$classes = array(
		'space-tab',
		'space-navigation-link',
		'space-navigation-link--' . $endpoint,
	);

	$current = false;
	if ( get_query_var( 'active-space-tab' ) === $endpoint ) {
		$current = true;
	}

	// Set current item class.
	if ( 'home' === $endpoint && ( ! get_query_var( 'active-space-tab' ) ) ) {
		$current = true; // Dashboard is not an endpoint, so needs a custom check.
	}

	if ( $current ) {
		$classes[] = 'current';
		$classes[] = 'selected';
	}

	$classes = apply_filters( 'spaces_engine_get_space_menu_item_classes', $classes, $endpoint );

	return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
}

function get_space_meta( $key, $space_id = false ) {
	if ( ! $space_id ) {
		$space_id = get_the_ID();
	}

	$meta = get_post_meta( $space_id, 'space_' . $key, true );

	//ToDo: Back compat for previous meta

	return $meta;
}

function get_action_labels() {
	$action_labels = array(
		'buddypress_message' => esc_html__( 'Message', 'wpe-wps' ),
		'whatsapp_message'   => esc_html__( 'WhatsApp', 'wpe-wps' ),
		'phone'              => esc_html__( 'Call Now', 'wpe-wps' ),
		'email'              => esc_html__( 'Send Email', 'wpe-wps' ),
		'contact_us'         => esc_html__( 'Contact US', 'wpe-wps' ),
		'website'            => esc_html__( 'Learn More', 'wpe-wps' ),
		'website_video'      => esc_html__( 'Watch Now', 'wpe-wps' ),
		'visit_group'        => esc_html__( 'Visit Group', 'wpe-wps' ),
		'sign_up'            => esc_html__( 'Sign Up', 'wpe-wps' ),
		'start_order'        => esc_html__( 'Start Order', 'wpe-wps' ),
		'view_shop'          => esc_html__( 'Shop Now', 'wpe-wps' ),
		'get_tickets'        => esc_html__( 'Get Tickets', 'wpe-wps' ),
	);

	return apply_filters( 'spaces_engine_action_labels', $action_labels );
}

function get_action_button( $space_id ) {

	$user_id = bp_loggedin_user_id();

	if ( 0 === $user_id ) {
		return;
	}

	$action_button = get_space_meta( 'action_button', $space_id );

	if ( '' === $action_button || 'none' === $action_button ) {
		return;
	}

	$action_value = get_space_meta( 'action_button_' . $action_button, $space_id );

	$author_id = get_post_field( 'post_author', get_the_ID() );

	$action_labels = get_action_labels();
	$target        = '_blank';
	switch ( $action_button ) {
		case 'buddypress_message':
			$memberslug   = function_exists( 'bp_members_get_user_slug' ) ? bp_members_get_user_slug( $author_id ) : bp_core_get_username( $author_id );
			$action_value = wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . $memberslug );
			$target       = '';
			break;
		case 'whatsapp_message':
			$action_value = 'https://api.whatsapp.com/send?phone=' . rawurlencode( $action_value );
			break;
		case 'email':
			$action_value = 'mailto:' . $action_value;
			$target       = '';
			break;
		case 'phone':
			$action_value = 'tel:' . $action_value;
			$target       = '';
			break;
		default:
			break;
	}

	?>
	<a href="<?php echo esc_url( $action_value ); ?>" id="space-action-button" class="button primary" data-id="<?php echo esc_attr( $space_id ); ?>" title="<?php echo esc_html( $action_labels[ $action_button ] ); ?>" target="<?php echo esc_attr( $target ); ?>">
		<?php echo esc_html( $action_labels[ $action_button ] ); ?>
	</a>
	<?php
}
