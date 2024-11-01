<?php

namespace SpacesEngine;

bp_attachments_enqueue_scripts( 'BP_Attachment_Cover_Image' );

$args = array(
	'object_dir' => 'spaces',
	'item_id'    => get_id_by_var(),
);

$cover_src = bp_attachments_get_attachment( 'url', $args );
?>

<div id="create-space-cover-image" class="create-space-panel cover-image group-create" data-step="cover-image">
	<h3 class="bp-screen-title">
		<?php
		printf(
		// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( 'Upload %s Cover Image', 'wpe-wps' ),
			esc_html( get_singular_label() )
		);
		?>
	</h3>

	<?php if ( 'BuddyBoss Theme' == wp_get_theme()->name || 'BuddyBoss Theme' == wp_get_theme()->parent_theme ) : ?>
		<div id="header-cover-image" style="background-image: url('<?php echo esc_url( $cover_src ); ?>')"></div>
	<?php else : ?>
		<div id="header-cover-image" style="background-image: url('<?php echo esc_url( $cover_src ); ?>')"></div>
	<?php endif; ?>

	<p>
	<?php
		printf(
		// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( 'The Cover Image will be used to customize the header of your %s.', 'wpe-wps' ),
			esc_html( get_singular_label() )
		);
		?>
	</p>

<?php bp_attachments_get_template_part( 'cover-images/index' ); ?>
</div>



