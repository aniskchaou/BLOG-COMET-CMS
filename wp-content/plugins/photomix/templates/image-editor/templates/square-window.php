<?php
/**
 * Image editor template part
 *
 * @package photomix
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if ( ! current_user_can('upload_files') ) {
	wp_die(__('Sorry, you are not allowed to upload files.'));
}

$photomix_editor = photomix_get_editor_config();
?>

<div class="photomix-editor photomix-tpl-window">
	<canvas id="photomix-canvas" width="<?php echo absint( $photomix_editor['width'] ) ?>" height="<?php echo absint( $photomix_editor['height'] ); ?>"></canvas>

	<div class="photomix-actions-wrapper">
		<div class="photomix-image-actions photomix-image-actions-disabled photomix-image-left">

			<?php photomix_get_template_part( 'image-editor/image-actions' ) ?>

		</div>
		<div class="photomix-image-actions photomix-image-actions-disabled photomix-image-right">

			<?php photomix_get_template_part( 'image-editor/image-actions' ) ?>

		</div>
	</div>
</div>

<script type="text/javascript">
	(function(ctx) {
		var canvasWidth  = parseInt(<?php echo absint( $photomix_editor['width'] ); ?>, 10);
		var canvasHeight = parseInt(<?php echo absint( $photomix_editor['height'] ); ?>, 10);
		var windowWidth  = Math.round(canvasWidth / 2);
		var windowHeight = Math.round(canvasHeight / 2);;
		var windowOffset = 0;
		var gapWidth 	 = parseInt(<?php echo absint( $photomix_editor['gutter_on'] ? $photomix_editor['width'] / 100 : 0 ); ?>, 10);

		var leftImageConfig     = <?php echo photomix_get_mask_js_config( 'left' ); ?>;
		leftImageConfig.zIndex  = 1;

		var rightImageConfig     = <?php echo photomix_get_mask_js_config( 'right' ); ?>;
		rightImageConfig.zIndex  = 3;

		ctx.photomixEditorConfig = {
			canvas: {
				backgroundColor: '<?php echo sanitize_hex_color( $photomix_editor['gutter_on'] ? $photomix_editor['gutter_color'] : '' ); ?>',
				objects:         <?php echo photomix_get_objects_js_config(); ?>,
				shapes:          {
					'color': '<?php echo sanitize_hex_color( $photomix_editor['shape_color'] ? $photomix_editor['shape_color'] : '' ); ?>'
				},
				icons: {
					'noPhoto':   '<?php echo trailingslashit( photomix_get_plugin_url() ) . 'modules/image-editor/assets/images/no-photo.svg'; ?>',
					'masks': {
						'left': {
							xOffset: canvasWidth / 4 - 25,
							yOffset: canvasHeight / 4 - 25
						}
					}
				}
			},
			masks: {
				left: {
					width: 		canvasWidth,
					height: 	canvasHeight,
					left:		0,
					top:		0,
					fill:		'<?php echo sanitize_hex_color( $photomix_editor['background_color'] ) ?>',
					zIndex:		0,
					image:      leftImageConfig
				},
				right: {
					width: 		windowWidth,
					height: 	windowHeight,
					left:		canvasWidth - windowWidth - windowOffset,
					top:		canvasHeight - windowHeight - windowOffset,
					fill:		'<?php echo sanitize_hex_color( $photomix_editor['background_color'] ) ?>',
					stroke:     '<?php echo sanitize_hex_color( $photomix_editor['gutter_on'] ? $photomix_editor['gutter_color'] : '' ); ?>',
					strokeWidth: gapWidth,
					zIndex:		2,
					image:      rightImageConfig
				}
			}
		};
	})(window);
</script>