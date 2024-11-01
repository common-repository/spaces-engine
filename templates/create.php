<?php

namespace SpacesEngine;

$current = get_query_var( 'create-space-step' );
if ( ! $current ) {
	$current = 'details';
}

get_header();
?>

	<div id="buddypress" class="buddypress-wrap">
		<div id="create-space-form-wrapper" data-current="<?php echo esc_attr( $current ); ?>">

			<?php if ( is_user_logged_in() ) : ?>

			<div class="item-body" id="create-space-body">
					<nav class="<?php bp_nouveau_groups_create_steps_classes(); ?>" id="space-create-tabs" role="navigation" aria-label="<?php esc_attr_e( 'space creation menu', 'wpe-wps' ); ?>">
						<ol class="space-create-buttons button-tabs">

							<?php wp_kses_post( get_creation_step_tabs() ); ?>

						</ol>
					</nav>

					<?php
					$step = get_query_var( 'create-space-step' );

					if ( $step ) {
						spaces_get_template_part( 'create', $step );
					} else {
						spaces_get_template_part( 'create', 'details' );
					}
					?>
					<input type="hidden" name="space-id" id="space-id" value="<?php echo get_id_by_var() ? esc_attr( get_id_by_var() ) : ''; ?>">

				<div class="create-space-navigation">
					<aside style="display: none" class="bp-feedback bp-messages bp-template-notice">
						<span class="bp-icon" aria-hidden="true"></span>
						<p></p>
					</aside>

					<?php wp_kses_post( get_creation_step_buttons() ); ?>
				</div>
		</div><!-- .item-body -->
			<?php else : ?>
				<div class="logged-out-message">
					<?php
					printf(
					// translators: Placeholder %s is the singular label of the space post type.
						esc_html__( 'Please log in to create a %s', 'wpe-wps' ),
						esc_html( get_singular_label() )
					);
					?>
				</div>
			<?php endif; ?>
		</div>

	</div>

<?php

get_footer();
