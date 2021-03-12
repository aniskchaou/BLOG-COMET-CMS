<?php
/**
 * Admin Hooks
 *
 * @package whats-your-reaction
 * @subpackage Hooks
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Assets.
add_action( 'admin_enqueue_scripts', 'ma_admin_enqueue_styles' );
add_action( 'admin_enqueue_scripts', 'ma_admin_enqueue_scripts' );

add_filter( 'plugin_action_links', 'ma_add_plugin_settings_link', 10, 2 );
