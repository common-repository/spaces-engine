<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php do_action( 'wpe_wps_above_filters' ); ?>

<div id="wpe-wps-index-filter-wrapper">
	<div id="wpe-wps-index-filters" class="wpe-wps-form">

		<div class="form-group wpe-wps-text-group">
			<input
				type="text"
				class="input-text"
				name="wpe_wps_spaces_search"
				id="wpe-wps-spaces-search"
			>
			<label for="wpe-wps-spaces-search">
			<?php
			printf(
			// translators: Placeholder %s is the plural label of the space post type.
				esc_html__( 'Search %s', 'wpe-wps' ),
				esc_html( get_plural_label() )
			);
			?>
			</label>

		</div>

		<?php
			echo '<div class="form-group">';
			$cats = wp_dropdown_categories(
				array(
					'taxonomy'          => 'wp_space_category',
					'hierarchical'      => 1,
					'show_option_none'  => esc_html__( 'All Categories', 'wpe-wps' ),
					'option_none_value' => '',
					'name'              => 'wpe_wps_category_filter',
					'id'                => 'wpe-wps-category-dropdown',
					'value_field'       => 'slug',
					'hide_empty'        => 0,
				)
			);
			echo '</div>';

			?>
	</div>
</div>

<?php do_action( 'wpe_wps_below_filters' ); ?>
