<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="bp-wrap">

	<div id="item-body" class="item-body">

		<?php if ( get_the_content() ) : ?>
			<?php echo wp_kses_post( get_the_content() ); ?>
		<?php endif; ?>

	</div><!-- #item-body -->

</div><!-- .bp-wrap -->
