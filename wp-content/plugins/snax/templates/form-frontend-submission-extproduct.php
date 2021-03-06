<?php
/**
 * New post form for format "External Product"
 *
 * @package snax 1.11
 * @subpackage Theme
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

wp_enqueue_script( 'snax-external-product-front-form', snax()->includes_url . 'formats/external-product/js/front-form.js', array( 'snax-front' ), snax()->version, true );
?>

<?php
global $snax_post_format;
$snax_post_format = 'extproduct';

// HTML classes of the form.
$snax_class = array(
	'snax',
	'snax-form-frontend',
	'snax-form-without-items',
	'snax-form-frontend-format-' . $snax_post_format,
);

$snax_class[] = 'snax-form-frontend-without-media';
add_filter( 'snax_form_file_upload_no_media', '__return_true' );
?>

<?php do_action( 'snax_before_frontend_submission_form', $snax_post_format ); ?>

	<form action="" method="post" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_class ) ); ?>">
		<?php do_action( 'snax_frontend_submission_form_start', $snax_post_format ); ?>

		<div class="snax-form-main">
			<h2 class="snax-form-main-title screen-reader-text"><?php esc_html_e( 'Share your product', 'snax' ); ?></h2>

			<?php snax_get_template_part( 'posts/form-edit/row-title' ); ?>

			<div class="snax-edit-post-row-url">
				<label for="snax-post-url"><?php esc_html_e( 'URL', 'snax' ); ?></label>

				<input name="snax-post-url"
				       id="snax-post-url"
				       type="url"
				       value=""
				       placeholder="<?php esc_attr_e( 'Enter URL&hellip;', 'snax' ); ?>"
				       autocomplete="off"
				       required
				       readonly
				/>
			</div>

			<?php snax_get_template_part( 'posts/form-edit/row-description' ); ?>

			<div class="snax-edit-post-row-media">
				<?php
				$snax_key = $snax_post_format;

				$snax_class = array(
					'snax-tab-content',
					'snax-tab-content-' . $snax_key,
					'snax-tab-content-visible',
				);

				$snax_class[] = 'snax-tab-content-current';
				?>
				<div class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $snax_class ) ); ?>">
					<?php snax_get_template_part( 'posts/form-edit/new', $snax_key ); ?>
				</div>
			</div>

			<div class="snax-edit-post-row-prices">
				<?php snax_get_template_part( 'posts/form-edit/row-wc-regular-price' ); ?>
				<?php snax_get_template_part( 'posts/form-edit/row-wc-sale-price' ); ?>
			</div>

		</div><!-- .snax-form-main -->

		<div class="snax-form-side">
			<h2 class="snax-form-side-title screen-reader-text"><?php esc_html_e( 'Publish Options', 'snax' ); ?></h2>

			<input type="hidden" name="snax-post-format" value="extproduct"/>
			<input type="hidden" name="snax-tags-taxonomy" value="product_tag"/>
			<input type="hidden" name="snax-categories-taxonomy" value="product_cat"/>

			<?php
			if ( snax_extproduct_show_featured_media_field() ) {
				snax_get_template_part( 'posts/form-edit/row-featured-image' );
			}
			?>

			<?php
			if ( snax_extproduct_show_category_field() ) {
				set_query_var( 'snax_categories_taxonomy', 'product_cat' );
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
