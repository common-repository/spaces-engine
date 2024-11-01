<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The core plugin admin class.
 */
class Spaces_Engine_Admin {
	/**
	 * Define the admin functionality of the plugin.
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
		// add_action( 'admin_notices', array( $this, 'add_upsell' ) );

		add_filter( 'wp_privacy_personal_data_exporters', array( $this, 'register_exporter' ) );
	}

	/**
	 * Add an upsell banner to our admin pages. Ensures that we don't spam any non-Spaces Engine page as per WordPress guidelines.
	 *
	 * @return void
	 */
	public function add_upsell() {
		/**
		 * Check whether the get_current_screen function exists
		 * because it is loaded only after 'admin_init' hook.
		 */
		if ( function_exists( 'get_current_screen' ) ) {
			$current_screen = get_current_screen();

			if ( 'wpe_wpspace' === $current_screen->post_type ) : ?>
				<a href="https://spacesengine.com" class="upsell" style="text-decoration: none">
					<div class="banner" style="border: 2px solid #8468F1;
                    display: flex;
                    padding-top: 20px;
                    width: 95%;
                    background: #fff;
                    padding: 20px;
                    justify-content: space-between;
                    align-items: center;
                    gap: 20px;
                    margin-top: 20px;">
						<img src="https://spacesengine.com/wp-content/uploads/2021/11/spaces-logo-green.png" height="50px">
						<h2 style="text-transform: uppercase; color: #8468F1">Many More Features Waiting in the Professional Edition</h2>
						<p><strong>Click</strong> to find out more, try the demo, and read the reviews</p>

					</div>
				</a>
			<?php
			endif;
		}
	}

	/**
	 * Registers the Spaces Engine exporter.
	 *
	 * Allows users to request an export of the information associated with any Space they have created.
	 *
	 * @param array $exporters_array The array of exporters.
	 *
	 * @return array The modified array of exporters.
	 */
	public function register_exporter( $exporters_array ) {
		$exporters_array['spaces_engine'] = array(
			'exporter_friendly_name' => 'Spaces Engine exporter', // isn't shown anywhere
			'callback'               => array( $this, 'exporter' ), // name of the callback function which is below
		);
		return $exporters_array;
	}

	/**
	 * Export spaces associated with the given email address.
	 *
	 * @param string $email_address The email address of the post author.
	 * @param int $iteration The iteration number for pagination (optional).
	 *
	 * @return array An array containing the exported space data and a boolean indicating if there are more spaces to export.
	 */
	public function exporter( $email_address, $iteration = 1 ) {
		$iteration = (int) $iteration;

		$export_items = array();

		if ( $spaces = get_posts(
			array(
				'post_type'      => 'wpe_wpspace',
				'posts_per_page' => 100, // how much to process each time
				'paged'          => $iteration,
				'post_author'    => $email_address,
				'numberposts'    => -1,
			)
		) ) {
			foreach ( $spaces as $space ) {
				$data = array(
					array(
						'name'  => 'Name',
						'value' => $space->post_title,
					),
					array(
						'name'  => 'Description',
						'value' => $space->post_content,
					),
					array(
						'name'  => 'Slug',
						'value' => $space->post_name,
					),
					array(
						'name'  => 'Created',
						'value' => $space->post_date,
					),
				);

				$meta = get_post_meta( $space->ID );

				foreach ( $meta as $key => $value ) {
					$data[] = array(
						'name'  => $key,
						'value' => $value[0],
					);
				}

				$export_items[] = array(
					'group_id'    => 'spaces',
					'group_label' => 'Spaces',
					'item_id'     => 'space-' . $space->ID,
					'data'        => $data,
				);
			}
		}

		// Tell core if we have more orders to work on still
		$done = count( $spaces ) < 100;
		return array(
			'data' => $export_items,
			'done' => $done,
		);
	}


}
