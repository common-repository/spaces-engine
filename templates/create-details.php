<?php

namespace SpacesEngine;

?>

<div id="create-space-details" class="create-space-panel details" data-step="details">
	<form action="" method="post" id="create-space-form" enctype="multipart/form-data" data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_space' ) ); ?>">
	<h3 class="bp-screen-title">
		<?php
		printf(
		// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( 'Enter %s Details', 'wpe-wps' ),
			esc_html( get_singular_label() )
		);
		?>
	</h3>
	<div class="form-group">
		<label class="field-name" for="space-name"><?php esc_html_e( 'Title (required)', 'wpe-wps' ); ?></label>
		<p class="field-description"><?php esc_html_e( 'Use a name that defines your page or the name of your company, brand, or organisation.', 'wpe-wps' ); ?></p>
		<input type="text" name="space_name" id="space-name">
	</div>

	<div class="form-group">
		<label class="field-name" for="space-description"><?php esc_html_e( 'Description (required)', 'wpe-wps' ); ?></label>
		<p class="field-description"><?php esc_html_e( 'Briefly explain what your company, brand, or organisation does.', 'wpe-wps' ); ?></p>
		<?php
		$args = array(
			'tinymce' => array(
				'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
				'toolbar2' => '',
				'toolbar3' => '',
			),
		);


		wp_editor( '', 'space-description', $args );
		?>
	</div>

	<div class="form-group">
		<label class="field-name" for="wpe-wps-category-dropdown"><?php esc_html_e( 'Category', 'wpe-wps' ); ?></label>
		<p class="field-description"><?php esc_html_e( 'Enter a category that describes your company, brand, or organisation.', 'wpe-wps' ); ?></p>
		<?php
		$cats = wp_dropdown_categories(
			array(
				'taxonomy'          => 'wp_space_category',
				'hierarchical'      => 1,
				'show_option_none'  => esc_html__( 'Select category', 'wpe-wps' ),
				'option_none_value' => '',
				'name'              => 'wpe_wps_category_filter',
				'id'                => 'wpe-wps-category-dropdown',
			)
		);

		?>
	</div>
	</form>
</div>



