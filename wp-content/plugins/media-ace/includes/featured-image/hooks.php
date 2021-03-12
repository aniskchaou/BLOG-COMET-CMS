<?php
/**
 * Hooks
 *
 * @package media-ace
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Default Featured Image.
add_filter( 'has_post_thumbnail', 'mace_has_default_featured_image', 20, 5 );
add_filter( 'get_post_metadata',  'mace_set_default_featured_image', 10, 4 );

// Fetch Featured Image for Embeds.
add_action( 'save_post', 'mace_download_featured_media_for_video', 10, 1 );
