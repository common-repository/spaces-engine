<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( wp_is_block_theme() ) {
	block_template_part('header');
}

get_header(); /*Header Portion*/

do_action( 'space_before_main_content' );
?>

<div id="primary" class="content-area has-global-padding">

	<main id="main" class="site-main buddypress-wrap" role="main">

		<?php do_action( 'before_single_space' ); ?>

		<div id="buddypress" class="space-single <?php echo esc_attr( bp_nouveau_get_container_classes() ); ?>" >

				<div id="item-header" role="complementary" data-bp-item-id="<?php the_ID(); ?>" data-bp-item-component="space" class="space-header single-headers">

					<?php do_action( 'spaces_engine_before_profile_header_part' ); ?>

					<?php space_header_template_part(); ?>

					<?php do_action( 'spaces_engine_after_profile_header_part' ); ?>

				</div><!-- #item-header -->

			<div class="bp-wrap">

				<aside class="hidden bp-feedback primary-feedback bp-messages bp-template-notice">
					<span class="bp-icon" aria-hidden="true"></span>
					<p></p>
				</aside>

					<nav class="main-navs no-ajax bp-navs single-screen-navs space-main-nav" id="space-main-object-nav" role="navigation" aria-label="Space menu">
						<ul>

							<?php do_action( 'spaces_engine_before_space_menu_items' ); ?>

							<?php
							foreach ( get_menu_items() as $endpoint => $label ) :

								?>
								<li class="<?php echo esc_attr( get_menu_item_classes( $endpoint ) ); ?>">
									<a href="<?php echo esc_url( single_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
								</li>
							<?php endforeach; ?>

							<?php do_action( 'spaces_engine_after_space_menu_items' ); ?>

						</ul>
					</nav>

			</div><!-- .bp-wrap -->

				<?php
				if ( get_query_var( 'active-space-tab' ) ) {
					spaces_get_template_part( 'single', get_query_var( 'active-space-tab' ) );
				} else {
					spaces_get_template_part( 'single', 'home' );
				}
				?>

		</div>

		<?php do_action( 'after_single_space' ); ?>

	</main> <!-- Main tag finish -->

</div>

<?php
/**
 * Hook: bp_business_profile_after_main_content.
 */
do_action( 'space_after_main_content' );

if( wp_is_block_theme() ) {
	block_template_part('footer');
}

get_footer();
