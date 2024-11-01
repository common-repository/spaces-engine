<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$space_id = get_the_ID();

$space_category = get_terms(
	array(
		'taxonomy'   => 'wp_space_category',
		'hide_empty' => false,
	)
);

$selected_category = wp_get_post_terms( $space_id, 'wp_space_category' );
$category_id       = 0;
if ( ! empty( $selected_category ) ) {
	$category_id = $selected_category[0]->term_id;
}
?>

<div id="space-setting-general-content" class="tab-content settings-tab-content active">
	<h3 class="space-screen-title"><?php esc_html_e( 'General Settings', 'wpe-wps' ); ?></h3>

	<?php do_action( 'spaces_engine_before_general_settings' ); ?>

	<fieldset class="space-info">
		<label for="space-name">
			<?php
			printf(
			// translators: Placeholder %s is the singular label of the space post type.
				esc_html__( '%s Name (required)', 'wpe-wps' ),
				esc_html( get_singular_label() )
			);
			?>
		</label>
		<input type="text" name="title" id="space-name" value="<?php echo esc_attr( get_the_title() ); ?>" aria-required="true">
	</fieldset>

	<fieldset class="space-info">
		<label for="space-desc">
			<?php
			printf(
			// translators: Placeholder %s is the singular label of the space post type.
				esc_html__( '%s Description (required)', 'wpe-wps' ),
				esc_html( get_singular_label() )
			);
			?>
		</label>
		<?php
		$args = array(
			'tinymce' => array(
				'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
				'toolbar2' => '',
				'toolbar3' => '',
			),
		);


		wp_editor( get_the_content(), 'description', $args );
		?>
	</fieldset>

	<?php if ( ! empty( $space_category ) ) : ?>
		<fieldset class="space-info">
			<label for="space-category">
				<?php
				printf(
				// translators: Placeholder %s is the singular label of the space post type.
					esc_html__( '%s Category', 'wpe-wps' ),
					esc_html( get_singular_label() )
				);
				?>
			</label>
			<select name="category" id="space-category">
					<option value="">
						<?php
						printf(
						// translators: Placeholder %s is the singular label of the space post type.
							esc_html__( 'Select %s category', 'wpe-wps' ),
							esc_html( strtolower( get_singular_label() ) )
						);
						?>
					</option>

				<?php foreach ( $space_category as $category ) : ?>
					<option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category_id, $category->term_id ); ?>><?php echo esc_html( $category->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</fieldset>
	<?php endif; ?>

	<?php do_action( 'spaces_engine_after_general_settings' ); ?>


</div>