<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The core plugin class.
 */
class Spaces_Engine {
	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->boot_public();
		$this->boot_admin();
	}

	/**
	 * Load our dependencies.
	 *
	 * @return void
	 */
	public function load_dependencies() {
		require_once SPACES_ENGINE_PLUGIN_DIR . '/includes/spaces-engine-post-types.php';
		require_once SPACES_ENGINE_PLUGIN_DIR . '/includes/spaces-engine-functions.php';
		require_once SPACES_ENGINE_PLUGIN_DIR . '/includes/spaces-engine-single-functions.php';
		require_once SPACES_ENGINE_PLUGIN_DIR . '/includes/spaces-engine-activity-functions-filters.php';
		require_once SPACES_ENGINE_PLUGIN_DIR . '/includes/spaces-engine-handlers.php';
		require_once SPACES_ENGINE_PLUGIN_DIR . '/includes/classes/class-spaces-engine-public.php';
		require_once SPACES_ENGINE_PLUGIN_DIR . '/admin/classes/class-spaces-engine-admin.php';
	}

	/**
	 * Boots our public-facing functionality.
	 *
	 * @return void
	 */
	public function boot_public() {
		$spaces_engine_public = new Spaces_Engine_Public();
	}

	/**
	 * Boots our public-facing functionality.
	 *
	 * @return void
	 */
	public function boot_admin() {
		$spaces_engine_admin = new Spaces_Engine_Admin();
	}
}
