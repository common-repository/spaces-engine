<?php

namespace SpacesEngine;

?>

<div id="create-space-profile-image" class="create-space-panel profile-image" data-step="profile-image">
	<h3 class="bp-screen-title">
		<?php
		printf(
		// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( 'Upload %s Profile Photo', 'wpe-wps' ),
			esc_html( get_singular_label() )
		);
		?>
	</h3>

	<div class="viewer">
		<?php echo wp_kses_post( get_space_avatar() ); ?>
	</div>

	<div class="description">
		<p class="bp-help-text">
			<?php
			printf(
			// translators: Placeholder %s is the singular label of the space post type.
				esc_html__( 'Upload an image to use as a profile photo for this %1$s. The image will be shown on the main %2$s page, and in search results.', 'wpe-wps' ),
				esc_html( strtolower( get_singular_label() ) ),
				esc_html( strtolower( get_singular_label() ) )
			);
			?>
	</div>

	<div class="space-upload-container" >

		<?php if ( 'upload-image' === bp_get_avatar_admin_step() ) : ?>
				<div class="main-column">
			<?php


			/**
			 * Load the Avatar UI templates
			 *
			 * @since 2.3.0
			 */
			bp_avatar_get_templates();

			wp_nonce_field( 'bp_avatar_upload' );
			?>

			<?php
		endif;

		if ( 'crop-image' === bp_get_avatar_admin_step() ) :
			?>

		<h2><?php esc_html_e( 'Crop Group Profile Photo', 'buddypress' ); ?></h2>

		<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php esc_attr_e( 'Profile photo to crop', 'buddypress' ); ?>" />

		<div id="avatar-crop-pane">
			<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php esc_attr_e( 'Profile photo preview', 'buddypress' ); ?>" />
		</div>

		<input type="submit" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php esc_attr_e( 'Crop Image', 'buddypress' ); ?>" />

		<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
		<input type="hidden" id="x" name="x" />
		<input type="hidden" id="y" name="y" />
		<input type="hidden" id="w" name="w" />
		<input type="hidden" id="h" name="h" />

			<?php
			wp_nonce_field( 'bp_avatar_cropstore' );
			?>

		<?php endif; ?>

	</div>
</div>



