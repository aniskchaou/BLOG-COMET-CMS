<?php
/**
 * New post form for format "Versus Poll"
 *
 * @package snax 1.11
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}
?>

<?php
global $snax_post_format;
$snax_post_format = 'versus_poll';

// HTML classes of the form.
$snax_class = array(
	'snax',
	'snax-form-frontend',
	'snax-form-without-items',
	'snax-form-frontend-format-' . $snax_post_format,
);
if ( snax_is_frontend_submission_edit_mode() ) {
	$snax_class[] = 'snax-form-frontend-edit-mode';
}
?>

<?php do_action( 'snax_before_frontend_submission_form', $snax_post_format ); ?>

	<form action="" method="post" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_class ) ); ?>">
		<?php do_action( 'snax_frontend_submission_form_start', $snax_post_format ); ?>

		<div class="snax-form-main">
			<h2 class="snax-form-main-title screen-reader-text"><?php esc_html_e( 'Share your story', 'snax' ); ?></h2>

			<?php snax_get_template_part( 'posts/form-edit/row-title' ); ?>

			<?php snax_get_template_part( 'posts/form-edit/row-description' ); ?>

			<div class="snax-edit-post-row-media snax-poll-upload">
				<?php
				$snax_key = 'image';

				$snax_class = array(
					'snax-tab-content',
					'snax-tab-content-' . $snax_key,
					'snax-tab-content-hidden',
					'snax-tab-content-current',
				);
				?>
				<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_class ) ); ?>">
					<?php add_filter( 'snax_plupload_config', 'snax_plupload_allow_multi_selection' ); ?>
					<?php snax_get_template_part( 'posts/form-edit/new', $snax_key ); ?>
				</div>
			</div>

			<?php
			// Load JS.
			wp_enqueue_script('backbone');
			wp_enqueue_media();
			wp_enqueue_script( 'snax-poll-common', 	snax_get_includes_url() . 'polls/admin/js/common.js', array( 'jquery' ), snax_get_version(), true );
			wp_enqueue_script( 'snax-versus-poll',  snax_get_includes_url() . 'polls/admin/js/versus-poll.js', array( 'backbone', 'snax-poll-common', 'jquery', 'jquery-ui-sortable' ), snax_get_version(), true );
			
			$poll_config = array(
				'i18n' => array(
					'yes'	=> __( "Yes", 'snax' ),
					'no'	=> __( "No", 'snax' ),
				),
			);
			wp_localize_script( 'snax-versus-poll', 'snax_versus_poll_config', wp_json_encode( $poll_config ) );

			$snax_post_id = (int) filter_input( INPUT_GET, snax_get_url_var( 'post' ), FILTER_SANITIZE_NUMBER_INT );

			if ( $snax_post_id ) {
				$poll_post = get_post( $snax_post_id );
			} else {
				$poll_post = snax_get_user_draft_poll( 'versus' );
			}

			// Set up global post to poll draft.
			global $post;
			$current_post = $post;

			$post = $poll_post;

			// Load template.
			add_filter( 'snax_poll_settings_tab_active', '__return_false' );

			snax_get_template_part( 'polls/versus/form-frontend/poll-tpl' );

			$post = $current_post;
			wp_reset_postdata();
			?>

		</div><!-- .snax-form-main -->

		<div class="snax-form-side">
			<h2 class="snax-form-side-title screen-reader-text"><?php esc_html_e( 'Publish Options', 'snax' ); ?></h2>

			<input type="hidden" name="snax-post-format" value="versus_poll"/>

			<?php
			global $snax_post_id;
			$snax_post_id = $poll_post->ID;

			if ( snax_poll_show_featured_media_field() ) {
				snax_get_template_part( 'posts/form-edit/row-featured-image' );
			}
			?>

			<?php
			if ( snax_poll_show_category_field() ) {
				snax_get_template_part( 'posts/form-edit/row-categories' );
			}
			?>

			<?php snax_get_template_part( 'posts/form-edit/row-tags' ); ?>

			<?php snax_get_template_part( 'posts/form-edit/row-legal' ); ?>

			<?php snax_get_template_part( 'posts/form-edit/row-draft-actions' ); ?>

			<?php snax_get_template_part( 'posts/form-edit/row-actions' ); ?>
		</div><!-- .snax-form-side -->

		<?php do_action( 'snax_frontend_submission_form_end', $snax_post_format ); ?>
	</form>

<?php do_action( 'snax_after_frontend_submission_form', $snax_post_format ); ?>
