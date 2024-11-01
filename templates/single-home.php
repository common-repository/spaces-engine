<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
buddypress()->current_component = 'activity';
?>

<div class="buddypress-wrap">

	<?php if ( is_user_logged_in() && ( is_space_admin() ) ) : ?>

		<div class="bp-business-profile-post-form-wrapper">

			<?php bp_get_template_part( 'activity/post-form' ); ?>

		</div>
	<?php endif; ?>

	<div class="screen-content">

		<?php bp_nouveau_activity_hook( 'before_directory', 'list' ); ?>

		<div id="activity-stream" class="activity" data-bp-list="activity" >

			<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-activity-loading' ); ?></div>

		</div><!-- .activity -->

		<?php bp_nouveau_after_activity_directory_content(); ?>
	</div><!-- .screen-content -->
</div>
