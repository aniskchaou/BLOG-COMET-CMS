<?php
/**
 * Snax Format Taxonomy
 *
 * @package snax
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_action( 'init',                         'snax_add_snax_format_taxonomy', 0 );
add_action( 'init',                         'snax_insert_snax_format_terms' );
add_action( 'wp_loaded',                    'snax_migrate_formats_meta_to_taxonomy' );
add_action( 'wp_loaded',                    'snax_migrate_formats_quiz_poll_to_taxonomy' );

// Use custom labels for terms.
add_filter( 'single_term_title',            'snax_format_custom_term_title' );
add_filter( 'get_the_archive_description',  'snax_format_custom_term_description' );

/**
 * Create Snax Format taxonomy.
 */
function snax_add_snax_format_taxonomy() {
	$labels = array(
		'name'              => _x( 'Snax Formats', 'taxonomy general name', 'snax' ),
		'singular_name'     => _x( 'Snax Format', 'taxonomy singular name', 'snax' ),
		'search_items'      => __( 'Search Snax Formats', 'snax' ),
		'all_items'         => __( 'All Snax Formats', 'snax' ),
		'parent_item'       => __( 'Parent Snax Format', 'snax' ),
		'parent_item_colon' => __( 'Parent Snax Format:', 'snax' ),
		'edit_item'         => __( 'Edit Snax Format', 'snax' ),
		'update_item'       => __( 'Update Snax Format', 'snax' ),
		'add_new_item'      => __( 'Add New Snax Format', 'snax' ),
		'new_item_name'     => __( 'New Snax Format Name', 'snax' ),
		'menu_name'         => __( 'Snax Format', 'snax' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => false,
		'show_admin_column' => false,
		'query_var'         => true,
		'rewrite'           => array(
		    'slug' => snax_get_url_var( 'format' )
        ),
		'capabilities' => array(
			'manage_terms' => 'edit_posts',
			'edit_terms' => 'edit_posts',
			'delete_terms' => 'edit_posts',
			'assign_terms' => 'edit_posts',
		),
		'public' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => false,
	);
	if ( defined( 'SNAX_DEBUG_TAXONOMY' ) && SNAX_DEBUG_TAXONOMY ) {
		$args['show_ui'] = true;
	}

	$supported_post_types = apply_filters( 'snax_format_taxonomy_post_types', array( 'post', snax_get_quiz_post_type(), snax_get_poll_post_type() ) );

	register_taxonomy( snax_get_snax_format_taxonomy_slug(), $supported_post_types, $args );
}

/**
 * Get snax format taxonomy slug.
 */
function snax_get_snax_format_taxonomy_slug() {
	return apply_filters( 'snax_get_snax_format_taxonomy_slug', 'snax_format' );
}

/**
 * Insert format terms.
 */
function snax_insert_snax_format_terms() {
	$formats = snax_get_formats();

	// Backwards compatibility with the preexisting code.
	unset( $formats['classic_list'] );
	unset( $formats['ranked_list'] );
	$formats['list']['labels']['name'] = __( 'List', 'snax' );

	foreach ( $formats as $slug => $format ) {
		wp_insert_term(
			$format['labels']['name'],
			snax_get_snax_format_taxonomy_slug(),
			array(
				'description'	=> $format['description'],
				'slug' 		=> $slug,
			)
		);
	}
}

/**
 * Update term title based on custom label
 *
 * @param string $title         Term title.
 *
 * @return string
 */
function snax_format_custom_term_title( $title ) {
    if ( is_tax( snax_get_snax_format_taxonomy_slug() ) ) {
        $query_obj = get_queried_object();

        $term_slug = $query_obj->slug;

        $callback_func = sprintf( 'snax_%s_get_singular_name', $term_slug );

        if ( is_callable( $callback_func ) ) {
            $custom_title = call_user_func( $callback_func );

            if ( ! empty( $custom_title ) ) {
                $title = $custom_title;
            }
        }
    }

    return $title;
}

/**
 * Update term description based on custom label
 *
 * @param string $description       Term description.
 *
 * @return string
 */
function snax_format_custom_term_description( $description ) {
    if ( is_tax( snax_get_snax_format_taxonomy_slug() ) ) {
        $query_obj = get_queried_object();

        $term_slug = $query_obj->slug;

        $callback_func = sprintf( 'snax_%s_get_description', $term_slug );

        if ( is_callable( $callback_func ) ) {
            $custom_description = call_user_func( $callback_func );

            if ( ! empty( $custom_description ) ) {
                $description = $custom_description;
            }
        }
    }

    return $description;
}

/**
 * Migrate formats from meta to custom taxonomy.
 */
function snax_migrate_formats_meta_to_taxonomy() {

	$option = get_option( 'snax_formats_taxonomy_migration' );
	if ( $option ) {
		return;
	}

	$migration_post_types = array(
		'post',
		snax_get_poll_post_type(),
		snax_get_quiz_post_type(),
	);

	foreach ( $migration_post_types as $post_type ) {
		$posts = get_posts( array(
			'post_type' => $post_type,
			'fields'          => 'ids',
			'posts_per_page'  => -1,
		) );
		foreach ( $posts as $post_id ) {
			$format = get_post_meta( $post_id, '_snax_format', true );
			if ( ! empty( $format ) ) {
				snax_set_post_format( $post_id, $format );
			}
		}
	}

	update_option( 'snax_formats_taxonomy_migration', true );
}

/**
 * Migrate formats from meta to custom taxonomy.
 */
function snax_migrate_formats_quiz_poll_to_taxonomy() {

	$option = get_option( 'snax_formats_quiz_poll_taxonomy_migration' );
	if ( $option ) {
		return;
	}

	$migration_post_types = array(
		snax_get_poll_post_type(),
		snax_get_quiz_post_type(),
	);

	foreach ( $migration_post_types as $post_type ) {
		$posts = get_posts( array(
			'post_type' => $post_type,
			'fields'          => 'ids',
			'posts_per_page'  => -1,
		) );
		foreach ( $posts as $post_id ) {
			$format = get_post_meta( $post_id, '_snax_quiz_type', true );
			if ( ! empty( $format ) ) {
				snax_set_post_format( $post_id, $format . '_quiz' );
				snax_set_post_format( $post_id, $format . '_quiz' );
			}
			$format = get_post_meta( $post_id, '_snax_poll_type', true );
			if ( ! empty( $format ) ) {
				snax_set_post_format( $post_id, $format . '_poll' );
			}
		}
	}

	update_option( 'snax_formats_quiz_poll_taxonomy_migration', true );
}
