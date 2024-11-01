<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the plural label for Spaces.
 *
 * @return mixed|null
 */
function get_plural_label() {
	return apply_filters( 'spaces_engine_plural_label', 'Spaces' );
}

/**
 * Get the singular label for Spaces.
 *
 * @return mixed|null
 */
function get_singular_label() {
	return apply_filters( 'spaces_engine_singular_label', 'Space' );
}

/**
 * Get the Spaces slug.
 *
 * @return mixed|null
 */
function get_slug() {
	return apply_filters( 'spaces_engine_slug', 'spaces' );
}

/**
 * Determine if current page ID is a single Space Post type
 *
 * @return bool
 */
function is_space_by_id( $post_id ) {
	if ( 'wpe_wpspace' === get_post_type( $post_id ) ) {
		return true;
	} else {
		return false;

	}
}

/**
 * Checks if a user is a Space admin.
 *
 * @param int|null $user_id Optional. The user ID to check. Defaults to the current user ID.
 * @param int|null $space_id Optional. The space ID to check. Defaults to the current space ID.
 *
 * @return bool               Returns true if the user is a Space admin, false otherwise.
 */
function is_space_admin( $user_id = null, $space_id = null ) {

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	// Site admins are always Space admins.
	if ( current_user_can( 'manage_options' ) ) {
		return true;
	}

	if ( ! $space_id ) {
		$space_id = get_the_ID();
	}

	$author_id = get_post_field( 'post_author', $space_id );

	// Owners are always admins
	if ( (int) $author_id === (int) $user_id ) {
		return true;
	}
}

/**
 * Checks if BuddyBoss is active.
 *
 * @return bool Whether BuddyBoss is active or not.
 */
function is_bb() {
	if ( isset( buddypress()->buddyboss ) ) {
		return true;
	}

	return false;
}

/**
 * Checks if the current theme is BuddyBoss Theme or its child theme.
 *
 * @return bool Whether the current theme is BuddyBoss Theme or its child theme.
 */
function is_bb_theme() {
	if ( 'BuddyBoss Theme' === wp_get_theme()->name || 'BuddyBoss Theme' === wp_get_theme()->parent_theme ) {
		return true;
	}

	return false;
}

/**
 * Gets the various screens for rewrite purposes.
 *
 * @return mixed|void
 */
function get_screens() {
	$items = array(
		'home'     => esc_html__( 'Home', 'wpe-wps' ),
		'about'    => esc_html__( 'About', 'wpe-wps' ),
		'settings' => esc_html__( 'Settings', 'wpe-wps' ),
	);

	return apply_filters( 'wpe_wps_primary_nav', $items );
}

/**
 * Gets the creation steps array.
 *
 * Filterable to allow 3rd-party tools to add steps in. The order of the creation
 * panels shown will be in the order of the array.
 *
 * @return array An associative array of creation steps.
 */
function get_creation_steps() {
	$steps = array(
		'details'       => esc_html__( 'Details', 'wpe-wps' ),
		'profile-image' => esc_html__( 'Profile Image', 'wpe-wps' ),
		'cover-image'   => esc_html__( 'Cover Image', 'wpe-wps' ),
	);

	$more_steps = apply_filters( 'spaces_engine_creation_steps', array() );

	return array_merge( $steps, $more_steps );
}

/**
 * Checks if a given step is the first creation step.
 *
 * @param string $step The step to check.
 *
 * @return bool Whether the given step is the first creation step.
 */
function is_first_creation_step( $step ) {
	return array_key_first( get_creation_steps() ) === $step;
}

/**
 * Checks if the given step is the last creation step.
 *
 * @param mixed $step The step to check.
 *
 * @return bool Whether the given step is the last creation step.
 */
function is_last_creation_step( $step ) {
	return array_key_last( get_creation_steps() ) === $step;
}

/**
 * Gets the string for the Space creation page.
 *
 * @return string The formatted string for creating a Space page.
 */
function get_create_space_string() {
	return sprintf(
	// translators: Placeholder %s is the singular label of the space post type.
		esc_html__( 'create-%1$s-page', 'wpe-wps' ),
		esc_html( strtolower( get_singular_label() ) )
	);
}

/**
 * Gets the link for the Space creation page.
 *
 * @return string The link for creating a Space.
 */
function get_create_space_link() {
	return trailingslashit( get_post_type_archive_link( 'wpe_wpspace' ) . get_create_space_string() );
}

/**
 * Retrieves the creation step tabs.
 *
 * @return void
 */
function get_creation_step_tabs() {
	$counter = 1;
	foreach ( get_creation_steps() as $slug => $step ) { ?>

		<li data-id="<?php echo esc_attr( $slug ); ?>">
			<span><?php echo esc_html( $counter ); ?>. <?php echo esc_html( $step ); ?></span>
		</li>
		<?php
		$counter++;
	}
}

/**
 * Gets the creation step buttons HTML.
 *
 * @return string The HTML for the creation step buttons.
 */
function get_creation_step_buttons() {
	$current = get_query_var( 'create-space-step' );
	if ( ! $current ) {
		$current = 'details';
	}

	$keys  = array_keys( get_creation_steps() );
	$index = array_search( $current, $keys, true );

	if ( 0 === $index ) {
		$prev = false;
	} else {
		$prev = $keys[ $index - 1 ];
	}

	if ( count( get_creation_steps() ) <= $index + 1 ) {
		$next = false;
	} else {
		$next = $keys[ $index + 1 ];
	}

	$post_name = get_query_var( 'name' );

	ob_start();
	?>

	<?php if ( 0 === $index ) : ?>
		<div class="prev-next">
			<button type="submit" class="primary create">
				<?php
					printf(
					// translators: Placeholder %s is the singular label of the space post type.
						esc_html__( 'Create %s', 'wpe-wps' ),
						esc_html( strtolower( get_singular_label() ) )
					);
				?>
			</button>
			<a class="button primary next disabled" href="<?php echo esc_url( get_post_type_archive_link( 'wpe_wpspace' ) . $next . '/' . $post_name ); ?>">
				<?php esc_html_e( 'Next step', 'wpe-wps' ); ?>
			</a>
		</div>
	<?php else : ?>
		<div class="prev-next">
			<?php if ( $index > 1 ) : ?>
			<a class="button primary" href="<?php echo esc_url( get_post_type_archive_link( 'wpe_wpspace' ) . $prev . '/' . $post_name ); ?>">
				<?php esc_html_e( 'Previous step', 'wpe-wps' ); ?>
			</a>
		<?php endif; ?>
			<?php if ( $next ) : ?>
				<a class="button primary" href="<?php echo esc_url( get_post_type_archive_link( 'wpe_wpspace' ) . $next . '/' . $post_name ); ?>">
					<?php esc_html_e( 'Next step', 'wpe-wps' ); ?>
				</a>
			<?php else : ?>
			<a class="button primary" href="<?php echo esc_url( get_post_type_archive_link( 'wpe_wpspace' ) . $next . '/' . $post_name ); ?>">
				<?php
				printf(
				// translators: Placeholder %s is the singular label of the space post type.
					esc_html__( 'Visit %s', 'wpe-wps' ),
					esc_html( strtolower( get_singular_label() ) )
				);
				?>
			</a>
		<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php
	return ob_get_flush();
}

/**
 * Gets the default cover image for a Space.
 *
 * @param bool $echo Optional. Whether to echo the image or just return the image URL. Defaults to false.
 *
 * @return string|void The URL of the default cover image or nothing if $echo is set to true.
 */
function default_cover_image( $echo = false ) {
	$cover_image = SPACES_ENGINE_PLUGIN_URL . 'assets/images/cover.jpg';

	if ( ! $echo ) {
		?>

		<img src="<?php echo esc_url( $cover_image ); ?>" alt="Space Cover Image">

		<?php
	} else {
		return $cover_image;
	}
}

/**
 * Retrieves a template part from the theme or plugin.
 *
 * Retrieves a template part by looking for a file in the theme directory first,
 * and if not found, serves the file from the plugin directory.
 *
 * @param string $slug The slug of the template file.
 * @param string $name Optional. The name of the template file. Default empty.
 * @param array $args Optional. Arguments to pass to the template file. Default empty array.
 *
 * @return void
 */
function spaces_get_template_part( $slug, $name = '', $folder = false, $args = array() ) {
	if ( $folder ) {
		$folder = trailingslashit( $folder );
	}

	// checks if the file exists in the theme first, otherwise serve the file from the plugin
	if ( $name ) {
		$theme_file = locate_template( "spaces-engine/{$folder}{$slug}-{$name}.php" );

		if ( $theme_file ) {
			$template = $theme_file;
		} else {
			$template = SPACES_ENGINE_PLUGIN_DIR . "/templates/{$folder}{$slug}-{$name}.php";
		}
	} else {
		$theme_file = locate_template( "spaces-engine/{$folder}{$slug}.php" );

		if ( $theme_file ) {
			$template = $theme_file;
		} else {
			$template = SPACES_ENGINE_PLUGIN_DIR . "/templates/{$folder}{$slug}.php";
		}
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'spaces_get_template_part', $template, $slug, $name, $folder );

	if ( $template ) {
		load_template( $template, true, $args );
	}
}


/**
 * Gets the ID of a post by its variable name.
 *
 * Retrieves the post name from the query variable 'name',
 * then retrieves the post object associated with that name.
 * If the post object is found, the ID of the post is returned.
 * If the post object is not found, false is returned.
 *
 * @return int|false The ID of the post or false.
 */
function get_id_by_var() {
	$post_name = get_query_var( 'name' );
	$post      = get_page_by_path( $post_name, OBJECT, 'wpe_wpspace' );

	if ( $post ) {
		$id = $post->ID;
	} else {
		$id = false;
	}

	return $id;
}

/**
 * Checks to see if a Space has an avatar. Compares with default avatar.
 *
 * @param bool $post_id The ID of the Space to check. Default is false.
 *
 * @return bool Whether an avatar is found.
 */
function has_avatar( $post_id = false ) {

	if ( ! $post_id ) {
		$post_id = get_the_ID();

		if ( ! $post_id ) {
			$post_id = get_id_by_var();
		}
	}

	$avatar_args = array(
		'no_grav' => true,
		'html'    => false,
		'type'    => 'thumb',
		'item_id' => $post_id,
		'object'  => 'space',
	);

	// Get the avatar.
	$avatar = bp_core_fetch_avatar( $avatar_args );

	return ( bp_core_avatar_default( 'local', $avatar_args ) !== $avatar );
}

/**
 * Gets a new Space avatar image.
 *
 * @return string|null The avatar image URL on success, null on failure.
 */
function get_space_avatar( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();

		if ( ! $post_id ) {
			$post_id = get_id_by_var();
		}
	}

	$r = array(
		'type'       => 'full',
		'width'      => false,
		'height'     => false,
		'class'      => 'avatar',
		'id'         => 'avatar-crop-preview',
		'alt'        => __( 'Space photo', 'wpe-wps' ),
		'item_id'    => $post_id,
		'object'     => 'space',
		'avatar_dir' => 'space-avatars',
		'no_grav'    => true,
		'html'       => true,
	);

	// Get the avatar.
	$avatar = bp_core_fetch_avatar( $r );

	return $avatar;
}

/**
 * Gets the avatar upload directory for a space.
 *
 * @return array The avatar upload directory information.
 */
function avatar_upload_dir() {
	$post_id = (int) $_POST['bp_params']['item_id'];

	$directory = 'space-avatars';
	$path      = bp_core_avatar_upload_path() . '/' . $directory . '/' . $post_id;
	$newbdir   = $path;
	$newurl    = bp_core_avatar_url() . '/' . $directory . '/' . $post_id;
	$newburl   = $newurl;
	$newsubdir = '/' . $directory . '/' . $post_id;

	return array(
		'path'    => $path,
		'url'     => $newurl,
		'subdir'  => $newsubdir,
		'basedir' => $newbdir,
		'baseurl' => $newburl,
		'error'   => false,
	);
}

/**
 * Checks if the current page is the avatar edit page for a Space.
 *
 * @return bool True if the current page is the avatar edit page for a Space, false otherwise.
 */
function is_avatar_edit() {
	if ( 'profile-image' === get_query_var( 'create-space-step' ) || 'settings' === get_query_var( 'active-space-tab' ) ) {
		return true;
	}

	return false;
}

/**
 * Checks if the current page is the cover image edit page for a Space.
 *
 * @return bool Returns true if the current page is the cover image edit page for a Space, false otherwise.
 */
function is_cover_image_edit() {
	if ( 'cover-image' === get_query_var( 'create-space-step' ) || 'settings' === get_query_var( 'active-space-tab' ) ) {
		return true;
	}

	return false;
}

/**
 * Checks if a space has a cover image.
 *
 * @param int $space_id Optional. The ID of the space. Defaults to 0.
 *
 * @return string|false The URL of the cover image if it exists, false otherwise.
 */
function has_cover_image( $space_id = 0 ) {
	if ( empty( $space_id ) ) {
		$space_id = get_id_by_var();
	}

	$args = array(
		'object_dir' => 'spaces',
		'item_id'    => $space_id,
	);

	$cover_src = bp_attachments_get_attachment( 'url', $args );

	if ( $cover_src ) {
		return true;
	}

	return false;
}

/**
 * Get the cover image URL for a specific space.
 *
 * @param int $space_id The ID of the space. Default is 0 (current space).
 *
 * @return string The URL of the cover image, or the default cover image URL if no cover image found.
 */
function get_cover_image( $space_id = 0 ) {
	if ( empty( $space_id ) ) {
		$space_id = get_the_ID();
	}

	$args = array(
		'object_dir' => 'spaces',
		'item_id'    => $space_id,
	);

	$cover_src = bp_attachments_get_attachment( 'url', $args );

	if ( $cover_src ) {
		return $cover_src;
	}

	return default_cover_image( true );
}

function get_cover_image_edit_link( $space_id = 0 ) {
	if ( empty( $space_id ) ) {
		$space_id = get_the_ID();
	}

	$slug = get_post( $space_id )->post_name;

	return trailingslashit( get_post_type_archive_link( 'wpe_wpspace' ) . 'cover-image/' . $slug );
}
