<?php
/**
 * Snax Settings Section
 *
 * @package snax
 * @subpackage Settings
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

// Register section and fields.
add_filter( 'snax_admin_get_settings_sections', 'snax_admin_settings_sections_general' );
add_filter( 'snax_admin_get_settings_fields',   'snax_admin_settings_fields_general' );

/**
 * Register section
 *
 * @param array $sections       Sections.
 *
 * @return array
 */
function snax_admin_settings_sections_general( $sections ) {
	$sections['snax_settings_general'] = array(
		'title'    => __( 'Frontend Submission', 'snax' ),
		'callback' => 'snax_admin_settings_general_section_description',
		'page'      => 'snax-general-settings',
	);

	return $sections;
}

/**
 * Register section fields
 *
 * @param array $fields     Fields.
 *
 * @return array
 */
function snax_admin_settings_fields_general( $fields ) {
	$fields['snax_settings_general'] = array(
		'snax_active_formats' => array(
			'title'             => __( 'Active formats', 'snax' ) . '<br /><span style="font-weight: normal;">' . __( '(drag to reorder)', 'snax' ) . '</span>',
			'callback'          => 'snax_admin_setting_callback_active_formats',
			'sanitize_callback' => 'snax_sanitize_text_array',
			'args'              => array(),
		),
		'snax_formats_order' => array(
			'sanitize_callback' => 'sanitize_text_field',
		),
		'snax_misc_header' => array(
			'title'             => '<h2>' . esc_html_x( 'Misc', 'Settings page', 'snax' ) . '</h2>',
			'callback'          => '__return_empty_string',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_show_origin' => array(
			'title'             => __( 'Show the "This post was created with our nice and easy submission form." text', 'snax' ),
			'callback'          => 'snax_admin_setting_show_origin',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_disable_admin_bar' => array(
			'title'             => __( 'Disable admin bar for non-administrators', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_disable_admin_bar',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_disable_dashboard_access' => array(
			'title'             => __( 'Disable Dashboard access for non-administrators', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_disable_dashboard_access',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_disable_wp_login' => array(
			'title'             => __( 'Disable WP login form', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_disable_wp_login',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_enable_login_popup' => array(
			'title'             => __( 'Enable the login popup ', 'snax' ),
			'callback'          => 'snax_admin_setting_enable_login_popup',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_froala_for_items' => array(
			'title'             => __( 'Allow rich editor for post description', 'snax' ),
			'callback'          => 'snax_admin_setting_froala_for_items',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_froala_for_list_items' => array(
			'title'             => __( 'Allow rich editor for item description', 'snax' ),
			'callback'          => 'snax_admin_setting_froala_for_list_items',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_limits_header' => array(
			'title'             => '<h2>' . __( 'Limits', 'snax' ) . '</h2>',
			'callback'          => '__return_empty_string',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_user_posts_per_day' => array(
			'title'             => __( 'User can submit', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_new_posts_limit',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_new_post_items_limit' => array(
			'title'             => __( 'User can submit', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_new_post_items_limit',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_user_submission_limit' => array(
			'title'             => __( 'User can submit', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_user_submission_limit',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_tags_limit' => array(
			'title'             => __( 'Tags', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_tags_limit',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_post_title_max_length' => array(
			'title'             => __( 'Title length', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_post_title_max_length',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_post_description_max_length' => array(
			'title'             => __( 'Description length', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_post_description_max_length',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
	);

	return $fields;
}

function snax_admin_general_settings() {
	?>

	<div class="wrap">

		<h1><?php esc_html_e( 'Snax Settings', 'snax' ) ?></h1>
		<h2 class="nav-tab-wrapper"><?php snax_admin_settings_tabs( __( 'General', 'snax' ) ); ?></h2>

		<form action="options.php" method="post">

			<?php settings_fields( 'snax-general-settings' ); ?>
			<?php do_settings_sections( 'snax-general-settings' ); ?>

			<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'snax' ); ?>" />
			</p>
		</form>
	</div>

	<?php
}

/**
 * Render general section description
 */
function snax_admin_settings_general_section_description() {}

/**
 * Formats
 */
function snax_admin_setting_callback_active_formats() {
	$formats = snax_get_formats();
	$active_formats_ids = snax_get_active_formats_ids();
	?>
	<div id="snax-settings-active-formats">
	<?php
	foreach ( $formats as $format_id => $format_args ) {
		$checkbox_id = 'snax_active_format_' . $format_id;

		$is_checked  = in_array( $format_id, $active_formats_ids, true );
		$is_disabled = snax_is_format_disabled( $format_id );
		?>
		<fieldset>
			<label for="<?php echo esc_attr( $checkbox_id ); ?>">
				<input name="snax_active_formats[]" id="<?php echo esc_attr( $checkbox_id ); ?>" type="checkbox" value="<?php echo esc_attr( $format_id ); ?>" <?php checked( $is_checked, true ); ?><?php disabled( $is_disabled, true ); ?> /> <?php echo esc_html( $format_args['labels']['name'] ); ?>
			</label>
			<small>
				<?php if ( $is_disabled ) {
					snax_get_template_part( 'formats/disabled', $format_id );
				}
				?>
			</small>
		</fieldset>
		<?php
	}
	?>
	</div>
	<input name="snax_formats_order" id="snax_formats_order" type="hidden" value="<?php echo esc_attr( implode( ',', snax_get_formats_order() ) ); ?>">
	<?php
}

/**
 * Items per page
 */
function snax_admin_setting_callback_items_per_page() {
	?>
	<input name="snax_items_per_page" id="snax_items_per_page" type="number" size="5" value="<?php echo esc_attr( snax_get_global_items_per_page() ); ?>" />
	<?php
}

/**
 * Upload allowed
 *
 * @param array $args           Arguments.
 */
function snax_admin_setting_callback_upload_allowed( $args ) {
	$media_type = $args['type'];

	$setting_id = 'snax_' . $media_type . '_upload_allowed';
	$is_checked = call_user_func( 'snax_is_' . $media_type . '_upload_allowed' );

	$rel_settings = array(
		'image' === $media_type ? '#snax_max_upload_size' : '#snax_' . $media_type . '_max_upload_size',
		'[name^=snax_' . $media_type . '_allowed_types]',
	);
	?>
	<input class="snax-hide-rel-settings" data-snax-rel-settings="<?php echo esc_attr( implode( ',', $rel_settings ) ); ?>" name="<?php echo esc_attr( $setting_id ); ?>" id="<?php echo esc_attr( $setting_id ); ?>" type="checkbox" <?php checked( $is_checked ); ?> value="standard" />
	<?php
}

/**
 * Max. image upload size
 *
 * @param array $args          Arguments.
 */
function snax_admin_setting_callback_upload_max_size( $args ) {
	$media_type = $args['type'];

	$bytes_1mb = 1024 * 1024;

	$max_upload_size = call_user_func( 'snax_get_' . $media_type . '_max_upload_size' );
	$limit = wp_max_upload_size();

	$limit_in_mb = floor( $limit / $bytes_1mb );

	$choices = array(
		$bytes_1mb => '1MB',
	);

	if ( $limit_in_mb > 1  ) {
		// Iterate each 2MB.
		for ( $i = 2; $i <= $limit_in_mb; $i *= 2 ) {
			$choices[ $i * $bytes_1mb ] = $i . 'MB';
		}
	}

	// Max limit not included?
	if ( ! isset( $choices[ $limit_in_mb * $bytes_1mb ] ) ) {
		$choices[ $limit_in_mb * $bytes_1mb ] = $limit_in_mb . 'MB';
	}

	$choices = apply_filters( 'snax_max_upload_size_choices', $choices, $media_type );

	$setting_id = 'image' === $media_type ? 'snax_max_upload_size' : 'snax_' . $media_type . '_max_upload_size';
	?>
	<select name="<?php echo esc_attr( $setting_id ); ?>" id="<?php echo esc_attr( $setting_id ); ?>">
		<?php foreach ( $choices as $value => $label ) : ?>
		<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $max_upload_size, $value ); ?>><?php echo esc_html( $label ); ?></option>
		<?php endforeach; ?>
	</select>
	<span><?php printf( esc_html__( 'Maximum size can be set to %dMB, which is your server\'s upload limit (set in php.ini).', 'snax' ), absint( $limit / $bytes_1mb ) ); ?></span>
	<?php
}

/**
 * Allowed upload types
 *
* @param array $args                Arguments.
 */
function snax_admin_setting_callback_upload_allowed_types( $args ) {
	$media_type = $args['type'];

	$setting_id = 'snax_' . $media_type . '_allowed_types';
	$all_types  = call_user_func( 'snax_get_all_' . $media_type . '_allowed_types' );
	$checked    = call_user_func( 'snax_get_' . $media_type . '_allowed_types' );

	foreach ( $all_types as $type ) {
		$field_id = $setting_id . '_' . $type;
		?>
		<label for="<?php echo esc_attr( $field_id ); ?>">
			<input name="<?php echo esc_attr( $setting_id ); ?>[]" id="<?php echo esc_attr( $field_id ); ?>" type="checkbox" value="<?php echo esc_attr( $type ); ?>"<?php checked( in_array( $type, $checked ) ); ?> /> <?php echo esc_html( $type ); ?>
		</label>
		<?php
	}
}

/**
 * How many new posts user can submit, per day.
 */
function snax_admin_setting_callback_new_posts_limit() {
	$limit = snax_get_user_posts_per_day();

	$limit_options = apply_filters( 'snax_new_posts_limit_options', array( 1, 10 ) );
	?>

	<select name="snax_user_posts_per_day" id="snax_user_posts_per_day">
		<?php  foreach( $limit_options as $limit_option ): ?>
		<option value="<?php echo absint( $limit_option ) ?>" <?php selected( $limit_option, $limit ) ?>>
			<?php echo esc_html( sprintf( _nx( '%d post', '%d posts', $limit_option, 'Limit option', 'snax' ), $limit_option ) ); ?>
		</option>
		<?php  endforeach; ?>

		<option value="-1" <?php selected( -1, $limit ) ?>><?php esc_html_e( 'unlimited posts', 'snax' ) ?></option>
	</select>
	<span><?php esc_html_e( 'per day.', 'snax' ); ?></span>
	<?php
}

/**
 * How many new items user can submit to a new post (during creation).
 */
function snax_admin_setting_callback_new_post_items_limit() {
	$limit = snax_get_new_post_items_limit();

	$limit_options = apply_filters( 'snax_new_post_items_limit_options', array( 10, 20 ) );
	?>

	<select name="snax_new_post_items_limit" id="snax_new_post_items_limit">
		<?php  foreach( $limit_options as $limit_option ): ?>
		<option value="<?php echo absint( $limit_option ) ?>" <?php selected( $limit_option, $limit ) ?>>
			<?php echo esc_html( sprintf( _nx( '%d item', '%d items', $limit_option, 'Limit option', 'snax' ), $limit_option ) ); ?>
		</option>
		<?php  endforeach; ?>

		<option value="-1" <?php selected( -1, $limit ) ?>><?php esc_html_e( 'unlimited items', 'snax' ) ?></option>
	</select>
	<span><?php esc_html_e( 'while creating a new list/gallery. Applies also to Story format images.', 'snax' ); ?></span>
	<?php
}

/**
 * Disable admin bar
 */
function snax_admin_setting_callback_disable_admin_bar() {
	?>
	<input name="snax_disable_admin_bar" id="snax_disable_admin_bar" type="checkbox" <?php checked( snax_disable_admin_bar() ); ?> />
	<?php
}

/**
 * Disable dashboard access
 */
function snax_admin_setting_callback_disable_dashboard_access() {
	?>
	<input name="snax_disable_dashboard_access" id="snax_disable_dashborad_access" type="checkbox" <?php checked( snax_disable_dashboard_access() ); ?> />
	<?php
}

/**
 * Disable WP login form
 */
function snax_admin_setting_callback_disable_wp_login() {
	?>
	<input name="snax_disable_wp_login" id="snax_disable_wp_login" type="checkbox" <?php checked( snax_disable_wp_login() ); ?> />
	<?php
}

/**
 * Wheter to show "This post was created with our nice and easy submission form."
 */
function snax_admin_setting_show_origin() {
	$origin = snax_show_origin();
	?>

	<select name="snax_show_origin" id="snax_show_origin">
		<option value="standard" <?php selected( $origin, true ) ?>><?php esc_html_e( 'yes', 'snax' ) ?></option>
		<option value="none" <?php selected( $origin, false ) ?>><?php esc_html_e( 'no', 'snax' ) ?></option>
	</select>
	<?php
}

/**
 * Whether to allow Froala editor (simple) in post description
 */
function snax_admin_setting_froala_for_items() {
	$froala_for_items = snax_froala_for_items();
	?>

	<select name="snax_froala_for_items" id="snax_froala_for_items">
		<option value="standard" <?php selected( $froala_for_items, true ) ?>><?php esc_html_e( 'yes', 'snax' ) ?></option>
		<option value="none" <?php selected( $froala_for_items, false ) ?>><?php esc_html_e( 'no', 'snax' ) ?></option>
			<?php
}

/**
 * Whether to allow Froala editor (simple) in item description
 */
function snax_admin_setting_froala_for_list_items() {
	$froala_for_list_items = snax_froala_for_list_items();
	?>

	<select name="snax_froala_for_list_items" id="snax_froala_for_list_items">
		<option value="standard" <?php selected( $froala_for_list_items, true ) ?>><?php esc_html_e( 'yes', 'snax' ) ?></option>
		<option value="none" <?php selected( $froala_for_list_items, false ) ?>><?php esc_html_e( 'no', 'snax' ) ?></option>
	</select>
	<p class="description">
		<?php esc_html_e( 'For lists and galleries', 'snax' ); ?>
	</p>
	<?php
}

/**
 * Wheter to enable the login popup
 */
function snax_admin_setting_enable_login_popup() {
	$enable_login_popup = snax_enable_login_popup();
	?>

	<select name="snax_enable_login_popup" id="snax_enable_login_popup">
		<option value="standard" <?php selected( $enable_login_popup, true ) ?>><?php esc_html_e( 'yes', 'snax' ) ?></option>
		<option value="none" <?php selected( $enable_login_popup, false ) ?>><?php esc_html_e( 'no', 'snax' ) ?></option>
	</select>
	<?php
	do_action( 'snax_after_admin_setting_enable_login_popup' );
}

/**
 * Demo post
 *
 * @param array $args			Renderer config.
 */
function snax_admin_setting_callback_demo_post( $args ) {
	$format = $args['format'];
	$selected_post_id = snax_get_demo_post_id( $format );
	$select_name = sprintf( 'snax_demo_%s_post_id', $format );

	$posts = get_posts( array(
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'ASC',
		'post_status'      => 'any',
		'tax_query'		 => array(
			array(
				'taxonomy' 	=> snax_get_snax_format_taxonomy_slug(),
				'field' 	=> 'slug',
				'terms' 	=> 'meme' === $format ? 'image' : $format,
			),
		),
	) );
	?>
	<select name="<?php echo esc_attr( $select_name ) ?>" id="<?php echo esc_attr( $select_name ); ?>">
		<option value=""><?php esc_html_e( '- None -', 'snax' ) ?></option>

		<?php foreach( $posts as $post ) : ?>
			<option class="level-0" value="<?php echo intval( $post->ID ) ?>" <?php selected( $post->ID, $selected_post_id ); ?>><?php echo esc_html( get_the_title( $post ) ); ?></option>
		<?php endforeach; ?>
	</select>
	<?php

	if ( ! empty( $selected_post_id ) ) :
		?>
		<a href="<?php echo esc_url( get_permalink( $selected_post_id ) ); ?>" class="button-secondary" target="_blank"><?php esc_html_e( 'View', 'snax' ); ?></a>
		<?php
	endif;

	if ( 'meme' === $format ) {
		esc_html_e( 'Choose an Image post', 'snax' );
	}
}

/**
 * Demo posts
 *
 * @param array $args			Renderer config.
 */
function snax_admin_setting_callback_demo_posts( $args ) {
	$args = wp_parse_args( $args, array(
		'post_type' => 'post',
		'format'    => '',
	) );

	if ( empty( $args['format'] ) ) {
		return;
	}

	$format             = $args['format'];
	$selected_post_ids  = snax_get_demo_post_ids( $format );
	$select_name        = sprintf( 'snax_demo_%s_post_ids', $format );

	$posts = get_posts( array(
		'post_type'        => $args['post_type'],
		'posts_per_page'   => -1,
		'orderby'          => 'title',
		'order'            => 'ASC',
		'post_status'      => 'any',
		'tax_query'		 => array(
			array(
				'taxonomy' 	=> snax_get_snax_format_taxonomy_slug(),
				'field' 	=> 'slug',
				'terms' 	=> 'meme' === $format ? 'image' : $format,
			),
		),
	) );
	?>
	<select size="10" name="<?php echo esc_attr( $select_name ) ?>[]" id="<?php echo esc_attr( $select_name ); ?>" multiple="multiple">
		<option value="" <?php selected( in_array( '', $selected_post_ids, true ) ); ?>><?php esc_html_e( '- None -', 'snax' ) ?></option>

		<?php foreach( $posts as $post ) : ?>
			<option class="level-0" value="<?php echo intval( $post->ID ) ?>" <?php selected( in_array( $post->ID, $selected_post_ids ) ); ?>><?php echo esc_html( get_the_title( $post ) ); ?></option>
		<?php endforeach; ?>
	</select>
	<?php
}

	/**
 * Tags limit
 */
function snax_admin_setting_callback_tags_limit() {
	?>
	<input name="snax_tags_limit" id="snax_tags_limit" type="number" size="5" value="<?php echo esc_attr( snax_get_tags_limit() ); ?>" />
	<p class="description"><?php esc_html_e( 'Maximum number of tags user can assign to a post during new submission.', 'snax' ); ?></p>
	<?php
}

/**
 * Post title max. length
 */
function snax_admin_setting_callback_post_title_max_length() {
	?>
	<input name="snax_post_title_max_length" id="snax_post_title_max_length" type="number" size="5" value="<?php echo esc_attr( snax_get_post_title_max_length() ); ?>" />
	<?php
}

/**
 * Post description max. length
 */
function snax_admin_setting_callback_post_description_max_length() {
	?>
	<input name="snax_post_description_max_length" id="snax_post_description_max_length" type="number" size="5" value="<?php echo esc_attr( snax_get_post_description_max_length() ); ?>" />
	<?php
}

/**
 * User can submit (limit)
 */
function snax_admin_setting_callback_user_submission_limit() {
	$limit = snax_get_user_submission_limit();

	$limit_options = apply_filters( 'snax_user_submission_limit_options', array( 1 ) );
	?>

	<select name="snax_user_submission_limit" id="snax_user_submission_limit">
		<?php  foreach( $limit_options as $limit_option ): ?>
		<option value="<?php echo absint( $limit_option ) ?>" <?php selected( $limit_option, $limit ) ?>>
			<?php echo esc_html( sprintf( _nx( '%d item', '%d items', $limit_option, 'Limit option', 'snax' ), $limit_option ) ); ?>
		</option>
		<?php  endforeach; ?>

		<option value="-1" <?php selected( -1, $limit ) ?>><?php esc_html_e( 'unlimited items', 'snax' ) ?></option>
	</select>
	<span><?php esc_html_e( 'to an existing list.', 'snax' ); ?></span>
	<?php
}