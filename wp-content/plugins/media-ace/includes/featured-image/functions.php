<?php
/**
 * Featured Images Functions
 *
 * @package media-ace
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Check whether a post has a post thumbnail.
 *
 * @param bool             $has_thumbnail true if the post has a post thumbnail, otherwise false.
 * @param int|WP_Post|null $post          Post ID or WP_Post object. Default is global `$post`.
 * @param int|false        $thumbnail_id  Post thumbnail ID or false if the post does not exist.
 *
 * @return bool
 */
function mace_has_default_featured_image( $has_thumbnail, $post, $thumbnail_id ) {
    // Skip, if the post has a featured image set.
    if ($has_thumbnail) {
        return $has_thumbnail;
    }

    // Skip, if the post doesn't support the featured images.
    if (!post_type_supports(get_post_type($post), 'thumbnail')) {
        return $has_thumbnail;
    }

    if ( ! empty( mace_get_default_featured_image() ) ) {
        $has_thumbnail = true;
    }

    return $has_thumbnail;
}

/**
 * Set the meta data
 *
 * @param null|mixed $value     Current meta value.
 * @param int        $object_id ID of the object metadata is for.
 * @param string     $meta_key  Optional. Metadata key
 * @param bool       $single    Optional, default is false.
 *
 * @return string|array
 */
function mace_set_default_featured_image( $value, $object_id, $meta_key, $single ) {
    // Skip, if the admin access, except for AJAX calls.
    if ( ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) ) {
        return $value;
    }

    // Skip, if a key is not the '_thumbnail_id'.
    if ( ! empty( $meta_key ) && '_thumbnail_id' !== $meta_key ) {
        return $value;
    }

    // Skip, if the post doesn't support featured images.
    if ( ! post_type_supports( get_post_type( $object_id ), 'thumbnail' ) ) {
        return $value;
    }

    // Get current Cache.
    $meta_cache = wp_cache_get( $object_id, 'post_meta' );

    if ( ! $meta_cache ) {
        $meta_cache = update_meta_cache( 'post', array( $object_id ) );

        if ( isset( $meta_cache[ $object_id ] ) ) {
            $meta_cache = $meta_cache[ $object_id ];
        } else {
            $meta_cache = array();
        }
    }

    // Skip, if the _thumbnail_id is present in cache.
    if ( ! empty( $meta_cache['_thumbnail_id'][0] ) ) {
        return $value;
    }

    // Get the Default Featured Image ID.
    $default_image_id = mace_get_default_featured_image();

    // Set the Default Image in cache.
    $meta_cache['_thumbnail_id'][0] = apply_filters( 'mace_default_thumbnail_id', $default_image_id, $object_id );
    wp_cache_set( $object_id, $meta_cache, 'post_meta' );

    return $value;
}

/**
 * Auto download featured image for video format
 *
 * @param int $post_id  Post id.
 */
function mace_download_featured_media_for_video( $post_id ) {
	if ( ! mace_get_auto_featured_image_enable() ) {
		return;
	}
	$format = get_post_format( $post_id );
	if ( ! has_post_thumbnail( $post_id ) && 'video' === $format ) {
		remove_action( 'save_post', 'mace_download_featured_media_for_video', 10, 1 );
		mace_download_embed_featured_media( $post_id );
		add_action( 'save_post', 'mace_download_featured_media_for_video', 10, 1 );
	}
}

/**
 * Download embedded content thumbnail and set as featured media
 *
 * @param int $post_id      Post id.
 */
function mace_download_embed_featured_media( $post_id ) {
	$embed_post = get_post( $post_id );
	$url        = mace_get_first_url_in_content( $embed_post );
	$max_width  = 1000; // Max thumbnail width.
	remove_filter( 'oembed_providers', '__return_empty_array' );
	$wp_oembed    = new WP_oEmbed();
	add_filter( 'oembed_providers', '__return_empty_array' );
	$provider_url = $wp_oembed->get_provider( $url );
	$img_url      = '';
	$max_res_set  = false;

	if ( ! empty( $provider_url ) ) {
		$json    = file_get_contents( $provider_url . '?url=' . $url . '&maxwidth=' . $max_width . '&format=json' );
		$json    = json_decode( $json );
		$img_url = $json->thumbnail_url;
	}

	// Special treatment for YT to try to get maxresdefault.jpg.
	if ( strpos( $url, 'youtube' ) || strpos( $url, 'youtu.be' ) ) {
		$max_res_yt_img_url = str_replace( 'hqdefault', 'maxresdefault', $img_url );
		$max_res_set        = mace_sideload_featured_media( $max_res_yt_img_url, $post_id );
	}

	if ( ! empty( $img_url ) && ! $max_res_set ) {
		mace_sideload_featured_media( $img_url, $post_id );
	}
}

/**
 * Don't download the featured image if not on the supported provider list
 *
 * @param bool $result      False by default.
 * @param int  $url         Embed url.
 *
 * @return bool
 */
function mace_featured_media_supported_providers( $result, $url ) {
	$supported_providers = array(
		'youtube',
		'youtu.be',
		'vimeo',
		'dailymotion',
		'dai.ly',
		'flickr',
		'flic.kr',
		'photobucket',
		'funnyordie',
		'vine',
		'soundcloud',
		'slideshare',
		'instagr.am',
		'instagram',
		'issuu',
		'collegehumor',
		'tedcom',
		'kickstarter',
		'kck.st',
		'cloudup',
	);

	foreach ( $supported_providers as &$provider ) {
		if ( strpos( $url, $provider ) ) {
			$result = true;
		}
	}

	return $result;
}

/**
 * Get image from url and set is as featured
 *
 * @param string $img_url       Image url.
 * @param int    $post_id       Post id.
 *
 * @return bool
 */
function mace_sideload_featured_media( $img_url, $post_id ) {
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	add_action( 'add_attachment', 'mace_set_sideloaded_thumb_as_featured' );

	if ( is_wp_error( media_sideload_image( $img_url, $post_id, 'The embed thumbnail', 'src' ) ) ) {
		remove_action( 'add_attachment', 'mace_set_sideloaded_thumb_as_featured' );

		return false;
	};

	remove_action( 'add_attachment', 'mace_set_sideloaded_thumb_as_featured' );

	return true;
}

/**
 * When attachement is added, set it as featured media for parent
 *
 * @param int $att_id Attachment id.
 */
function mace_set_sideloaded_thumb_as_featured( $att_id ) {
	$att     = get_post( $att_id );
	$post_id = $att->post_parent;

	set_post_thumbnail( $post_id, $att_id );
}

/**
 * Check whether to lazy load images
 *
 * @return string
 */
function mace_get_auto_featured_image_enable() {
	return 'standard' === get_option( 'mace_auto_featured_image_enable', 'standard' );
}

/**
 * Return ID of the default featured image
 *
 * @return int|string   Image ID or empty string
 */
function mace_get_default_featured_image() {
    return get_option( 'mace_default_featured_image', '' );
}
