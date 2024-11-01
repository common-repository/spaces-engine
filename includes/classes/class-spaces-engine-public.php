<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The core plugin public class.
 */
class Spaces_Engine_Public {

	/**
	 * Query vars to add to wp.
	 *
	 * @var array
	 */
	public $query_vars = array();

	/**
	 * Define the public-facing functionality of the plugin.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * We use an init function to decouple our hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', array( $this, 'rewrites' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'bp_enqueue_assets_in_bp_pages_only', array( $this, 'load_assets' ) );

		add_action( 'bp_attachments_avatar_delete_template', array( $this, 'avatar_delete_template' ) );
		add_action( 'bp_attachments_cover_image_delete_template', array( $this, 'cover_image_delete_template' ) );

		add_filter( 'template_include', array( $this, 'filter_template' ) );
		add_filter( 'body_class', array( $this, 'body_classes' ) );

		add_filter( 'bp_avatar_is_front_edit', array( $this, 'enable_avatar_upload_ui' ) );
		add_filter( 'bp_attachments_cover_image_is_edit', array( $this, 'enable_cover_image_upload_ui' ) );
		add_filter( 'bp_attachment_avatar_params', array( $this, 'avatar_params' ), 99, 1 );
		add_filter( 'bp_attachment_cover_image_params', array( $this, 'cover_image_params' ), 99, 1 );
		add_filter( 'bp_core_avatar_ajax_upload_params', array( $this, 'ajax_upload_params' ), 99, 1 );
		add_filter( 'bp_attachments_current_user_can', array( $this, 'allow_uploads_on_create_screen' ), 99, 3 );
		add_filter( 'bp_core_avatar_dir', array( $this, 'filter_directory_for_fetch_avatar' ), 99, 2 );
		add_filter( 'bp_attachments_cover_image_object_dir', array( $this, 'cover_image_object_data' ), 99, 2 );
		add_filter( 'bp_before_cover_image_upload_dir_parse_args', array( $this, 'cover_image_upload_data' ) );
		add_filter( 'bp_before_spaces_cover_image_settings_parse_args', array( $this, 'cover_image_settings' ) );
		add_filter( 'bp_before_attachments_enqueue_scripts_parse_args', array( $this, 'script_data' ) );
		add_filter( 'bp_attachments_cover_image_ui_warnings', array( $this, 'cover_image_warnings' ) );
		add_filter( 'bp_core_fetch_avatar_no_grav', array( $this, 'stop_grav' ) );
		add_filter( 'bp_nouveau_feedback_messages',  array( $this, 'feedback_messages' ) );

		if ( ! is_admin() ) {
			add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
		}
	}

	/**
	 * Adds additional body classes based on certain conditions.
	 *
	 * @param array $classes The existing body class array to add additional classes to.
	 *
	 * @return array The modified body class array.
	 */
	public function body_classes( $classes ) {
		// If we don't add this class, BP changes the user profile avatar to the new Space profile image.
		if ( is_avatar_edit() ) {
			$classes[] = 'group-avatar';
		}

		return $classes;
	}

	/**
	 * Generates the markup to allow Space avatars to be deleted.
	 *
	 * @return void
	 */
	public function avatar_delete_template() {
		$button_value = sprintf(
		// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( 'Delete %s Photo', 'wpe-wps' ),
			esc_html( get_singular_label() )
		);
		?>
		<p><?php
			printf(
			// translators: Placeholder %s is the singular label of the space post type.
				esc_html__( 'If you\'d like to delete your current %s photo, use the delete %s photo button.', 'wpe-wps' ),
				esc_html( strtolower( get_singular_label() ) ),
				esc_html( strtolower( get_singular_label() ) )
			);
			?></p>
		<input type="button" class="button edit" id="bp-delete-avatar" value="<?php echo esc_attr( $button_value ); ?>">
		<?php
	}

	/**
	 * Generates the markup to allow Space cover images to be deleted.
	 *
	 * @return void
	 */
	public function cover_image_delete_template() {
		$button_value = sprintf(
			// translators: Placeholder %s is the singular label of the space post type.
			__( 'Delete %s Cover Image', 'wpe-wps' ),
			get_singular_label()
		);
		?>
		<p><?php
			printf(
			// translators: Placeholder %s is the singular label of the space post type.
				esc_html__( 'If you\'d like to delete your current %s cover image, use the delete %s cover image button.', 'wpe-wps' ),
				esc_html( strtolower( get_singular_label() ) ),
				esc_html( strtolower( get_singular_label() ) )
			);
			?></p>
		<input type="button" class="button edit" id="bp-delete-cover-image" value="<?php echo esc_attr( $button_value ); ?>">
		<?php
	}

	/**
	 * Enqueues the stylesheets needed for Spaces Engine.
	 *
	 * This function adds the main CSS stylesheet for the Spaces Engine plugin
	 * and dynamically loads inline styles for the color palette and border radius.
	 *
	 * @return void
	 */
	public function enqueue_styles(  ) {
		wp_enqueue_style( 'spaces-engine-main', SPACES_ENGINE_PLUGIN_URL . 'assets/css/main.css', array(), SPACES_ENGINE_PLUGIN_VERSION );

		if ( 'BuddyBoss Theme' == wp_get_theme()->name || 'BuddyBoss Theme' == wp_get_theme()->parent_theme ) {
			wp_enqueue_style( 'spaces-engine-buddyboss', SPACES_ENGINE_PLUGIN_URL . 'assets/css/buddyboss.css', array(), SPACES_ENGINE_PLUGIN_VERSION );
		}

		// Loads dynamic inline styles.
		$style_css = $this->load_styles();
		wp_add_inline_style( 'spaces-engine-main', $style_css );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'spaces-engine-main', SPACES_ENGINE_PLUGIN_URL . 'assets/js/main.js', array( 'jquery' ), SPACES_ENGINE_PLUGIN_VERSION, true );

		if ( function_exists( 'buddypress' ) && ! is_bb() && get_post_type() === 'wpe_wpspace' ) {
			bp_enqueue_community_scripts();
		}

		if ( function_exists( 'buddypress' ) && is_bb() && is_single() && get_post_type() === 'wpe_wpspace' && isset( $wp_query->query['medias'] ) ) {
			wp_enqueue_script( 'bp-media-dropzone' );
			wp_enqueue_script( 'bp-nouveau-codemirror' );
			wp_enqueue_script( 'bp-nouveau-codemirror-css' );
			wp_enqueue_script( 'bp-nouveau-media' );
			wp_enqueue_script( 'bp-exif' );
			wp_enqueue_script( 'bp-nouveau-activity-post-form' );
		}

		if ( 'profile-image' === get_query_var('create-space-step' ) ) {
			bp_attachments_enqueue_scripts('BP_Attachment_Avatar');
		} elseif ( 'cover-image' === get_query_var('create-space-step' ) ) {
			bp_attachments_enqueue_scripts( 'BP_Attachment_Cover_Image' );
		}

		wp_localize_script(
			'spaces-engine-main',
			'spaces_engine_main',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'creation_steps' => get_creation_steps(),
				'next' => esc_html__( 'Next step', 'wpe-wps' ),
				'previous' => esc_html__( 'Previous step', 'wpe-wps' ),
				'create' => sprintf(
					// translators: Placeholder %s is the singular label of the space post type.
					esc_html__( 'Create %s', 'wpe-wps' ),
					esc_html( strtolower( get_singular_label() ) )
				),
				'visit' => sprintf(
					// translators: Placeholder %s is the singular label of the space post type.
					esc_html__( 'Visit %s', 'wpe-wps' ),
					esc_html( strtolower( get_singular_label() ) )
				),
				'return' => sprintf(
				// translators: Placeholder %s is the singular label of the space post type.
					esc_html__( 'Return to %s', 'wpe-wps' ),
					esc_html( get_singular_label() )
				),
				'create_space_link' => esc_url( get_create_space_link() )
			)
		);
	}

	/**
	 * Load assets for the given page. BP defaults to disabling assets on non-core pages.
	 *
	 * @see https://buddypress.trac.wordpress.org/ticket/8679
	 *
	 * @param mixed $retval The original return value.
	 *
	 * @return mixed The modified return value.
	 */
	public function load_assets( $retval ) {
		if ( 'wpe_wpspace' === get_post_type() || is_archive() ) {
			return false;
		}

		return $retval;
	}

	/**
	 * Add query vars.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		/** Add query vars for our Space Creation Pages */
		$vars[] = 'create-' . strtolower( get_singular_label() ) . '-page';
		$vars[] = 'create-space-step';

		/** Add query vars for each menu item and creation step */
		$vars[] = 'active-space-tab';

		return $vars;
	}

	/**
	 * Custom rewrite rules.
	 */
	public function rewrites() {
		$create_space_string = get_create_space_string();
		$slug                = get_slug();

		$counter = 0;
		foreach ( get_creation_steps() as $step => $value ) {
			if ($counter++ == 0) continue;

			add_rewrite_rule( "^{$slug}/{$step}/([^/]*)", 'index.php?post_type=wpe_wpspace&name=$matches[1]&' . $create_space_string . '=true&create-space-step=' . $step, 'top' );
		}

		add_rewrite_rule( "^{$slug}/{$create_space_string}", 'index.php?post_type=wpe_wpspace&' . $create_space_string . '=true', 'top' );

		$screens = get_screens();
		foreach ( $screens as $screen => $value ) {
			add_rewrite_rule( "^{$slug}/([^/]*)/{$screen}", 'index.php?post_type=wpe_wpspace&name=$matches[1]&active-space-tab=' . $screen, 'top' );
		}
	}

	/**
	 * Our template includes function.
	 *
	 * @param $template
	 *
	 * @return mixed|string
	 */
	public function filter_template( $template ) {
		if ( 'wpe_wpspace' === get_query_var( 'post_type' ) ) {
			$create_space_string = get_create_space_string();
			if ( get_query_var( $create_space_string ) ) {
				// Regardless of other privacy settings, you must be logged in to create a Space
				if ( ! is_user_logged_in() ) {
					wp_safe_redirect( home_url( '/' ) );
					exit;
				}

				// checks if the file exists in the theme first, otherwise serve the file from the plugin
				$theme_file = locate_template( 'spacesengine/create.php' );

				if ( $theme_file ) {
					$template = $theme_file;
				} else {
					$template = SPACES_ENGINE_PLUGIN_DIR . '/templates/create.php';
				}

				return $template;
			}

			if ( is_single() ) {
				buddypress()->current_component = 'activity';

				/* We need to force our single template to be part of the activity component, to receive the needed scripts */
				// buddypress()->current_component = 'activity';
				// add_filter( 'bp_current_component', function () {return 'activity';} );
				$theme_file = locate_template( 'spacesengine/single.php' );

				if ( $theme_file ) {
					$template = $theme_file;
				} else {
					$template = SPACES_ENGINE_PLUGIN_DIR . '/templates/single.php';
				}
			} elseif ( is_archive() ) {
				$theme_file = locate_template( 'spacesengine/archive.php' );

				if ( $theme_file ) {
					$template = $theme_file;
				} else {
					$template = SPACES_ENGINE_PLUGIN_DIR . '/templates/archive.php';
				}
			}
		}

		return $template;
	}

	/**
	 * Loads the styles for the plugin.
	 *
	 * @return string The CSS root variables for the styles.
	 */
	public function load_styles() {
		$bb = get_option( 'buddyboss_theme_options' );

		$space_styles = array(
			'--se-primary-color' => '#385DFF',
			'--se-content-background-color' => '#FFFFFF',
			'--se-header-background-color' => '#FFFFFF',
			'--se-header-text-color' => '#FFFFFF',
			'--se-headings-color' => '#1E2132',
			'--se-border-color' => 'D6D9DD',
			'--se-primary-button-background-color' => '#385DFF',
			'--se-primary-button-background-hover' => '#1E42DD',
			'--se-primary-button-text-color' => '#FFFFFF',
			'--se-primary-button-text-hover' => '#FFFFFF',
			'--se-secondary-button-background-color' => '#F2F4F5',
			'--se-secondary-button-background-hover' => '#385DFF',
			'--se-secondary-button-text-color' => '#1E2132',
			'--se-secondary-button-text-hover' => '#FFFFFF',
			'--se-border-radius' => '6'
		);

		$space_styles = apply_filters( 'spaces_engine_styles', $space_styles );
		$style_string = '';

		// If the BuddyBoss theme, keep colors consistent.
		if ( is_bb_theme() ) {
			$styles = array(
				'--se-primary-color' => isset( $bb['accent_color'] ) ? $bb['accent_color'] : $space_styles['--se-primary-color'],
				'--se-content-background-color' => isset( $bb['body_blocks'] ) ? $bb['body_blocks'] : $space_styles['--se-content-background-color'],
				'--se-header-background-color' => isset( $bb['header_background'] ) ? $bb['header_background'] : $space_styles['--se-header-background-color'],
				'--se-header-text-color' => isset( $bb['body_text_color'] ) ? $bb['body_text_color'] : $space_styles['--se-header-text-color'],
				'--se-headings-color' => isset( $bb['heading_text_color'] ) ? $bb['heading_text_color'] : $space_styles['--se-headings-color'],
				'--se-border-color' => isset( $bb['body_blocks_border'] ) ? $bb['body_blocks_border'] : $space_styles['--se-border-color'],
				'--se-primary-button-background-color' => isset( $bb['primary_button_background']['regular'] ) ? $bb['primary_button_background']['regular'] : $space_styles['--se-primary-button-background-color'],
				'--se-primary-button-background-hover' => isset( $bb['primary_button_background']['hover'] ) ? $bb['primary_button_background']['hover'] : $space_styles['--se-primary-button-background-hover'],
				'--se-primary-button-text-color' => isset( $bb['primary_button_text_color']['regular'] ) ? $bb['primary_button_text_color']['regular'] : $space_styles['--se-primary-button-text-color'],
				'--se-primary-button-text-hover' => isset( $bb['primary_button_text_color']['hover'] ) ? $bb['primary_button_text_color']['hover'] : $space_styles['--se-primary-button-text-hover'],
				'--se-secondary-button-background-color' => isset( $bb['secondary_button_background']['regular'] ) ? $bb['secondary_button_background']['regular'] : $space_styles['--se-secondary-button-background-color'],
				'--se-secondary-button-background-hover' => isset( $bb['secondary_button_background']['hover'] ) ? $bb['secondary_button_background']['hover'] : $space_styles['--se-secondary-button-background-hover'],
				'--se-secondary-button-text-color' => isset( $bb['secondary_button_text_color']['regular'] ) ? $bb['secondary_button_text_color']['regular'] : $space_styles['--se-secondary-button-text-color'],
				'--se-secondary-button-text-hover' => isset( $bb['secondary_button_text_color']['hover'] ) ? $bb['secondary_button_text_color']['hover'] : $space_styles['--se-secondary-button-text-hover'],
				'--se-border-radius' => isset( $bb['button_default_radius'] ) ? $bb['button_default_radius'] . 'px' : $space_styles['--se-border-radius'] . 'px',
			);
		} else {
			$styles = $space_styles;
		}

		foreach ( $styles as $key => $style ) {
			$style_string .= $key . ':' . $style . ';';
		}

		return ':root{' . $style_string . '}';
	}

	/**
	 * Filters the BuddyPress Nouveau feedback messages.
	 *
	 * @param array $value The list of feedback messages.
	 */
	public function feedback_messages( $messages ) {
		$messages['space-delete-warning'] = array(
			'type'    => 'info',
			'message' => __( 'WARNING: Deleting this item will completely remove ALL content associated with it. There is no way back. Please be careful with this option.', 'wpe-wps' ),
		);

		return $messages;
	}

	/**
	 * Enables the avatar upload user interface based on the current context.
	 *
	 * @param boolean $retval The current value of the return variable.
	 *
	 * @return boolean The updated value of the return variable that determines if the avatar upload UI should be enabled.
	 */
	public function enable_avatar_upload_ui( $retval ) {
		if ( is_avatar_edit() ) {
			$retval = true;
		}


		return $retval;
	}

	public function stop_grav( $retval ) {
		if ( is_avatar_edit() ) {
			$retval = true;
		}

		return $retval;
	}

	/**
	 * Enables cover image upload user interface.
	 *
	 * @param bool $retval The current return value indicating if cover image upload UI is enabled.
	 *
	 * @return bool The updated return value indicating if cover image upload UI is enabled.
	 */
	public function enable_cover_image_upload_ui( $retval ) {
		if ( is_cover_image_edit() ) {
			$retval = true;
		}

		return $retval;
	}

	/**
	 * Set the avatar parameters for a specific post type.
	 *
	 * @param array $data The existing avatar parameters. Default empty array.
	 *
	 * @return array The modified avatar parameters.
	 */
	public function avatar_params( $data ) {
		if ( 'wpe_wpspace' === get_post_type() ) {
			$post_id = get_the_ID();

			if ( ! $post_id ) {
				$post_id = get_id_by_var();
			}

			$data = array(
				'object'     => 'space',
				'item_id'    => $post_id,
				'item_type'  => 'full',
				'component'  => 'spaces',
				'has_avatar' => has_avatar( $post_id ),
				'nonces'  => array(
					'set'    => wp_create_nonce( 'bp_avatar_cropstore' ),
					'remove' => wp_create_nonce( 'bp_delete_avatar_link' ),
				),
			);
		}

		return $data;
	}

	/**
	 * Generates cover image parameters for a given data.
	 *
	 * @param array $data The data to generate the cover image parameters.
	 *
	 * @return array The generated cover image parameters.
	 */
	function cover_image_params( $data ) {
		if ( 'wpe_wpspace' === get_post_type() ) {
			$post_id = get_the_ID();

			if ( ! $post_id ) {
				$post_id = get_id_by_var();
			}

			$data = array(
				'object'     => 'space',
				'item_id'    => $post_id,
				'component'  => 'spaces',
				'has_cover_image' => has_cover_image( $post_id ),
				'nonces'  => array(
					'remove' => wp_create_nonce( 'bp_delete_cover_image' ),
				),
			);
		}

		return $data;

	}

	/**
	 * Adds a filter function to bp_params. BuddyPress uses this filter, which is found in spaces-engine-functions.php
	 * to determine where to save avatars. This must therefore ALWAYS BE A STRING.
	 *
	 * @param array $bp_params The parameters used in AJAX file uploads.
	 *                        - 'object' (string): The object type.
	 *
	 * @return array The modified parameters used in AJAX file uploads.
	 *               - 'object' (string): The modified object type.
	 *               - 'upload_dir_filter' (string): The modified upload directory filter.
	 */
	public function ajax_upload_params( $bp_params ) {
		if ( 'space' === $bp_params['object'] ) {
			$bp_params['upload_dir_filter'] = __NAMESPACE__ . '\avatar_upload_dir';
		}

		return $bp_params;
	}

	/**
	 * Filters the directory for fetching avatar.
	 *
	 * @param string $directory The current directory.
	 * @param string $object The object to fetch avatar for.
	 *
	 * @return string The filtered directory.
	 */
	public function filter_directory_for_fetch_avatar( $directory, $object ) {
		if ( 'space' === $object ) {
			return 'space-avatars';
		}

		return $directory;
	}

	/**
	 * Filter the object data of the cover image.
	 *
	 * @param array $object_data The object data of the cover image.
	 * @param string $object The type of object being filtered.
	 *
	 * @return array The modified object data of the cover image.
	 */
	public function cover_image_object_data( $object_data, $object ) {
		if ( 'space' === $object ) {
			$object_data = array( 'dir' => 'spaces', 'component' => 'spaces' );
		}

		return $object_data;
	}

	/**
	 * Generates script data for a given data.
	 *
	 * @param array $script_data The data to generate the script data.
	 *
	 * @return array The generated script data.
	 */
	public function script_data( $script_data) {
		if ( 'wpe_wpspace' === get_post_type() ) {
			$script_data['feedback_messages'] = array(
				1 => __( 'Action processed...', 'wpe-wps' ),
				2 => __( 'Action processed...', 'wpe-wps' ),
				3 => __( 'Action processed...', 'wpe-wps' ),
				4 => __( 'Action processed...', 'wpe-wps' ),
			);
		}

		return $script_data;
	}

	/**
	 * Generates cover image upload data for a given argument.
	 *
	 * @param array $args The argument to generate the cover image upload data.
	 *
	 * @return array The generated cover image upload data.
	 */
	public function cover_image_upload_data( $args ) {
		if ( 'space' === $_POST['bp_params']['object'] ) {
			return array(
				'object_id'        => (int) $_POST['bp_params']['item_id'],
				'object_directory' => 'spaces',
			);
		}

		return $args;
	}

	/**
	 * Generates cover image settings for a given settings array.
	 *
	 * @param array $settings The settings array to generate the cover image settings.
	 *
	 * @return array The generated cover image settings.
	 */
	public function cover_image_settings( $settings ) {
		$settings['components'][] = 'spaces';
		$settings['width'] = 1140;
		$settings['height'] = 300;

		return $settings;
	}

	/**
	 * Generates cover image warnings for a given array of warnings.
	 *
	 * @param array $warnings The array of warnings to generate cover image warnings.
	 *
	 * @return array The generated cover image warnings.
	 */
	public function cover_image_warnings( $warnings ) {
		if ( is_cover_image_edit() ) {
			$warnings = array(
				'dimensions' => sprintf(
				/* translators: 1: the advised width size in pixels. 2: the advised height size in pixels. */
					__( 'For better results, make sure to upload an image that is larger than %1$spx wide, and %2$spx tall.', 'wpe-wps' ),
					1140,
					300
				),
			);
		}

		return $warnings;
	}



	/**
	 * Filter if uploads are allowed on the create screen.
	 *
	 * @param bool $can Whether uploads are allowed. Default false.
	 * @param string $capability The capability being checked.
	 * @param array $args Additional arguments passed to the function.
	 *
	 * @return bool Whether uploads are allowed on the create screen.
	 */
	public function allow_uploads_on_create_screen( $can, $capability, $args ) {
		if ( 'edit_avatar' === $capability || 'edit_cover_image' === $capability ) {
			if ( is_cover_image_edit() || is_avatar_edit() ) {
				if ( is_user_logged_in() ) {
					$can = true;
				}
			} elseif ( is_space_admin( get_current_user_id(), $args['item_id'] ) ) {
				$can = true;
			}
		}

		// $can = true;

		return $can;
	}
}
