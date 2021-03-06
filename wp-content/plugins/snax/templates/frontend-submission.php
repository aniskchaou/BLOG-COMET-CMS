<?php
/**
 * New post form
 *
 * @package snax 1.11
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Load dependencies, on demand.
add_action( 'wp_footer', 'snax_render_feedback' );

$snax_format_var = snax_get_url_var( 'format' );
$snax_format = (string) filter_input( INPUT_GET, $snax_format_var, FILTER_SANITIZE_STRING );
?>

<div class="snax">
	<?php
    // You have to be logged in to access a format page.
    if ( ! is_user_logged_in() && ! empty( $snax_format ) ) {
        // Trigger the Login popup.
        snax_frontend_submission_render_scripts();
        return;
    }

	if ( is_user_logged_in() && snax_user_reached_submitted_posts_limit() ) {
		snax_get_template_part( 'notes/limit-posts-daily' );
		return;
	}

	if ( is_user_logged_in() && ! current_user_can( 'snax_add_posts' ) ) {
		snax_get_template_part( 'notes/access-denied' );
		return;
	}

	if ( is_user_logged_in() && ! current_user_can( 'snax_upload_files' ) ) {
		snax_get_template_part( 'notes/action-forbidden' );
		return;
	}


	$snax_post_id = filter_input( INPUT_GET, snax_get_url_var( 'post' ), FILTER_SANITIZE_NUMBER_INT );

	if ( $snax_post_id && ! current_user_can( 'snax_edit_posts', $snax_post_id ) ) {
		snax_get_template_part( 'notes/access-denied' );
		return;
	}

	if ( empty( $snax_format ) && snax_get_format_count() === 1 ) {
		$snax_formats_ids = snax_get_active_formats_ids();

		$snax_format = array_pop( $snax_formats_ids );
	}

	if ( apply_filters( 'snax_frontend_submission_active_format_check', true ) && ! snax_is_active_format( $snax_format ) ) {
		$snax_format = '';
	}
	?>
	<?php do_action( 'snax_before_frontend_submission' ); ?>

	<?php snax()->set_current_format( $snax_format ); ?>

	<?php snax_get_template_part( 'form-frontend-submission', $snax_format ); ?>

	<?php do_action( 'snax_after_frontend_submission' ); ?>
</div>
