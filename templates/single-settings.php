<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="tabs space-settings-content">
	<nav class="sub-navs no-ajax bp-navs single-screen-subnavs space-settings-nav" id="space-object-nav" role="navigation" aria-label="Settings menu">
		<ul class="subnav" id="space-settings-tabs-nav">
			<?php
			foreach ( get_settings_tabs() as $slug => $name ) :
				$active_class = (isset($_GET['active']) && $_GET['active'] == $slug) ? 'active' : ''; //phpcs:ignore
				?>
				<li data-id="<?php echo esc_attr( $slug ); ?>" id="space-setting-<?php echo esc_attr( $slug ); ?>-li" class="space-setting-sub-tab space-sub-tab <?php echo esc_attr( $active_class ); ?>">
					<a class="dashicons-before" href="#space-setting-<?php echo esc_attr( $slug ); ?>-content"><?php echo esc_html( $name ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<form action="" method="post" class="space-settings" id="space-settings" data-nonce="<?php echo esc_attr( wp_create_nonce( 'save_space' ) ); ?>">
		<div class="item-body-inner">
			<div class="space-loader" style="display: none"></div>
			<div id="tabs-content">
				<?php foreach ( get_settings_tabs() as $slug => $name ) : ?>
					<?php spaces_get_template_part( $slug, '', 'settings' ); ?>
				<?php endforeach; ?>
			</div>
			<div class="space-settings-action-wrapper">
				<aside class="bp-feedback save-feedback bp-messages error" style="display: none">
					<span class="bp-icon" aria-hidden="true"></span>
					<p></p>
				</aside>
				<button class="space-settings-submit button primary" id="space-settings-submit" type="submit">
					<span>
						<?php
						printf(
						// translators: Placeholder %s is the singular label of the space post type.
							esc_html__( 'Save %s Settings', 'wpe-wps' ),
							esc_html( get_singular_label() )
						);
						?>
					</span>
					<div class="space-loader" style="display: none;"></div>
				</button>
			</div>
			<input type="hidden" name="space_id" value="<?php echo esc_attr( get_the_ID() ); ?>" />
			<input type="hidden" name="action" value="save_space" />
			<input type="hidden" name="page_url" id="space_setting_page_url" value="<?php echo esc_url( get_permalink() ) . 'settings/'; ?>" />
			<?php //wp_nonce_field( 'save_space', 'save_space' ); ?>

		</div>
	</form>
</div>
