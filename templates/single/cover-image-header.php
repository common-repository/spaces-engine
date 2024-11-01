<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = array(
	'object_dir' => 'spaces',
	'item_id'    => get_id_by_var(),
);

$cover_src = bp_attachments_get_attachment( 'url', $args );

if ( $cover_src ) {
	$cover_image = $cover_src;
} else {
	$cover_image = default_cover_image( true );
}

$has_cover_image_position = '';

if ( is_bb() ) {
	$cover_image_position = get_post_meta( get_id_by_var(), 'bp_cover_position', true );

	if ( '' !== $cover_image_position ) {
		$has_cover_image_position = ' has-position';
	}
}

?>

<div id="cover-image-container">
	<div id="header-cover-image" class="<?php echo esc_attr( $has_cover_image_position ); ?>" <?php echo ! is_bb() ? 'style="background-image:url(' . esc_url( get_cover_image() ) . ');"' : ''; ?>>
		<?php if ( is_bb() ) : ?>
			<img decoding="async" class="header-cover-img" src="<?php echo esc_url( get_cover_image() ); ?>" alt="" <?php echo ( '' !== $cover_image_position ) ? ' style="top: ' . esc_attr( $cover_image_position ) . 'px"' : ''; ?>>
		<?php endif; ?>
		<a href="<?php echo esc_url( get_cover_image_edit_link() ); ?>" title="hfghfg" class="link-change-cover-image bp-tooltip" data-bp-tooltip-pos="right" data-bp-tooltip="Change Cover Photo">
			<i class=" bb-icon-bf bb-icon-camera"></i>
		</a>

		<?php if ( ! empty( $cover_src ) && bp_is_item_admin() && is_bb() ) { ?>
			<a href="#" class="position-change-cover-image bp-tooltip" data-bp-tooltip-pos="right" data-bp-tooltip="<?php esc_attr_e( 'Reposition Cover Photo', 'buddyboss' ); ?>">
				<i class="bb-icon-bf bb-icon-arrows"></i>
			</a>
			<div class="header-cover-reposition-wrap">
				<a href="#" class="button small cover-image-cancel"><?php esc_html_e( 'Cancel', 'buddyboss' ); ?></a>
				<a href="#" class="button small cover-image-save space-cover-image-save"><?php esc_html_e( 'Save Changes', 'buddyboss' ); ?></a>
				<span class="drag-element-helper"><i class="bb-icon-l bb-icon-bars"></i><?php esc_html_e( 'Drag to move cover photo', 'buddyboss' ); ?></span>
				<img src="<?php echo esc_url( $cover_src ); ?>" alt="<?php esc_attr_e( 'Cover photo', 'buddyboss' ); ?>" />
			</div>
		<?php } ?>
	</div>

	<div class="container">
		<div id="item-header-cover-image">
			<div class="avatar-top">
				<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 76 31" xml:space="preserve">
				<path class="st0" d="M57.7,11.4c-1.4-1.4-2.7-2.9-4.1-4.4c-0.2-0.3-0.5-0.5-0.8-0.8c-0.2-0.2-0.3-0.3-0.5-0.5l0,0
	C48.6,2.2,43.5,0,38,0S27.4,2.2,23.6,5.7l0,0c-0.2,0.2-0.3,0.3-0.5,0.5c-0.3,0.3-0.5,0.5-0.8,0.8c-1.4,1.5-2.7,3-4.1,4.4
	c-5,5.1-11.7,6.1-18.3,6.3V31h9.4h8.9h39.4h4.9H76V17.6C69.4,17.4,62.7,16.5,57.7,11.4z"></path>
</svg>
				<div class="avatar-image">
					<?php echo wp_kses_post( get_space_avatar() ); ?>
				</div>
			</div>
			<div id="item-header-content">

				<?php do_action( 'spaces_engine_header_content_start' ); ?>

				<div class="item-title">

					<?php do_action( 'spaces_engine_title_start' ); ?>

					<h2 class="user-nicename"><?php the_title(); ?></h2>

					<?php do_action( 'spaces_engine_title_end' ); ?>

				</div>

				<?php do_action( 'spaces_engine_header_content_end' ); ?>

			</div>
			<div id="item-actions" class="space-item-actions">
				<div class="space-primary-buttons" >
					<?php do_action( 'spaces_engine_primary_buttons_start' ); ?>

					<?php echo get_action_button( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

					<?php do_action( 'spaces_engine_primary_buttons_end' ); ?>
				</div>
			</div>

		</div><!-- #item-header-cover-image -->
	</div><!-- .container -->

</div><!-- #cover-image-container -->
