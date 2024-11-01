<?php
/**
 * Plugin Name: Spaces Engine
 * Plugin URI:  https://spacesengine.com/
 * Description: Easily create business profiles for BuddyPress and BuddyBoss.
 * Author:      Bouncingsprout Studio
 * Author URI:  https://www.bouncingsprout.com/
 * Version:     1.0.0
 * Domain Path: /languages/
 * License:     GPLv2 or later
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

define( 'SPACES_ENGINE_PLUGIN_VERSION', '1.0.0' );
define( 'SPACES_ENGINE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SPACES_ENGINE_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'SPACES_ENGINE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 *  Checks if Spaces Engine Pro is activated.
 */
function spaces_engine_pro_active() {
	if ( function_exists( 'se_fs' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'spaces_engine_pro_active_notice' );
	}
}
add_action( 'admin_init', 'spaces_engine_pro_active' );

/**
 * Creates an error admin notice explaining why Spaces Engine was deactivated.
 */
function spaces_engine_pro_active_notice() {
	$a = ' Spaces Engine';
	echo '<div class="error"><p>';
	/* translators: %s: */
	echo sprintf(
	/* Translators: %1$s is our name. %2$s is BuddyPress */
		esc_html__( 'You already have a version of %1$s installed and active. To use the free version, please deactivate the pro version first.', 'wpe-wps' ),
		'<strong>' . esc_html( $a ) . '</strong>',
	);
	echo '</p></div>';
}

/**
 *  Checks if BuddyPress is activated.
 */
function spaces_engine_requires_buddypress() {
	if ( ! class_exists( 'Buddypress' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'spaces_engine_required_plugin_admin_notice' );
	}
}
add_action( 'admin_init', 'spaces_engine_requires_buddypress' );

/**
 * Creates an error admin notice explaining why Spaces Engine was deactivated.
 */
function spaces_engine_required_plugin_admin_notice() {
	$a = ' Spaces Engine';
	$b = 'BuddyPress';
	echo '<div class="error"><p>';
	/* translators: %s: */
	echo sprintf(
	/* Translators: %1$s is our name. %2$s is BuddyPress */
		esc_html__( '%1$s requires %2$s to be installed and active.', 'wpe-wps' ),
		'<strong>' . esc_html( $a ) . '</strong>',
		'<strong>' . esc_html( $b ) . '</strong>'
	);
	echo '</p></div>';
}

/**
 * Start the engines, Captain...
 */
function spaces_engine_run() {
	require_once SPACES_ENGINE_PLUGIN_PATH . 'includes/classes/class-spaces-engine.php';
	$plugin = new \SpacesEngine\Spaces_Engine();
}
add_action( 'bp_include', 'spaces_engine_run' );
