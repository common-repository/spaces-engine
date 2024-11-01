<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Creates a new space.
 *
 * This method creates a new space by inserting a post with the given title and post type.
 * It verifies the nonce for security before creating the space.
 * If the nonce is not valid, it sends a JSON error message.
 * After creating the space, it sends a JSON success response with the URL of the space.
 * Finally, it terminates the script execution.
 *
 * @return void
 */
function create_space() {
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'create_space' ) ) {
		$error = new \WP_Error(
			'Failed security check while creating the Space',
			esc_html__( 'We ran into a problem...', 'wpe-wps' )
		);

		wp_send_json_error( $error );
	}

	$title       = $_POST['title'];
	$description = $_POST['description'];

	if ( ! $title || ! $description ) {
		$error = new \WP_Error(
			'Missing title or description',
			esc_html__( 'Please fill in all of the required fields', 'wpe-wps' )
		);

		wp_send_json_error( $error );
	}

	$categories = array();
	if ( ! empty( $_POST['category'] ) ) {
		if ( is_array( $_POST['category'] ) ) {
			foreach ( $_POST['category'] as $category ) {
				if ( is_numeric( $category ) ) {
					$categories[] = intval( $category );
				}
			}
		} else {
			$categories = intval( $_POST['category'] );
		}
	}

	$args = array(
		'post_title'   => sanitize_text_field( $title ),
		'post_type'    => 'wpe_wpspace',
		'post_status'  => 'publish',
		'post_content' => wp_kses_post( wpautop( $description ) ),
	);

	$id = wp_insert_post( $args );

	if ( $description ) {
		// Legacy. The OG Spaces Engine put this into meta.
		add_post_meta( $id, 'wpe_wps_short_description', sanitize_text_field( $description ) );
	}

	wp_set_object_terms( $id, $categories, 'wp_space_category' );

	$response = array(
		'url'     => esc_url( get_permalink( $id ) ),
		'message' => sprintf(
		// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( '%s created successfully', 'wpe-wps' ),
			esc_html( get_singular_label() )
		),
		'slug'    => get_post_field( 'post_name', get_post( $id ) ),
	);

	wp_send_json_success( $response );

	wp_die();
}
add_action( 'wp_ajax_create_space', __NAMESPACE__ . '\create_space' );

function save_space() {
	parse_str( $_POST['form_data'], $data );

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'save_space' ) ) {
		$error = new \WP_Error(
			'Failed security check while creating the Space',
			esc_html__( 'We ran into a problem...', 'wpe-wps' )
		);

		wp_send_json_error( $error );
	}

	$title       = $data['title'];
	$description = sanitize_textarea_field( $data['description'] );
	$space_id    = $data['space_id'];
	$user_id     = get_current_user_id();
	$author_id   = get_post_field( 'post_author', $space_id );
	$space_info  = filter_var_array( $data['space_info'], FILTER_SANITIZE_FULL_SPECIAL_CHARS );

	if ( $user_id !== (int) $author_id && ! current_user_can( 'manage_options' ) ) {
		$error = new \WP_Error(
			'Missing capabilities',
			esc_html__( 'You don\'t have permission to save this data.', 'wpe-wps' )
		);

		wp_send_json_error( $error );
	}

	if ( ! $title || ! $description ) {
		$error = new \WP_Error(
			'Missing title or description',
			esc_html__( 'Please fill in all of the required fields', 'wpe-wps' )
		);

		wp_send_json_error( $error );
	}

	$categories = array();
	if ( ! empty( $data['category'] ) ) {
		if ( is_array( $data['category'] ) ) {
			foreach ( $data['category'] as $category ) {
				if ( is_numeric( $category ) ) {
					$categories[] = intval( $category );
				}
			}
		} else {
			$categories = intval( $data['category'] );
		}
	}

	$args = array(
		'ID'           => (int) $space_id,
		'post_title'   => sanitize_text_field( $title ),
		'post_content' => wpautop( $description ),
	);

	wp_update_post( $args );

	if ( ! empty( $data['category'] ) ) {
		wp_set_post_terms( $space_id, $categories, 'wp_space_category', false );
	} else {
		wp_set_post_terms( $space_id, array(), 'wp_space_category', false );
	}

	if ( ! empty( $space_info ) ) {
		foreach ( $space_info as $space_key => $space_value ) {
			update_post_meta( $space_id, 'space_' . $space_key, $space_value );
		}
	}

	$response = array(
		'url'     => esc_url( get_permalink( $space_id ) ),
		'message' => sprintf(
			// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( '%s updated successfully', 'wpe-wps' ),
			esc_html( get_singular_label() )
		),
		'slug'    => get_post_field( 'post_name', get_post( $space_id ) ),
	);

	wp_send_json_success( $response );

	wp_die();
}
add_action( 'wp_ajax_save_space', __NAMESPACE__ . '\save_space' );

function delete_space() {
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'save_space' ) ) {
		$error = new \WP_Error(
			'Failed security check while deleting the Space',
			esc_html__( 'We ran into a problem...', 'wpe-wps' )
		);

		wp_send_json_error( $error );
	}

	$space_id = sanitize_text_field( $_POST['space_id'] );

	if ( ! is_space_admin() ) {
		$error = new \WP_Error(
			'Failed authorization check while deleting the Space',
			esc_html__( 'You are not authorized to do that...', 'wpe-wps' )
		);

		wp_send_json_error( $error );
	}

	if ( bp_is_active( 'activity' ) ) {
		bp_activity_delete(
			array(
				'item_id'   => $space_id,
				'component' => 'activity',
			)
		);
	}

	global $wp_filesystem;

	require_once ABSPATH . '/wp-admin/includes/file.php';
	WP_Filesystem();

	// Delete group avatars.
	$upload_path = bp_core_avatar_upload_path();
	$wp_filesystem->delete( trailingslashit( $upload_path . '/space-avatars/' . $space_id ), true );

	// Delete group cover images.
	$bp_attachments_uploads_dir = bp_attachments_uploads_dir_get();
	$type_dir                   = trailingslashit( $bp_attachments_uploads_dir['basedir'] );
	$wp_filesystem->delete( trailingslashit( $type_dir . 'spaces/' . $space_id ), true );

	// Delete group default PNG avatars.
	$upload_path = bp_core_avatar_upload_path();
	$wp_filesystem->delete( trailingslashit( $upload_path . '/space-avatars/default/' . $space_id ), true );

	do_action( 'spaces_engine_before_delete_space', $_POST['space_id'] );

	wp_delete_post( $space_id, true ); /* Delete Space*/

	do_action( 'spaces_engine_after_delete_space', $_POST['space_id'] );

	$response = array(
		'url'     => get_post_type_archive_link( 'wpe_wpspace' ),
		'message' => sprintf(
		// translators: Placeholder %s is the singular label of the space post type.
			esc_html__( '%s deleted successfully', 'wpe-wps' ),
			esc_html( get_singular_label() )
		),
		'slug'    => get_post_field( 'post_name', get_post( $space_id ) ),
	);

	wp_send_json_success( $response );

	wp_die();
}
add_action( 'wp_ajax_delete_space', __NAMESPACE__ . '\delete_space' );

/**
 * Filters spaces based on the given parameters.
 *
 * This method filters spaces based on the parameters received via POST request.
 * It verifies the nonce for security before filtering the spaces.
 * If the nonce is not valid, it sends a JSON error message.
 * It applies filters to the query based on the scope, order, search terms, and category.
 * The filtered spaces are retrieved using WP_Query and displayed in a list format.
 * If no spaces match the criteria, a "Sorry, no posts matched your criteria" message is displayed.
 * The filtered content is captured using output buffering and sent as a JSON success response.
 * The response includes pagination information and the filtered content.
 * Finally, it terminates the script execution.
 *
 * @return void
 */
function filter_spaces() {
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['params']['nonce'] ) ), 'spaces-index' ) ) {
		$error = new \WP_Error(
			'Failed security check while processing Spaces index',
			esc_html__( 'We ran into a problem...', 'wpe-wps' )
		);

		wp_send_json_error( $error );
	}

	$page       = intval( $_POST['params']['page'] );
	$qty        = 12;
	$pagination = 'standard';

	$args = array(
		'post_type'      => 'wpe_wpspace',
		'posts_per_page' => $qty,
		'paged'          => $page,
	);

	if ( 'personal' === $_POST['params']['scope'] ) {
		$args['author'] = get_current_user_id();
	}

	if ( 'alphabetical' === $_POST['params']['order'] ) {
		$args['orderby'] = 'title';
		$args['order']   = 'ASC';
	}

	if ( $_POST['params']['search_terms'] ) {
		$args['s'] = sanitize_text_field( $_POST['params']['search_terms'] );
	}

	if ( $_POST['params']['category'] ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'wp_space_category',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $_POST['params']['category'] ),
			),
		);
	}

	$the_query = new \WP_Query( $args );

	ob_start();

	if ( $the_query->have_posts() ) : ?>
		<ul id="space-list" class="item-list space-list bp-list grid">

		<?php
		while ( $the_query->have_posts() ) :
			$the_query->the_post();

			include SPACES_ENGINE_PLUGIN_DIR . '/templates/card.php';
			?>

		<?php endwhile; ?>

		</ul>

		<?php paginate( $the_query->max_num_pages, $page ); ?>

	<?php else : ?>
		<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'wpe-wps' ); ?></p>
		<?php
	endif;

		$response = array(
			'pagination' => $pagination,
			'next'       => $page + 1,
		);

		$response['content'] = ob_get_clean();

		wp_send_json_success( $response );

		wp_die();
}
add_action( 'wp_ajax_filter_spaces', __NAMESPACE__ . '\filter_spaces' );
add_action( 'wp_ajax_nopriv_filter_spaces', __NAMESPACE__ . '\filter_spaces' );

/**
 * Paginates a list of items.
 *
 * This method generates a pagination HTML output for a list of items based on the maximum number of pages and the current page.
 * It uses the `paginate_links()` function to generate the paginated links.
 * The `$max_num_pages` parameter is the total number of pages available.
 * The `$paged` parameter is the current page number.
 * It also supports localization for the "Prev" and "Next" texts, based on the existence of the BuddyBoss plugin.
 * The generated pagination HTML is outputted directly to the page.
 *
 * @param int $max_num_pages The total number of pages available.
 * @param int $paged The current page number.
 *
 * @return void
 */
function paginate( $max_num_pages, $paged ) {
	$big          = 999999999;
	$search_for   = array( $big, '#038;' );
	$replace_with = array( '%#%', '' );
	$position     = 'bottom';
	$pag_count    = false;

	if ( isset( buddypress()->buddyboss ) ) {
		$prev = esc_html__( 'Prev', 'wpe-wps' );
		$next = esc_html__( 'Next', 'wpe-wps' );
	} else {
		$prev = '←';
		$next = '→';
	}

	$paginate = paginate_links(
		array(
			'base'      => str_replace( $search_for, $replace_with, esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?page=%#%',
			'type'      => 'array',
			'current'   => max( 1, $paged ),
			'total'     => $max_num_pages,
			'prev_next' => true,
			'prev_text' => $prev,
			'next_text' => $next,
		)
	);

	if ( $max_num_pages > 1 ) :
		?>
		<div class="<?php echo esc_attr( 'bp-pagination ' . sanitize_html_class( $position ) ); ?>">

			<?php if ( $pag_count ) : ?>
				<div class="<?php echo esc_attr( 'pag-count ' . sanitize_html_class( $position ) ); ?>">

					<p class="pag-data">
						<?php echo esc_html( $pag_count ); ?>
					</p>

				</div>
			<?php endif; ?>

			<div class="bp-pagination-links bottom pagination">
				<p class="pag-data">
					<?php foreach ( $paginate as $page ) : ?>
						<?php echo wp_kses_post( $page ); ?>
					<?php endforeach; ?>
				</p>
			</div>

		</div>

		<?php
	endif;
}

function space_save_cover_position() {
	if ( ! bp_is_post_request() ) {
		wp_send_json_error();
	}

	if ( ! isset( $_POST['position'] ) ) {
		wp_send_json_error();
	}

	if ( ! isset( $_POST['post_id'] ) ) {
		wp_send_json_error();
	}

	$position = floatval( $_POST['position'] );
	$updated  = false;

	$updated = update_post_meta( intval( $_POST['post_id'] ), 'bp_cover_position', $position );

	if ( empty( $updated ) ) {
		wp_send_json_error();
	}

	$result['content'] = $position;

	wp_send_json_success( $result );
}
add_action( 'wp_ajax_space_save_cover_position', __NAMESPACE__ . '\space_save_cover_position' );
