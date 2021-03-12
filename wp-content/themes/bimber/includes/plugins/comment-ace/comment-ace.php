<?php
/**
 * CommentAce plugin functions
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Bimber_Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'cace_fb_color_scheme', 'bimber_cace_fb_color_scheme', 10, 1 );
add_filter( 'cace_vote_classes', 'bimber_cace_vote_classes', 10, 1 );

/**
 * Adjust FB color scheme based on the theme's setup
 *
 * @return string
 */
function bimber_cace_fb_color_scheme() {
    return bimber_get_theme_option( 'global', 'skin' );
}

/**
 * Apply Snax Login class
 *
 * @param array $classes
 *
 * @return array
 */
function bimber_cace_vote_classes( $classes ) {
    if ( bimber_can_use_plugin( 'snax/snax.php' ) && ! is_user_logged_in() && ! \Commentace\is_guest_voting_enabled() ) {
        $classes[] = 'snax-login-required';
    }

    return $classes;
}