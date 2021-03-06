<?php
/**
 * New post form for format "Embed"
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
$snax_post_format = 'embed';
$snax_has_embeds = snax_has_user_cards( $snax_post_format );

// HTML classes of the form.
$snax_class = array(
	'snax',
	'snax-form-frontend',
	'snax-form-frontend-format-' . $snax_post_format,
);
if ( ! $snax_has_embeds ) {
	$snax_class[] = 'snax-form-frontend-without-media';
	add_filter( 'snax_form_file_upload_no_media', '__return_true' );
}
?>

<?php do_action( 'snax_before_frontend_submission_form', $snax_post_format ); ?>

	<form action="" method="post" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_class ) ); ?>">
		<?php do_action( 'snax_frontend_submission_form_start', $snax_post_format ); ?>

		<div class="snax-form-main">
			<h2 class="snax-form-main-title screen-reader-text"><?php esc_html_e( 'Share your story', 'snax' ); ?></h2>

			<?php snax_get_template_part( 'posts/form-edit/row-title' ); ?>

			<div class="snax-edit-post-row-media">
				<?php
				$snax_key = $snax_post_format;

				$snax_class = array(
					'snax-tab-content',
					'snax-tab-content-' . $snax_key,
					'snax-tab-content-' . ( $snax_has_embeds ? 'hidden' : 'visible' ),
				);

				$snax_class[] = 'snax-tab-content-current';
				?>
				<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_class ) ); ?>">
					<?php snax_get_template_part( 'posts/form-edit/new', $snax_key ); ?>
				</div>
			</div>

			<div class="snax-cards">
				<?php if ( $snax_has_embeds ) : ?>

					<?php snax_get_template_part( 'loop-embeds' ); ?>

				<?php endif; ?>
			</div><!-- .snax-cards -->

			<?php snax_get_template_part( 'posts/form-edit/row-source' ); ?>
			<?php snax_get_template_part( 'posts/form-edit/row-description' ); ?>
			<?php snax_get_template_part( 'posts/form-edit/row-referral' ); ?>

		</div><!-- .snax-form-main -->

		<div class="snax-form-side">
			<h2 class="snax-form-side-title screen-reader-text"><?php esc_html_e( 'Publish Options', 'snax' ); ?></h2>

			<input type="hidden" name="snax-post-format" value="embed"/>

			<?php
			if ( snax_embed_show_featured_media_field() ) {
				snax_get_template_part( 'posts/form-edit/row-featured-image' );
			}
			?>

			<?php
			if ( snax_embed_show_category_field() ) {
				snax_get_template_part( 'posts/form-edit/row-categories' );
			}
			?>

			<?php snax_get_template_part( 'posts/form-edit/row-tags' ); ?>

			<?php snax_get_template_part( 'posts/form-edit/row-legal' ); ?>

			<?php snax_get_template_part( 'posts/form-edit/row-actions' ); ?>

		</div><!-- .snax-form-side -->

		<?php do_action( 'snax_frontend_submission_form_end', $snax_post_format ); ?>
	</form>

<?php do_action( 'snax_after_frontend_submission_form', $snax_post_format ); ?>
