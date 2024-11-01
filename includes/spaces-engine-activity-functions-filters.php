<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tell BuddyPress, once again, to use the Activity component. This ensures that elements
 * such as entry buttons look and feel as they should.
 *
 */
function bp_has_activities( $has_activities, $activities_template, $r ) {

	$url     = wp_get_referer();
	$post_id = url_to_postid( $url );
	if ( is_space_by_id( $post_id ) ) {
		global $bp;
		$bp->current_component = 'activity';
	}
	return $has_activities;
}
add_action( 'bp_has_activities', __NAMESPACE__ . '\bp_has_activities', 100, 3 );

/**
 * Set Space ID as the activity item id.
 */
function bp_activity_before_save( &$activity ) {

	$url     = wp_get_referer();
	$post_id = url_to_postid( $url );

	if ( ! is_space_by_id( $post_id ) ) {
		return;
	}

	if ( 'activity_comment' === $activity->type ) {
		return;
	}

	$activity->item_id      = $post_id;
	$activity->primary_link = get_the_permalink( $post_id );
	$activity->action       = '<a href="' . esc_attr( get_the_permalink( $post_id ) ) . '">' . esc_html( get_the_title( $post_id ) ) . ' </a>' . esc_html__( 'posted an update', 'wpe-wps' );
}
add_action( 'bp_activity_before_save', __NAMESPACE__ . '\bp_activity_before_save', 100, 1 );

/**
 * Save Space information to activity meta.
 */
function bp_activity_after_save( &$activity ) {
	$url     = wp_get_referer();
	$post_id = url_to_postid( $url );

	if ( ! is_space_by_id( $post_id ) ) {
		return;
	}

	if ( 'activity_comment' === $activity->type ) {
		return;
	}

	$activity_id = $activity->id;
	bp_activity_update_meta( $activity_id, '_is_space_activity', 1 );
	bp_activity_update_meta( $activity_id, '_space', $post_id );
}
add_action( 'bp_activity_after_save', __NAMESPACE__ . '\bp_activity_after_save', 100, 1 );

/**
 * Set a new activity action to show the activity originated from a Space.
 */
function update_activity_action( $action, $activity ) {

	$activity_id = $activity->id;

	$is_space = bp_activity_get_meta( $activity_id, '_is_space_activity', true );
	$space_id = bp_activity_get_meta( $activity_id, '_space', true );
	$post_as  = 'user';

	if ( 1 === (int) $is_space && '' !== $space_id && ( 'activity_update' === $activity->type || 'activity_comment' === $activity->type || 'rtmedia_comment_activity' === $activity->type ) ) {
		$user_link  = bp_core_get_userlink( $activity->user_id );
		$space_link = '<a href="' . esc_url( get_the_permalink( $space_id ) ) . '">' . esc_html( get_the_title( $space_id ) ) . '</a>';

		// Set the Activity update posted.
		if ( 'space' === $post_as ) {
			$action = sprintf(
			/* translators: 1: the user link. */
				esc_html__( '%1$s posted an update', 'wpe-wps' ),
				$space_link
			);
		} else {
			$action = sprintf(
			/* translators: 1: the user link. 2: the group link. */
				esc_html__( '%1$s posted an update in the %3$s %2$s', 'wpe-wps' ),
				$user_link,
				$space_link,
				esc_html( strtolower( get_singular_label() ) )
			);
		}
	}

	return apply_filters( 'bp_business_format_activity_actionb_business_activity_update', $action, $activity );
}
add_filter( 'bp_activity_new_update_action', __NAMESPACE__ . '\update_activity_action', 999, 2 );

/**
 * Ensures that Spaces only display activity they have created.
 *
 * @param string $bp_ajax_querystring The BP Ajax query string.
 * @param string $object The object type.
 *
 * @return string The modified BP Ajax query string.
 */
function hide_space_activity( $bp_ajax_querystring, $object ) {
	if ( 'activity' === $object ) {
		$url     = wp_get_referer();
		$post_id = url_to_postid( $url );

		if ( is_space_by_id( $post_id ) ) {
			$bp_ajax_querystring .= '&scope=public&primary_id=' . $post_id;
		}
	}

	if ( 'media' === $object && function_exists( 'buddypress' ) && buddypress()->buddyboss ) {
		$url     = wp_get_referer();
		$post_id = url_to_postid( $url );

		if ( is_space_by_id( $post_id ) ) {
			$bp_ajax_querystring .= '&primary_id=' . $group_id;
			global $bp;
			$bp->current_component = 'my-media';
		}
	}

	return $bp_ajax_querystring;
}
add_action( 'bp_ajax_querystring', __NAMESPACE__ . '\hide_space_activity', 100, 2 );
