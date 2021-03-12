
<?php
/**
 * CommentAce integration
 *
 * @license For the full license information, please view the Licensing folder
 * that was distributed with this source code.
 *
 * @package Snax_Plugin
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'snax_template_before_items_loop', function () {
    add_filter( 'cace_comment_types', 'snax_cace_only_wp_comment_type' );
} );

add_action( 'snax_template_after_items_loop', function () {
    remove_filter( 'cace_comment_types', 'snax_cace_only_wp_comment_type' );
} );

function snax_cace_only_wp_comment_type( $types ) {
    return array(
        COMMENT_TYPE_WORDPRESS => $types[ COMMENT_TYPE_WORDPRESS ]
     );
}
