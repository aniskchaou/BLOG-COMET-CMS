<?php
/**
 * Snax Format Functions
 *
 * @package snax
 * @subpackage Formats
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'snax_get_default_options',         'snax_link_format_options' );
add_filter( 'snax_get_formats',                 'snax_register_link_format' );
add_action( 'snax_handle_link_submission',      'snax_process_link_submission', 10, 2 );
add_action( 'wp_ajax_snax_process_link_data',   'snax_ajax_process_link_data' );

/**
 * Set the format active
 *
 * @param array $options    Options.
 *
 * @return array
 */
function snax_link_format_options( $options ) {
	$options['snax_active_formats'][] = 'link';

	return $options;
}

/**
 * Register the format
 *
 * @param array $formats        List of formats.
 *
 * @return array
 */
function snax_register_link_format( $formats ) {
	$format_var = snax_get_url_var( 'format' );

	$formats['link'] = array(
		'labels'		=> array(
			'name' 			=> snax_link_get_singular_name( __( 'Link', 'snax' ) ),
			'add_new'		=> snax_link_get_add_new( __( 'Link', 'snax' ) ),
		),
		'description'	=> snax_link_get_description( __( 'External site\'s link', 'snax' ) ),
		'position'		=> 45,
		'url'           => add_query_arg( $format_var, 'link' ),
		'learn_more_url' => '',
	);

	return $formats;
}

/**
 * Link submission handler
 *
 * @param array $data             Link data.
 * @param WP    $request          Request object.
 */
function snax_process_link_submission( $data, $request ) {
	$post_id = snax_create_link( $data );

	if ( ! is_wp_error( $post_id ) ) {
		$url_var = snax_get_url_var( 'post_submission' );
		$redirect_url = add_query_arg( $url_var, 'success', get_permalink( $post_id ) );

		$redirect_url = apply_filters( 'snax_new_post_redirect_url', $redirect_url, $post_id );

		$request->set_query_var( 'snax_redirect_to_url', $redirect_url );
	}
}

/**
 * Create new link post
 *
 * @param array $data   Link data.
 *
 * @return int          Created post id.
 */
function snax_create_link( $data ) {
	$format = 'link';
	$post_format = 'link';
	$link_provider_name = '';

	$defaults = array(
		'id'			=> 0,
		'title'         => '',
		'description'   => '',
		'category_id'   => array(),
		'author'        => get_current_user_id(),
		'status'		=> 'pending',
		'source'        => '',
		'ref_link'      => '',
	);

	$data = wp_parse_args( $data, $defaults );

	$author_id  = (int) $data['author'];
	$status 	= $data['status'];

	$url     = filter_input( INPUT_POST, 'snax-post-url', FILTER_SANITIZE_URL );
	$url_tag = '';

	if ( ! empty( $url ) ) {
		$url_tag = sprintf( '<a href="%s">%s</a>', $url, $url );
	}

    $is_new_post = 0 === $data['id'];

    $post_status = '';

    // Set comments status explicitly on UPDATE. Otherwise it will be set to "closed".
    if ( ! $is_new_post ) {
        $post_status = comments_open( $data['id'] ) ? 'open' : 'closed';
    }

    $post_date   = '';

    // Don't change published date.
    if ( ! $is_new_post ) {
        $post = get_post( $data['id'] );

        $post_date = $post->post_date;
    }

	$new_post = array(
		'post_title'     => wp_strip_all_tags( $data['title'] ),
		'post_content'   => $url_tag . snax_kses_post( $data['description'] ),
		'post_excerpt'   => snax_kses_post( $data['description'] ),
		'post_author'    => $author_id,
		'post_status'    => $status,
        'post_date'      => $post_date,
        'comment_status' => $post_status,
		'post_type'      => 'post',
		'ID'			 => $data['id'],
	);

	add_filter( 'snax_is_format_being_published', '__return_true' );
	$post_id = wp_insert_post( $new_post );
	remove_filter( 'snax_is_format_being_published', '__return_true' );

	if ( 0 === $post_id ) {
		return new WP_Error( 'snax_link_creating_failed', esc_html__( 'Some errors occured while creating link.', 'snax' ) );
	}

	// Set featured image.
	if ( ! snax_is_featured_media_field_disabled( 'link' ) ) {
		$featured_image = snax_get_format_featured_image( 'link', $author_id, $data['id'] );

		if ( $featured_image ) {
			set_post_thumbnail( $post_id, $featured_image->ID );

			// Attach featured media to item (Media Library, the "Uploaded to" column).
			wp_update_post( array(
				'ID'            => $featured_image->ID,
				'post_parent'   => $post_id,
			) );

			snax_reset_format_featured_image( $featured_image );
		}
	}

	// Referral link.
	update_post_meta( $post_id, '_snax_ref_link', $data['ref_link'] );

	// Assign category.
	$category_id = $data['category_id'];

	if ( ! empty( $category_id ) ) {
		wp_set_post_categories( $post_id, $category_id );
	}

	// Reassign tags.
	snax_remove_post_tags( $post_id );

	$tags = $data['tags'];

	if ( ! empty( $tags ) ) {
		wp_set_post_tags( $post_id, $tags, true );
	}

	// Set WP post format.
	if ( $post_format ) {
		set_post_format( $post_id, $post_format );
	}

	// Set post metadata.
	snax_set_post_format( $post_id, $format );
	add_post_meta( $post_id, '_snax_link_provider_name', $link_provider_name );

	do_action( 'snax_post_added', $post_id, 'link' );

	return $post_id;
}

/**
 * Return featured media field visibility type
 *
 * @return string
 */
function snax_link_featured_media_field() {
	$default = 'required';

	return apply_filters( 'snax_link_featured_media_field', get_option( 'snax_link_featured_media_field', $default ) );
}

/**
 * Check whether to show the Featured Media on a single post
 *
 * @return bool
 */
function snax_link_show_featured_media() {
	$default = 'standard';

	return 'standard' === apply_filters( 'snax_link_show_featured_media', get_option( 'snax_link_show_featured_media', $default ) );
}

/**
 * Check whether to show the Featured Media field on form
 *
 * @return bool
 */
function snax_link_show_featured_media_field() {
	return 'disabled' !== snax_link_featured_media_field();
}

/**
 * Return the Category field visibility type
 *
 * @return string
 */
function snax_link_category_field() {
	$default = 'optional';

	return apply_filters( 'snax_link_category_field', get_option( 'snax_link_category_field', $default ) );
}

/**
 * Check whether to show the Category field on form
 *
 * @return bool
 */
function snax_link_show_category_field() {
	return 'disabled' !== snax_link_category_field();
}

/**
 * Check whether to allow multiple categories selection
 *
 * @return bool
 */
function snax_link_multiple_categories_selection() {
	$default = 'standard';

	return 'standard' === apply_filters( 'snax_link_category_multi', get_option( 'snax_link_category_multi', $default ) );
}

/**
 * Return list of allowed categories to select during front end post creation
 *
 * @return array
 */
function snax_link_get_category_whitelist() {
	$default = array( '' => '' );

	return apply_filters( 'snax_link_category_whitelist', get_option( 'snax_link_category_whitelist', $default ) );
}

/**
 * Return list of categories to be auto-assigned during front end post creation
 *
 * @return array
 */
function snax_link_get_category_auto_assign() {
	$default = array( '' => '' );

	return apply_filters( 'snax_link_category_auto_assign', get_option( 'snax_link_category_auto_assign', $default ) );
}

/**
 * Return the Referral link field visibility type
 *
 * @return string
 */
function snax_link_allow_snax_authors_to_add_referrals() {
	$default = 'none';

	return 'standard' === apply_filters( 'snax_link_allow_snax_authors_to_add_referrals', get_option( 'snax_link_allow_snax_authors_to_add_referrals', $default ) );
}

/**
 * Return demo posts data
 *
 * @return array
 */
function snax_link_get_demos() {
	$post_ids = snax_get_demo_post_ids( 'link' );

	$demos_data = array();

	foreach ( $post_ids as $post_id ) {
		$post = get_post( $post_id );

		if ( 'link' !== get_post_format( $post ) ) {
			continue;
		}

		$url = get_url_in_content( $post->post_content );

		if ( empty( $url ) ) {
			continue;
		}

		$demos_data[] = array(
			'post_id' => $post_id,
			'url'     => $url,
		);
	}

	return $demos_data;
}

/**
 * Check url validity and return Open Graph meta tags
 */
function snax_ajax_process_link_data() {
	// Read raw embed code, can be url or iframe.
	$url = filter_input( INPUT_POST, 'snax_url', FILTER_SANITIZE_URL );

	if ( empty( $url ) ) {
		snax_ajax_response_error( 'Url not set!' );
		exit;
	}

	// Sanitize author id.
	$author_id = (int) filter_input( INPUT_POST, 'snax_author_id', FILTER_SANITIZE_NUMBER_INT );

	if ( 0 === $author_id ) {
		snax_ajax_response_error( 'Author id not set!' );
		exit;
	}

	if ( $pre = apply_filters( 'pre_snax_ajax_process_link_data', false, $url, $author_id ) ) {
		echo $pre;
		exit;
	}

	// Check url uniqueness.
	if ( snax_link_already_exists( $url ) ) {
		snax_ajax_response_error( __( 'Provided url already exists. Please use a different one.', 'snax' ), array(
			'stop_processing_form' => true,
		) );
		exit;
	}

	$skip_image = 'standard' === filter_input( INPUT_POST, 'snax_skip_image', FILTER_SANITIZE_STRING );

	$metadata = snax_parse_page_metadata( $url );

	if ( is_wp_error( $metadata ) ) {
		$message = $metadata->get_error_message();
		$message .= ' ';
		$message .= esc_html__( 'Meta data could not be processed. Please fill the form manually.', 'snax' );

		snax_ajax_response_error( $message );
		exit;
	}

	$errors = array();
	$processed_fields = 0;

	// Title.
	$title = '';
	$processed_fields++;

	if ( empty( $metadata['title'] ) ) {
		$errors[] = esc_html__( 'Title not found.', 'snax' );
	} else {
		$title = $metadata['title'];
	}

	// Description.
	$description = '';
	$processed_fields++;

	if ( empty( $metadata['description'] ) ) {
		$errors[] = esc_html__( 'Description not found.', 'snax' );
	} else {
		$description = $metadata['description'];
	}

	// Image.
	$image_id    = '';

	if ( ! $skip_image ) {
		$processed_fields++;

		if ( empty( $metadata['image'] ) ) {
			$errors[] = esc_html__( 'Image not found.', 'snax' );
		} else {
			$image_url = $metadata['image'];

			$attachment_id = snax_save_image_from_url( $image_url, $author_id );

			if ( is_wp_error( $attachment_id ) ) {
				$errors[] = $attachment_id->get_error_message();
			} else {
				$image_id = $attachment_id;
			}
		}
	}

	// Response.
	$response_args = array(
		'title'         => html_entity_decode( $title, ENT_QUOTES ),
		'description'   => html_entity_decode( $description, ENT_QUOTES ),
		'image_id'      => $image_id,
	);

	if ( empty( $errors ) ) {
		snax_ajax_response_success( 'Data fetched successfully.', $response_args );
	} else {
		// All fields failed.
		if ( $processed_fields === count( $errors ) ) {
			$error_str = esc_html__( 'This page prevents access or doesn\'t support Open Graph protocol. We couldn\'t read any data from it. Please fill the form manually.', 'snax' );
		} else {
			$error_str = sprintf( __( 'We were not able to read all data.<br />%s<br /> Please fill the missing data manually.', 'snax' ), implode( '<br />', $errors ) );
		}

		snax_ajax_response_error( $error_str, $response_args );
	}

	exit;
}

/**
 * Check whether the url was already submitted
 *
 * @param string $url       Link url.
 *
 * @return bool
 */
function snax_link_already_exists( $url ) {
	$demo_links = snax_link_get_demos();
	$demo_urls  = array();

	foreach( $demo_links as $demo_link ) {
		$demo_urls[] = $demo_link['url'];
	}

	// Skip checking demo urls.
	if ( in_array( $url, $demo_urls ) ) {
		return false;
	}

	// Fetch all Snax Link formats.
	$posts = get_posts( apply_filters( 'snax_link_check_duplicates_query_args', array(
		'post_type'     => 'post',
		'post_status'   => array( 'publish', 'pending' ),
		'tax_query'     => array(
			array(
				'taxonomy'  => 'snax_format',
				'field'     => 'slug',
				'terms'     => array( 'link' ),
			)
		),
		'posts_per_page' => -1
	) ) );

	foreach ( $posts as $post ) {
		$link_url = get_url_in_content( $post->post_content );

		if ( ! empty( $link_url ) && trim( $link_url ) === trim( $url ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Return singular name
 *
 * @param string $default       Default value, if option not set yet.
 *
 * @return string
 */
function snax_link_get_singular_name( $default = '' ) {
	$val = get_option( 'snax_link_singular_name' );

	if ( empty( $val ) ) {
		$val = $default;
	}

	return apply_filters( 'snax_link_singular_name', $val );
}

/**
 * Return "Add new" label
 *
 * @param string $default       Default value, if option not set yet.
 *
 * @return string
 */
function snax_link_get_add_new( $default = '' ) {
	$val = get_option( 'snax_link_add_new' );

	if ( empty( $val ) ) {
		$val = $default;
	}

	return apply_filters( 'snax_link_add_new', $val );
}

/**
 * Return description
 *
 * @param string $default       Default value, if option not set yet.
 *
 * @return string
 */
function snax_link_get_description( $default = '' ) {
	$val = get_option( 'snax_link_description' );

	if ( empty( $val ) ) {
		$val = $default;
	}

	return apply_filters( 'snax_link_description', $val );
}

/**
 * Return overview
 *
 * @param string $default       Default value, if option not set yet.
 *
 * @return string
 */
function snax_link_get_overview( $default = '' ) {
	$val = get_option( 'snax_link_overview' );

	if ( empty( $val ) ) {
		$val = $default;
	}

	return apply_filters( 'snax_link_overview', $val );
}
