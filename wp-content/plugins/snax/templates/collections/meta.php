<?php
/**
 * Collection Meta Information
 *
 * @package snax 1.19
 * @subpackage Collections
 */
?>
<?php if ( 'public' === snax_get_collection_visibility() || snax_user_is_collection_owner() ) : ?>
	<p class="snax-collection-meta snax-meta">
		<?php snax_render_collection_author(); ?>

		<?php snax_render_collection_item_count(); ?>

		<?php if ( snax_get_collection_item_count( get_the_ID() ) ) : ?>
			<?php snax_render_collection_update(); ?>
		<?php endif; ?>
	</p>
<?php endif;

