<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$button_text = sprintf(
// translators: Placeholder %s is the singular label of the space post type.
	esc_html__( 'Delete %s', 'wpe-wps' ),
	esc_html( get_singular_label() )
);

?>

<div id="space-setting-delete-content" class="tab-content settings-tab-content no-submit">

	<?php do_action( 'spaces_engine_before_delete_settings' ); ?>

	<h3 class="space-screen-title">
		<?php
		printf(
			// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( 'Delete this %s', 'wpe-wps' ),
			esc_html( get_singular_label() )
		);
		?>
	</h3>

	<?php bp_nouveau_user_feedback( 'space-delete-warning' ); ?>

	<label for="delete-space-understand" class="bp-label-text warn">
		<input type="checkbox" name="delete-space-understand" id="delete-space-understand" value="1" onclick="if(this.checked) { document.getElementById( 'delete-space-button' ).disabled = ''; } else { document.getElementById( 'delete-space-button' ).disabled = 'disabled'; }" />
		<?php
		printf(
			// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( 'I understand the consequences of deleting this %s', 'wpe-wps' ),
			esc_html( get_singular_label() )
		);
		?>
	</label>

	<div class="submit">
		<input type="submit" disabled="disabled" value="<?php echo esc_attr( $button_text ); ?>" id="delete-space-button" name="delete-space-button">
	</div>

	<?php do_action( 'spaces_engine_after_delete_settings' ); ?>


</div>
