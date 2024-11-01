<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register our Space post type.
 *
 * @return void
 */
function register_space_post_type() {
	$slug     = get_slug();
	$singular = get_singular_label();
	$plural   = get_plural_label();

	/**
	 * Taxonomies
	 */

	// Register the 'Categories' taxonomy
	register_taxonomy(
		'wp_space_category',
		array( 'wpe_wpspace' ),
		array(
			'hierarchical'      => true,
			'labels'            => array(
				// translators: Placeholder %s is the singular label of the space post type.
				'name'              => sprintf( __( '%s Categories', 'wpe-wps' ), $singular ),
				// translators: Placeholder %s is the singular label of the space post type.
				'singular_name'     => sprintf( __( '%s Category', 'wpe-wps' ), $singular ),
				// translators: Placeholder %s is the singular label of the space post type.
				'search_items'      => sprintf( __( 'Search %s Categories', 'wpe-wps' ), $singular ),
				// translators: Placeholder %s is the singular label of the space post type.
				'all_items'         => sprintf( __( 'All %s Categories', 'wpe-wps' ), $singular ),
				// translators: Placeholder %s is the singular label of the space post type.
				'parent_item'       => sprintf( __( 'Parent %s Category', 'wpe-wps' ), $singular ),
				// translators: Placeholder %s is the singular label of the space post type.
				'parent_item_colon' => sprintf( __( 'Parent %s Category', 'wpe-wps' ), $singular ),
				// translators: Placeholder %s is the singular label of the space post type.
				'edit_item'         => sprintf( __( 'Edit %s Category', 'wpe-wps' ), $singular ),
				// translators: Placeholder %s is the singular label of the space post type.
				'update_item'       => sprintf( __( 'Update %s Category', 'wpe-wps' ), $singular ),
				// translators: Placeholder %s is the singular label of the space post type.
				'add_new_item'      => sprintf( __( 'Add New %s Category', 'wpe-wps' ), $singular ),
				// translators: Placeholder %s is the singular label of the space post type.
				'new_item_name'     => sprintf( __( 'New %s Category Name', 'wpe-wps' ), $singular ),
				// translators: Placeholder %s is the singular label of the space post type.
				'menu_name'         => sprintf( __( '%s Categories', 'wpe-wps' ), $singular ),
			),
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => 'spaces-categories',
				'with_front' => true,
			),
		)
	);

	/**
	 * Post types
	 */
	$rewrite = array(
		'slug'       => $slug,
		'with_front' => false,
	);

	register_post_type(
		'wpe_wpspace',
		apply_filters(
			'register_post_type_wp_space',
			array(
				'labels'                => array(
					'name'               => $plural,
					'singular_name'      => $singular,
					'menu_name'          => $plural,
					// translators: Placeholder %s is the plural label of the space post type.
					'all_items'          => sprintf( __( 'All %s', 'wpe-wps' ), $plural ),
					'add_new'            => __( 'Add New', 'wpe-wps' ),
					// translators: Placeholder %s is the singular label of the space post type.
					'add_new_item'       => sprintf( __( 'Add %s', 'wpe-wps' ), $singular ),
					'edit'               => __( 'Edit', 'wpe-wps' ),
					// translators: Placeholder %s is the singular label of the space post type.
					'edit_item'          => sprintf( __( 'Edit %s', 'wpe-wps' ), $singular ),
					// translators: Placeholder %s is the singular label of the space post type.
					'new_item'           => sprintf( __( 'New %s', 'wpe-wps' ), $singular ),
					// translators: Placeholder %s is the singular label of the space post type.
					'view'               => sprintf( __( 'View %s', 'wpe-wps' ), $singular ),
					// translators: Placeholder %s is the singular label of the space post type.
					'view_item'          => sprintf( __( 'View %s', 'wpe-wps' ), $singular ),
					// translators: Placeholder %s is the singular label of the space post type.
					'search_items'       => sprintf( __( 'Search %s', 'wpe-wps' ), $plural ),
					// translators: Placeholder %s is the singular label of the space post type.
					'not_found'          => sprintf( __( 'No %s found', 'wpe-wps' ), $plural ),
					// translators: Placeholder %s is the plural label of the space post type.
					'not_found_in_trash' => sprintf( __( 'No %s found in trash', 'wpe-wps' ), $plural ),
					// translators: Placeholder %s is the singular label of the space post type.
					'parent'             => sprintf( __( 'Parent %s', 'wpe-wps' ), $singular ),
				),
				// translators: Placeholder %s is the plural label of the space post type.
				'description'           => sprintf(
				/* translators: Placeholder %s is the plural label of the space post type */
					esc_html__(
						'This is where you can create and manage %1$s.',
						'wpe-wps'
					),
					$plural
				),
				'public'                => true,
				'show_ui'               => true,
				'capability_type'       => 'post',
				'map_meta_cap'          => true,
				'publicly_queryable'    => true,
				'exclude_from_search'   => false,
				'hierarchical'          => true,
				'rewrite'               => $rewrite,
				'query_var'             => true,
				'supports'              => array(
					'title',
					'editor',
					'custom-fields',
					'publicize',
					'thumbnail',
					'author',
				),
				'has_archive'           => true,
				'show_in_nav_menus'     => true,
				'delete_with_user'      => true,
				'show_in_rest'          => true,
				'rest_base'             => 'wpe_wpspace',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'template'              => array( array( 'core/freeform' ) ),
				'template_lock'         => 'all',
				'menu_position'         => 30,
				'menu_icon'             => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyOTYgMjk2IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8Zz4KPHBhdGggZD0iTTExNi45NzcgMTI2LjI1TDI1MS4xODIgMTAwLjE2OUMyNjAuNDY1IDk5LjEzNDIgMjY0LjA3NyA5My4zNjY2IDI2MC4xMjYgNzYuMjM1NUMyNTcuNDI0IDY0LjUxOCAyNTEuNTI4IDM3LjQwNCAyMjMuMTM3IDI2LjQxMTJDMTkyLjQyOCAxNC41MjE0IDEwNS43OTcgNDguMjMyMiA4NC43OTYxIDkyLjk4MjRDNjcuOTk1OCAxMjguNzgyIDk5LjI0OTYgMTMwLjA3NyAxMTYuOTc3IDEyNi4yNVoiIGZpbGw9IiNmMGYwZjEiLz4KPHBhdGggZD0iTTE4Ni4yOTkgMTc1Ljc3Nkw1Mi43ODUzIDIwNS4xMDlDNDMuNTMxMyAyMDYuMzY4IDQwLjA2NDMgMjEyLjIyMiA0NC40Mzk2IDIyOS4yNTJDNDcuNDMyMiAyNDAuOTAxIDUzLjk5OTcgMjY3Ljg2NCA4Mi42NTU0IDI3OC4xNjVDMTEzLjY1IDI4OS4zMDYgMTk5LjQxNSAyNTMuNTAxIDIxOS4yOTYgMjA4LjI1M0MyMzUuMjAxIDE3Mi4wNTUgMjAzLjkyNSAxNzEuNTE5IDE4Ni4yOTkgMTc1Ljc3NloiIGZpbGw9IiNmMGYwZjEiLz4KPHBhdGggZD0iTTE3NC4zNzEgMTE0LjUxOEwyMDEuODQ5IDI0NS44NzdDMjAyLjk2NCAyNTQuOTY3IDIwOC44NzIgMjU4LjQ3MSAyMjYuMzI3IDI1NC40OTRDMjM4LjI2NiAyNTEuNzc0IDI2NS44OTUgMjQ1LjgyOSAyNzYuOTI5IDIxNy45MzhDMjg4Ljg2NCAxODcuNzcxIDI1My45MDcgMTAzLjA4MiAyMDguMTA5IDgyLjc3NzVDMTcxLjQ3MSA2Ni41MzM5IDE3MC4zNTEgOTcuMTcgMTc0LjM3MSAxMTQuNTE4WiIgZmlsbD0iI2YwZjBmMSIvPgo8cGF0aCBkPSJNMTI1LjQxOCAxODAuNDIyTDk5LjUzMjUgNDguODA4OEM5OC41MjgxIDM5LjcxMTIgOTIuNjU4MyAzNi4xMzExIDc1LjE0MjIgMzkuODY5OEM2My4xNjEzIDQyLjQyNzEgMzUuNDM5OSA0Ny45OTQ2IDI0LjA1NTggNzUuNzEyOEMxMS43NDI4IDEwNS42OTMgNDUuNjg0OCAxOTAuNzc4IDkxLjI2NjEgMjExLjY4MUMxMjcuNzMxIDIyOC40MDMgMTI5LjIyOCAxOTcuODA5IDEyNS40MTggMTgwLjQyMloiIGZpbGw9IiNmMGYwZjEiLz4KPC9nPgo8L3N2Zz4K',
			)
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_space_post_type' );
