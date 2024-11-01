<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$category      = get_the_terms( get_the_ID(), 'wp_space_category' );
$category_name = '';

if ( ! empty( $category ) ) {
	$category_name = $category['0']->name;
}

?>

<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="space-list-wrap">
		<a href="<?php the_permalink(); ?>">
			<div class="space-cover-img" style="background-image: url('<?php echo esc_url( get_cover_image() ); ?>')">

				<?php if ( '' !== $category_name ) : ?>

					<span class="space-category"><?php echo esc_html( $category_name ); ?></span>

				<?php endif; ?>

			</div>
		</a>
		<div class="space-content-wrap">
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

			<h3>
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html( the_title() ); ?></a>
			</h3>
		</div>
	</div>
</li><!-- #post-## -->
