<?php
/**
 * Functions
 *
 * @package media-ace
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}


add_filter( 'the_content',          'mace_lazy_load_content_video' );
add_filter( 'comment_text',         'mace_lazy_load_content_video', 99 );
add_filter( 'wp_kses_allowed_html', 'mace_video_allow_extra_html_attributes' );



function mace_video_allow_extra_html_attributes( $allowedposttags ) {
	$allowedposttags['video']['data-autoplay'] = true;

	return $allowedposttags;
}

function mace_lazy_load_content_video( $content ) {
//	if ( ! apply_filters( 'mace_lazy_load_image', true || is_embed() ) ) {
//		return $content;
//	}
//
//	if ( ! apply_filters( 'mace_lazy_load_content_image', true, $content ) ) {
//		return $content;
//	}



	// Find video tags.
	if ( preg_match_all('/<video[^>]+>/i', $content, $matches) ) {
		$lazy_class = mace_get_lazy_load_class();

		foreach( $matches[0] as $video_tag ) {
			$new_video_tag = $video_tag;

			// Html class not set.
			$html_class = '';

			// Extract html class.
			if ( preg_match('/class=["\']([^"\']+)["\']/i', $new_video_tag, $class_matches ) ) {
				$html_class = $class_matches[1];
			}

			if ( ! mace_can_add_lazy_load_class( $html_class ) ) {
				continue;
			}

			// Extract width attribute value.
			$width = 1600;
			if ( preg_match('/width=["\']([^"\']+)["\']/i', $new_video_tag, $matches ) ) {
				$width = (int) $matches[1];
			}

			// Extract height attribute value.
			$height = 900;
			if ( preg_match('/height=["\']([^"\']+)["\']/i', $new_video_tag, $matches ) ) {
				$height = (int) $matches[1];
			}

			// Thanks to this placeholder, browser will reserve correct space (blank) for future image.
			$placeholder = Mace_Lazy_Load::get_instance()->get_placeholder_src( $width, $height );

			$new_video_tag = str_replace(
				array(
					'autoplay=',
					'autoplay ',
					'class="',
					'class=\'',
				),
				array(
					'data-autoplay=',
					'data-autoplay ',
					'class="' . $lazy_class . ' ',
					'class=\'' . $lazy_class . ' ',
				),
				$new_video_tag
			);

			// class attribute was not replaced. We need to add it.
			if ( false === strpos( $new_video_tag, 'class=' ) ) {
				$new_video_tag = str_replace( '<video', '<video class="' . $lazy_class . '"', $new_video_tag );
			}

//			// Add data-expand attribute if enabled.
//			if ( mace_lazy_load_images_unveilling_effect_enabled() ) {
//				$new_video_tag = str_replace( '<video', '<video data-expand="600"', $new_video_tag );
//			}

			$content = str_replace( $video_tag, $new_video_tag, $content );

		}
	}

	return $content;
}

