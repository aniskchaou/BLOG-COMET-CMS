<?php
/**
 * Pagination for pages of posts
 *
 * @package snax 1.11
 * @subpackage Theme
 */

?>

<?php do_action( 'snax_template_before_pagination_loop' ); ?>

<div id="pag-top" class="pagination no-ajax">
	<div class="pag-count">
		<?php snax_collections_pagination_count(); ?>
	</div>

	<div class="pagination-links">

		<?php snax_collections_pagination_links(); ?>

	</div>
</div>

<?php do_action( 'snax_template_after_pagination_loop' ); ?>
