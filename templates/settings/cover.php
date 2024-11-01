<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="space-setting-cover-content" class="tab-content settings-tab-content no-submit">

	<?php do_action( 'spaces_engine_before_cover_settings' ); ?>

	<div id="create-space-cover-image" class="create-space-panel cover-image" data-step="cover-image">
		<h3 class="space-screen-title">
			<?php
			printf(
			// translators: Placeholder %s is the singular label of the space post type.
				esc_html__( '%s Cover Image', 'wpe-wps' ),
				esc_html( get_singular_label() )
			);
			?>
		</h3>

		<div class="description">
			<p class="bp-help-text">
				<?php
				printf(
				// translators: Placeholder %s is the singular label of the space post type.
					esc_html__( 'To change or delete your %1$s cover image, click the button below.', 'wpe-wps' ),
					esc_html( strtolower( get_singular_label() ) ),
					esc_html( strtolower( get_singular_label() ) )
				);
				?>
			</p>
		</div>

		<a href="<?php echo esc_url( get_cover_image_edit_link() ) . '?settings-tab=true'; ?>" class="button"><?php esc_html_e( 'Edit Cover Image', 'wpe-wps' ); ?></a>
	</div>

	<?php do_action( 'spaces_engine_after_cover_settings' ); ?>


</div>