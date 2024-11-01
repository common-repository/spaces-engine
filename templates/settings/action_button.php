<?php

namespace SpacesEngine;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$action_button = get_post_meta( get_the_ID(), 'space_action_button', true );

$whatsapp_message = get_post_meta( get_the_ID(), 'space_action_button_whatsapp_message', true );
$phone            = get_post_meta( get_the_ID(), 'space_action_button_phone', true );
$email            = get_post_meta( get_the_ID(), 'space_action_button_email', true );
$contact_us       = get_post_meta( get_the_ID(), 'space_action_button_contact_us', true );
$website          = get_post_meta( get_the_ID(), 'space_action_button_website', true );
$website_video    = get_post_meta( get_the_ID(), 'space_action_button_website_video', true );
$visit_group      = get_post_meta( get_the_ID(), 'space_action_button_visit_group', true );
$sign_up          = get_post_meta( get_the_ID(), 'space_action_button_sign_up', true );
$start_order      = get_post_meta( get_the_ID(), 'space_action_button_start_order', true );
$view_shop        = get_post_meta( get_the_ID(), 'space_action_button_view_shop', true );
$get_tickets      = get_post_meta( get_the_ID(), 'space_action_button_get_tickets', true );

?>

<div id="space-setting-action_button-content" class="tab-content settings-tab-content">

	<?php do_action( 'spaces_engine_before_action_button_settings' ); ?>

	<h3 class="space-screen-title">
		<?php esc_html_e( 'Action Button', 'wpe-wps' ); ?>
	</h3>

	<p class="description"><?php esc_html_e( 'Tell people how to engage with you by customizing the action button at the top of your Page.', 'wpe-wps' ); ?></p>
	<fieldset class="space-action-button">
		<strong><?php esc_html_e( 'Get people to contact you', 'wpe-wps' ); ?></strong>

		<ul>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-whatsapp_message"><?php esc_html_e( 'Send WhatsApp Message', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Starts chat through WhatsApp', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="whatsapp_message" id="bp-action-whatsapp_message" <?php checked( $action_button, 'whatsapp_message' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- Whatsapp Message-->
				<div id="space-action-whatsapp_message" class="space-action-info">
					<strong><?php esc_html_e( 'Connect Your Account', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( 'Enter your WhatsApp or WhatsApp Business number, then check your WhatsApp messages for a confirmation code.', 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_whatsapp_message]" value="<?php echo esc_attr( $whatsapp_message ); ?>" placeholder="<?php esc_html_e( 'Phone Number', 'wpe-wps' ); ?>" />
				</div>

			</li>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-phone"><?php esc_html_e( 'Call Now', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Starts a phone call', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="phone" id="bp-action-phone" <?php checked( $action_button, 'phone' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- Call Now-->
				<div id="space-action-phone" class="space-action-info">
					<strong><?php esc_html_e( 'Call Now', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( 'This makes it easy for people to call the phone number you\'d like them to use.', 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_phone]" value="<?php echo esc_attr( $phone ); ?>" placeholder="<?php esc_html_e( 'Phone Number', 'wpe-wps' ); ?>" />
				</div>
			</li>

			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-email"><?php esc_html_e( 'Send Email', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Send an email message', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="email" id="bp-action-email" <?php checked( $action_button, 'email' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- Email-->
				<div id="space-action-email" class="space-action-info">
					<strong><?php esc_html_e( 'Send Email', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( "This makes it easy for people to send you an email at the address you'd like them to use.", 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_email]" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php esc_html_e( 'Add your email', 'wpe-wps' ); ?>" />
				</div>
			</li>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-contact_us"><?php esc_html_e( 'Contact Us', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Opens a website with contact info', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="contact_us" id="bp-action-contact_us" <?php checked( $action_button, 'contact_us' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- contact_us-->
				<div id="space-action-contact_us" class="space-action-info">
					<strong><?php esc_html_e( 'Contact Us', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( 'To make it easier for people to take certain actions, choose a website to open when they tap or click your button.', 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_contact_us]" value="<?php echo esc_attr( $contact_us ); ?>" placeholder="<?php esc_html_e( 'Add website link', 'wpe-wps' ); ?>" />
				</div>
			</li>
		</ul>
	</fieldset>

	<fieldset class="space-action-button">
		<strong><?php esc_html_e( 'Link to your group or app', 'wpe-wps' ); ?></strong>
		<ul>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-website"><?php esc_html_e( 'Learn More', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Opens a website', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="website" id="bp-action-website" <?php checked( $action_button, 'website' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- Learn More -->
				<div id="space-action-website" class="space-action-info">
					<strong><?php esc_html_e( 'Learn More', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( 'This makes it easy for people to visit a website or app.', 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_website]" value="<?php echo esc_attr( $website ); ?>" placeholder="<?php esc_html_e( 'Add website link', 'wpe-wps' ); ?>" />
				</div>
			</li>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-website_video"><?php esc_html_e( 'Watch Now', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Opens a website with a video', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="website_video" id="bp-action-website_video" <?php checked( $action_button, 'website_video' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- Watch Now -->
				<div id="space-action-website_video" class="space-action-info">
					<strong><?php esc_html_e( 'Watch Now', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( 'To make it easier for people to take certain actions, choose a website to open when they tap or click your button.', 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_website_video]" value="<?php echo esc_attr( $website_video ); ?>" placeholder="<?php esc_html_e( 'Add website link', 'wpe-wps' ); ?>" />
				</div>
			</li>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-visit_group"><?php esc_html_e( 'Visit Group', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Choose a group for people to visit', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="visit_group" id="bp-action-visit_group" <?php checked( $action_button, 'visit_group' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- Visit Group -->
				<div id="space-action-visit_group" class="space-action-info">
					<strong><?php esc_html_e( 'Visit Group', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( 'This makes it easy for people to visit a your group or app.', 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_visit_group]" value="<?php echo esc_attr( $visit_group ); ?>" placeholder="<?php esc_html_e( 'Add website link', 'wpe-wps' ); ?>" />
				</div>
			</li>
		</ul>

	</fieldset>

	<fieldset class="space-action-button">
		<strong><?php esc_html_e( 'Help people support you', 'wpe-wps' ); ?></strong>
		<ul>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-sign_up"><?php esc_html_e( 'Sign Up', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Opens a website with a sign-up form', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="sign_up" id="bp-action-sign_up" <?php checked( $action_button, 'sign_up' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- Sign Up -->
				<div id="space-action-sign_up" class="space-action-info">
					<strong><?php esc_html_e( 'Sign Up', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( 'To make it easier for people to take certain actions, choose a website to open when they tap or click your button.', 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_sign_up]" value="<?php echo esc_attr( $sign_up ); ?>" placeholder="<?php esc_html_e( 'Add website link', 'wpe-wps' ); ?>" />
				</div>
			</li>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-start_order"><?php esc_html_e( 'Start Order', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Opens a website for a restaurant', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="start_order" id="bp-action-start_order" <?php checked( $action_button, 'start_order' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- Start Order -->
				<div id="space-action-start_order" class="space-action-info">
					<strong><?php esc_html_e( 'Start Order', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( 'To make it easier for people to take certain actions, choose a website to open when they tap or click your button.', 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_start_order]" value="<?php echo esc_attr( $start_order ); ?>" placeholder="<?php esc_html_e( 'Add website link', 'wpe-wps' ); ?>" />
				</div>
			</li>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-view_shop"><?php esc_html_e( 'View Shop', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Choose where people can find your products', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="view_shop" id="bp-action-view_shop" <?php checked( $action_button, 'view_shop' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- View Shop -->
				<div id="space-action-view_shop" class="space-action-info">
					<strong><?php esc_html_e( 'View Shop', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( 'This makes it easy for people to visit your shop.', 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_view_shop]" value="<?php echo esc_attr( $view_shop ); ?>" placeholder="<?php esc_html_e( 'Add website link', 'wpe-wps' ); ?>" />
				</div>
			</li>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-get_tickets"><?php esc_html_e( 'Get Tickets', 'wpe-wps' ); ?></label>
						<span><?php esc_html_e( 'Choose where people can find tickets', 'wpe-wps' ); ?></span>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="get_tickets" id="bp-action-get_tickets" <?php checked( $action_button, 'get_tickets' ); ?> class="space-action-radio"/>
					</div>
				</div>
				<!-- Get Tickets -->
				<div id="space-action-get_tickets" class="space-action-info">
					<strong><?php esc_html_e( 'Get Tickets', 'wpe-wps' ); ?></strong>
					<span><?php esc_html_e( 'This makes it easy for people to find tickets.', 'wpe-wps' ); ?></span>
					<input type="text" name="space_info[action_button_get_tickets]" value="<?php echo esc_attr( $get_tickets ); ?>" placeholder="<?php esc_html_e( 'Add website link', 'wpe-wps' ); ?>" />
				</div>
			</li>
		</ul>
	</fieldset>

	<fieldset class="space-action-button">
		<strong><?php esc_html_e( 'Remove action button from your page', 'wpe-wps' ); ?></strong>
		<ul>
			<li>
				<div class="space-action-wrap">
					<div class="space-action-label">
						<label for="bp-action-sign_up"><?php esc_html_e( 'Remove action button', 'wpe-wps' ); ?></label>
					</div>
					<div class="space-action-value">
						<input type="radio" name="space_info[action_button]" value="none" id="bp-action-none" <?php checked( $action_button, 'none' ); ?> class="space-action-radio"/>
					</div>
				</div>
			</li>
		</ul>
	</fieldset>

	<?php do_action( 'spaces_engine_after_action_button_settings' ); ?>


</div>
