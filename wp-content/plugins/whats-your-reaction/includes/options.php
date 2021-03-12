<?php
/**
 * Settings options
 *
 * @package whats-your-reaction
 * @subpackage Options
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Return list of post types than can be voted
 *
 * @return array
 */
function wyr_voting_get_post_types() {
    $post_types = get_option( 'wyr_voting_post_types', array( 'post' ) );

    $post_types = apply_filters( 'wyr_voting_post_types', $post_types );

    if ( ! is_array( $post_types ) ) {
        $post_types = array();
    }

    return $post_types;
}

/**
 * Check wherever to show the Link
 *
 * @return string
 */
function wyr_member_profile_link() {
	return 'standard' === get_option( 'wyr_member_profile_link', 'none' );
}

/**
 * Return count base for fakes
 *
 * @return string
 */
function wyr_get_fake_reaction_count_base() {
	return get_option( 'wyr_fake_reaction_count_base', '' );
}

/**
 * Check wherever to randomize fake reactions
 *
 * @return string
 */
function wyr_randomize_fake_reactions() {
	return 'standard' === get_option( 'wyr_fake_reactions_randomize', 'none' );
}

/**
 * Check wherever to disable fakes for new submissions
 *
 * @return string
 */
function wyr_disable_fake_reactions_for_new_submissions() {
	return 'standard' === get_option( 'wyr_fake_reactions_disable_for_new', 'standard' );
}
