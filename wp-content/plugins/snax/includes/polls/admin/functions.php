<?php
/**
 * Admin Functions
 *
 * @package snax
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

/**
 * Load stylesheets.
 */
function snax_poll_admin_enqueue_styles() {}

/**
 * Load javascripts.
 */
function snax_poll_admin_enqueue_scripts() {}

/**
 * Add Settings to the admin menu.
 */
function snax_register_poll_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    global $submenu;

    $submenu['edit.php?post_type=snax_poll'][] = array(
        __( 'Settings', 'snax' ),
        'manage_options',
        'admin.php?page=snax-polls-settings'
    );
}

/**
 * Register a page for new quiz type selection
 */
function snax_register_new_poll_page() {
	global $submenu;

	$parent_slug = 'edit.php?post_type=' . snax_get_poll_post_type();

	if ( ! isset( $submenu[ $parent_slug ] ) ) {
		return;
	}

	$menu_title = $submenu[ $parent_slug ][10][0];
	$capability = $submenu[ $parent_slug ][10][1];

	// Hide default "Add New" link.
	unset( $submenu[ $parent_slug ][10] );

	// Add a new "Add New" page.
	add_submenu_page(
		$parent_slug,
		$menu_title,
		$menu_title,
		$capability,
		'new-poll',
		'snax_render_new_poll_page'
	);
}

/**
 * Render a page for new quiz type selection
 */
function snax_render_new_poll_page() {
	snax_get_template_part( 'polls/new-poll' );
}

/**
 * Override default "Add New" url for a poll post type
 *
 * @param string $url     The complete admin area URL including scheme and path.
 * @param string $path    Path relative to the admin area URL. Blank string if no path is specified.
 *
 * @return string
 */
function snax_redirect_to_new_poll_page( $url, $path ) {
	if ( 'post-new.php?post_type=' . snax_get_poll_post_type() === $path ) {
		$url = snax_get_new_poll_page_url();
	}

	return $url;
}

/**
 * Return url to the new poll page
 *
 * @return string
 */
function snax_get_new_poll_page_url() {
	return 'edit.php?post_type=' . snax_get_poll_post_type() . '&page=new-poll';
}

/**
 * Return url to the new Classic poll page
 *
 * @return string
 */
function snax_get_new_classic_poll_page_url() {
	return admin_url() . 'post-new.php?post_type=' . snax_get_poll_post_type() . '&type=' . snax_get_classic_poll_type();
}

/**
 * Return url to the new Versus poll page
 *
 * @return string
 */
function snax_get_new_versus_poll_page_url() {
	return admin_url() . 'post-new.php?post_type=' . snax_get_poll_post_type() . '&type=' . snax_get_versus_poll_type();
}

/**
 * Return url to the new Binary poll page
 *
 * @return string
 */
function snax_get_new_binary_poll_page_url() {
	return admin_url() . 'post-new.php?post_type=' . snax_get_poll_post_type() . '&type=' . snax_get_binary_poll_type();
}

/**
 * Render Poll Form
 *
 * @param string $post		Post object.
 */
function snax_render_poll_form( $post ) {
	$poll_post_type = snax_get_poll_post_type();

	if ( get_post_type( $post ) !== $poll_post_type ) {
		return;
	}

	// Get type from url.
	$poll_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_STRING );


	// If not set, read from meta.
	if ( ! $poll_type ) {
		$poll_type = snax_get_poll_type( $post );
	}

	// Fallback to default type.
	if ( ! snax_is_valid_poll_type( $poll_type ) ) {
		$poll_type = snax_get_classic_poll_type();
	}


	// Load CSS.
	wp_enqueue_style( 'snax-poll', snax_get_includes_url() . 'polls/admin/css/poll.css', array(), '1.0' );

	// Load JS.
	wp_enqueue_media();
	wp_enqueue_script( 'snax-poll-common', 		snax_get_includes_url() . 'polls/admin/js/common.js', array( 'jquery' ), snax_get_version() );
	wp_enqueue_script( 'snax-' . $poll_type . '-poll', 	snax_get_includes_url() . 'polls/admin/js/' . $poll_type . '-poll.js', array( 'snax-poll-common', 'jquery', 'jquery-ui-sortable' ), snax_get_version() );

	$poll_config = array(
		'i18n' => array(
			'yes'	=> __( "Yes", 'snax' ),
			'no'	=> __( "No", 'snax' ),
		),
	);
	wp_localize_script( 'snax-' . $poll_type . '-poll', 'snax_' . $poll_type . '_poll_config', wp_json_encode( $poll_config ) );

	// Load template.
	snax_get_template_part( 'polls/' . $poll_type . '/form/poll-tpl' );
}

/**
 * Save poll.
 *
 * @param int 	  $post_id The post ID.
 * @param WP_Post $post The post object.
 * @param bool 	  $update Whether this is an existing post being updated or not.
 */
function snax_save_poll_form( $post_id, $post, $update ) {
	if ( ! snax_is_poll( $post ) ) {
		return;
	}

	$poll_type = filter_input( INPUT_POST, 'snax_poll', FILTER_SANITIZE_STRING );

	// Is valid type?
	if ( ! snax_is_valid_poll_type( $poll_type ) ) {
		return;
	}

	// Save poll type.
	update_post_meta( $post_id, '_snax_poll_type', $poll_type );

	// Save poll format.
	snax_set_post_format( $post_id, $poll_type . '_poll' );

	// Save settings.
	snax_save_poll_settings( $post_id, $post, $update );
}

/**
 * Save poll settings.
 *
 * @param int 	  $post_id The post ID.
 * @param WP_Post $post The post object.
 * @param bool 	  $update Whether this is an existing post being updated or not.
 */
function snax_save_poll_settings( $post_id, $post, $update ) {
	$answers_set 	                = filter_input( INPUT_POST, 'snax_answers_set', FILTER_SANITIZE_STRING );

	$allow_guests_to_play 	        = filter_input( INPUT_POST, 'snax_allow_guests_to_play', FILTER_SANITIZE_STRING );
	$vote_limit 	                = filter_input( INPUT_POST, 'snax_vote_limit', FILTER_SANITIZE_NUMBER_INT );
	$reveal_correct_wrong_answers 	= filter_input( INPUT_POST, 'snax_reveal_correct_wrong_answers', FILTER_SANITIZE_STRING );
	$questions_per_page 			= filter_input( INPUT_POST, 'snax_questions_per_page', FILTER_SANITIZE_STRING );
	$shuffle_questions 				= filter_input( INPUT_POST, 'snax_shuffle_questions', FILTER_SANITIZE_STRING );
	$questions_per_poll 			= filter_input( INPUT_POST, 'snax_questions_per_poll', FILTER_SANITIZE_STRING );
	$shuffle_answers 				= filter_input( INPUT_POST, 'snax_shuffle_answers', FILTER_SANITIZE_STRING );

	// Save settings.
	update_post_meta( $post_id, '_snax_answers_set', $answers_set );

	update_post_meta( $post_id, '_snax_allow_guests_to_play', $allow_guests_to_play );
	update_post_meta( $post_id, '_snax_vote_limit', $vote_limit );
	update_post_meta( $post_id, '_snax_reveal_correct_wrong_answers', $reveal_correct_wrong_answers );
	update_post_meta( $post_id, '_snax_questions_per_page', $questions_per_page );
	update_post_meta( $post_id, '_snax_shuffle_questions', $shuffle_questions );
	update_post_meta( $post_id, '_snax_questions_per_poll', $questions_per_poll );
	update_post_meta( $post_id, '_snax_shuffle_answers', $shuffle_answers );
}

/**
 * Create or update a question.
 *
 * @param array  $postarr	                An array of elements that make up a post to update or insert.
 * @param int    $media_id	                Optional. Media id assigned to the question.
 * @param string $answers_tpl               Optional. Answers template.
 * @param bool   $title_hide                Optional. Whether to hide title or not.
 * @param bool   $answers_labels_hide       Optional. Whether to hide answers labels or not.
 *
 * @return int|WP_Error		The post ID on success. WP_Error on failure.
 */
function snax_poll_insert_question( $postarr, $media_id = 0, $answers_tpl = 'text', $title_hide = false, $answers_labels_hide = false ) {
	$defaults = array(
		'post_type' 	=> snax_get_poll_question_post_type(),
		'post_status' 	=> 'publish',
	);

	$postarr = wp_parse_args( $postarr, $defaults );

	$post_id = wp_insert_post( $postarr, true );

	// Title hide.
	update_post_meta( $post_id, '_snax_title_hide', $title_hide );

	// Media.
	if ( $media_id ) {
		set_post_thumbnail( $post_id, $media_id );
	} elseif ( has_post_thumbnail( $post_id ) ) {
		delete_post_thumbnail( $post_id );
	}

	// Answers template.
	update_post_meta( $post_id, '_snax_answers_tpl', $answers_tpl );

	// Answers labels hide.
	update_post_meta( $post_id, '_snax_answers_labels_hide', $answers_labels_hide );

	return $post_id;
}

/**
 * Delete a question.
 *
 * @param array $postarr		An array of elements that make up a post to update or insert.
 *
 * @return WP_Post|WP_Error		The deleted post object on success. WP_Error on failure.
 */
function snax_poll_delete_question( $postarr ) {
	$defaults = array(
		'ID' 	=> '',
	);

	$postarr = wp_parse_args( $postarr, $defaults );

	$deleted = wp_delete_post( $postarr['ID'], true );

	if ( false === $deleted ) {
		return new WP_Error( 'deletion_failed' );
	}

	return $deleted;
}

/**
 * Create or update an answer.
 *
 * @param array $postarr	An array of elements that make up a post to update or insert.
 * @param int   $media_id	Optional. Media id assigned to the question.
 *
 * @return int|WP_Error		The post ID on success. WP_Error on failure.
 */
function snax_poll_insert_answer( $postarr, $media_id = 0 ) {
	$defaults = array(
		'post_type' 	=> snax_get_poll_answer_post_type(),
		'post_status' 	=> 'publish',
	);

	$postarr = wp_parse_args( $postarr, $defaults );

	// Insert/update WP post.
	$post_id = wp_insert_post( $postarr, true );

	// Media.
	if ( $media_id ) {
		set_post_thumbnail( $post_id, $media_id );
	} elseif ( has_post_thumbnail( $post_id ) ) {
		delete_post_thumbnail( $post_id );
	}

	return $post_id;
}

/**
 * Delete an answer.
 *
 * @param array $postarr		An array of elements that make up a post to update or insert.
 *
 * @return WP_Post|WP_Error		The deleted post object on success. WP_Error on failure.
 */
function snax_poll_delete_answer( $postarr ) {
	$defaults = array(
		'ID' 	=> '',
	);

	$postarr = wp_parse_args( $postarr, $defaults );

	$deleted = wp_delete_post( $postarr['ID'], true );

	if ( false === $deleted ) {
		return new WP_Error( 'deletion_failed' );
	}

	return $deleted;
}

/**
 * Create or update a result.
 *
 * @param array $postarr	An array of elements that make up a post to update or insert.
 * @param int   $range_low	Optional. Low range value.
 * @param int   $range_high	Optional. High range value.
 * @param int   $media_id	Optional. Media id assigned to the result.
 *
 * @return int|WP_Error		The post ID on success. WP_Error on failure.
 */
function snax_poll_insert_result( $postarr, $range_low = 0, $range_high = 0, $media_id = 0 ) {
	$defaults = array(
		'post_type' 	=> snax_get_poll_result_post_type(),
		'post_status' 	=> 'publish',
	);

	$postarr = wp_parse_args( $postarr, $defaults );

	$post_id = wp_insert_post( $postarr, true );

	// Range.
	update_post_meta( $post_id, '_snax_range_low',  $range_low );
	update_post_meta( $post_id, '_snax_range_high', $range_high );

	// Media.
	if ( $media_id ) {
		set_post_thumbnail( $post_id, $media_id );
	} elseif ( has_post_thumbnail( $post_id ) ) {
		delete_post_thumbnail( $post_id );
	}

	return $post_id;
}

/**
 * Delete a result.
 *
 * @param array $postarr		An array of elements that make up a post to update or insert.
 *
 * @return WP_Post|WP_Error		The deleted post object on success. WP_Error on failure.
 */
function snax_poll_delete_result( $postarr ) {
	$defaults = array(
		'ID' 	=> '',
	);

	$postarr = wp_parse_args( $postarr, $defaults );

	$deleted = wp_delete_post( $postarr['ID'], true );

	if ( false === $deleted ) {
		return new WP_Error( 'deletion_failed' );
	}

	return $deleted;
}

/**
 * Register custom columns to the columns shown on the manage posts screen
 *
 * @param array $columns            An array of column name => label.
 *
 * @return array
 */
function snax_register_polls_custom_columns( $columns ) {
	global $post_type, $pagenow;

	if ( 'edit.php' !== $pagenow ) {
		return $columns;
	}

	if ( $post_type === snax_get_poll_post_type() ) {
		$columns['snax_poll_type'] = __( 'Type', 'snax' );
	}

	return $columns;
}

/**
 * Render content of registered custom columns.
 *
 * @param string $column           Column name.
 * @param int    $post_id          Post ID.
 */
function snax_render_polls_custom_columns( $column, $post_id ) {
	if ( 'snax_poll_type' === $column ) {
		echo snax_get_poll_type_label( $post_id );
	}
}

function snax_allow_to_duplicate_polls( $post_types ) {
    $post_types[] = snax_get_poll_post_type();

    return $post_types;
}

/**
 * Duplicates a poll
 *
 * @param int $poll_id          Poll id.
 *
 * @return int|WP_Error
 */
function snax_poll_duplicate( $poll_id ) {
    $poll = get_post( $poll_id );

    // Duplicate poll.
    $new_poll_id = snax_post_duplicate( $poll->ID );

    if ( is_wp_error( $new_poll_id ) ) {
        echo $new_poll_id->get_error_message();
        exit;
    }

    // Duplicate questions.
    $questions = get_posts( array(
        'post_parent'  => $poll->ID,
        'post_type'    => snax_get_poll_question_post_type(),
        'numberposts'  => -1,
        'post_status'  => 'any',
    ) );

    if ( is_array( $questions ) &&  ! empty( $questions ) ) {
        foreach ( $questions as $question ) {
            $new_question_id = snax_post_duplicate(
                $question->ID,
                array(
                    'post_parent' => $new_poll_id,
                    'post_status' => 'publish',     // This post type is not public. Can be always set as published.
                )
            );

            if ( is_wp_error( $new_question_id ) ) {
                echo $new_question_id->get_error_message();
                exit;
            }

            // Duplicate question answers.
            $answers = get_posts( array(
                'post_parent'  => $question->ID,
                'post_type'    => snax_get_poll_answer_post_type(),
                'numberposts'  => -1,
                'post_status'  => 'any',
            ) );

            if ( is_array( $answers ) &&  ! empty( $answers ) ) {
                foreach ( $answers as $answer ) {
                    $new_answer_id = snax_post_duplicate(
                        $answer->ID,
                        array(
                            'post_parent' => $new_question_id,
                            'post_status' => 'publish',     // This post type is not public. Can be always set as published.
                        )
                    );

                    if ( is_wp_error( $new_answer_id ) ) {
                        echo $new_answer_id->get_error_message();
                        exit;
                    }
                }
            }
        }
    }

    return $new_poll_id;
}
