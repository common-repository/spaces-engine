<?php

namespace SpacesEngine;

$spaces_search_placeholder = sprintf(
	// translators: Placeholder %s is the plural label of the space post type.
	__( 'Search %s...', 'wpe-wps' ),
	get_plural_label()
);

if( wp_is_block_theme() ) {
	block_template_part('header');
}

get_header(); ?>

<?php do_action( 'wpe_wps_before_spaces_index' ); ?>

	<div id="primary" class="content-area has-global-padding">
		<div id="content" class="buddypress-wrap" role="main">

		<div id="space-archive-container" data-nonce="<?php echo esc_attr( wp_create_nonce( 'spaces-index' ) ); ?>" data-pagination="standard">
			<?php do_action( 'space_archive_start' ); ?>
				<header class="entry-header">
					<h1 class="entry-title"><?php get_plural_label(); ?></h1>
				</header>

				<nav class="spaces-type-navs main-navs bp-navs dir-navs  bp-subnavs" role="navigation" aria-label="Directory menu">
					<ul class="component-navigation spaces-nav" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpe-wps-index-scope' ) ); ?>">
						<li id="wpe-wps-index-all" class="wpe-wps-index-scope-link selected">
							<a data-scope="all" href="">
									<?php
									printf(
									/* translators: %s: The plural label for a Space */
										esc_html__( 'All %s', 'wpe-wps' ),
										esc_html( get_plural_label() ),
									);
									?>
							</a>
						</li>

						<?php if ( is_user_logged_in() ) : ?>
							<li id="wpe-wps-index-personal" class="wpe-wps-index-scope-link">
								<a data-scope="personal" href="">
										<?php
										printf(
										/* translators: %s: The plural label for a Space */
											esc_html__( 'My %s', 'wpe-wps' ),
											esc_html( get_plural_label() ),
										);
										?>
								</a>
							</li>

							<li id="wpe-wps-create-space-archive-link" class="no-ajax space-create create-button">
								<a href="<?php echo esc_url( get_post_type_archive_link( 'wpe_wpspace' ) . 'create-' . strtolower( get_singular_label() ) . '-page/' ); ?>">
									<?php
									printf(
									/* translators: %s: The singular label for a Space */
										esc_html__( 'Create a %s', 'wpe-wps' ),
										esc_html( get_singular_label() ),
									);
									?>
								</a>
							</li>
						<?php endif; ?>
					</ul><!-- .component-navigation -->
				</nav>

			<div id="subnav-filters" class="subnav-filters filters bp-secondary-header">
				<div class="subnav-search clearfix">
					<div class="dir-search space-search bp-search" data-bp-search="space">
						<form class="bp-dir-search-form">

							<label for="wpe-wps-spaces-search" class="bp-screen-reader-text">
								<?php echo esc_html( $spaces_search_placeholder ); ?>
							</label>
							<input
								type="text"
								class="input-text"
								name="wpe_wps_spaces_search"
								id="wpe-wps-spaces-search"
								placeholder="<?php echo esc_attr( $spaces_search_placeholder ); ?>">
						</form>
						<?php
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

						?>

					</div>

				</div>

				<select id="wpe-wps-index-ordering" name="wpe_wps_order_filter" data-placeholder="<?php esc_attr_e( 'Order by', 'wpe-wps' ); ?>">
					<option value="latest"><?php esc_html_e( 'Latest', 'wpe-wps' ); ?></option>
					<option value="alphabetical"><?php esc_html_e( 'Alphabetical', 'wpe-wps' ); ?></option>
				</select>
			</div>

			<div class="space-archive-wrapper"></div>
		</div>
		<?php do_action( 'space_archive_end' ); ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php do_action( 'wpe_wps_after_spaces_index' ); ?>

<?php

if( wp_is_block_theme() ) {
	block_template_part('footer');
}

get_footer(); ?>
