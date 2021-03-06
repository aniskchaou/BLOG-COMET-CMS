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
add_filter( 'snax_admin_get_settings_sections', 'snax_admin_settings_sections_embeds' );
add_filter( 'snax_admin_get_settings_fields',   'snax_admin_settings_fields_embeds' );

/**
 * Register section
 *
 * @param array $sections       Sections.
 *
 * @return array
 */
function snax_admin_settings_sections_embeds( $sections ) {
	$sections['snax_settings_embeds'] = array(
		'title'    => __( 'Embeds', 'snax' ),
		'callback' => 'snax_admin_settings_embeds_section_description',
		'page'      => 'snax-embeds-settings',
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
function snax_admin_settings_fields_embeds( $fields ) {
	$fields['snax_settings_embeds'] = array(

		/* Frontend Form */

		'snax_embed_frontend_form_header' => array(
			'title'             => '<h2>' . __( 'Frontend Form', 'snax' ) . '</h2>',
			'callback'          => '__return_empty_string',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_embed_featured_media_field' => array(
			'title'             => __( 'Featured Image', 'snax' ),
			'callback'          => 'snax_admin_setting_embed_featured_media_field',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embed_category_field' => array(
			'title'             => __( 'Category', 'snax' ),
			'callback'          => 'snax_admin_setting_embed_category_field',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embed_category_multi' => array(
			'title'             => __( 'Multiple categories selection?', 'snax' ),
			'callback'          => 'snax_admin_setting_embed_category_multi',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embed_category_whitelist' => array(
			'title'             => __( 'Category whitelist', 'snax' ),
			'callback'          => 'snax_admin_setting_embed_category_whitelist',
			'sanitize_callback' => 'snax_sanitize_category_whitelist',
			'args'              => array(),
		),
		'snax_embed_category_auto_assign' => array(
			'title'             => __( 'Auto assign to categories', 'snax' ),
			'callback'          => 'snax_admin_setting_embed_category_auto_assign',
			'sanitize_callback' => 'snax_sanitize_category_whitelist',
			'args'              => array(),
		),
		'snax_embed_allow_snax_authors_to_add_referrals' => array(
			'title'             => __( 'Referral link', 'snax' ),
			'callback'          => 'snax_admin_setting_embed_allow_snax_authors_to_add_referrals',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),

		/* Single Post */

		'snax_embed_single_post_header' => array(
			'title'             => '<h2>' . __( 'Single Post', 'snax' ) . '</h2>',
			'callback'          => '__return_empty_string',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),

		'snax_embed_show_featured_media' => array(
			'title'             => __( 'Show Featured Media', 'snax' ),
			'callback'          => 'snax_admin_setting_embed_show_featured_media',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),

		/* Embedly */

		'snax_embedly_header' => array(
			'title'             => '<h2>' . __( 'Embedly', 'snax' ) . '</h2>',
			'description'             => '<h2>' . __( 'ok', 'snax' ) . '</h2>',
			'callback'          => '__return_empty_string',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_embedly_enable' => array(
			'title'             => __( 'Enable Embedly support?', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_embedly_enable',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embedly_dark_skin' => array(
			'title'             => __( 'Dark skin', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_embedly_dark_skin',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embedly_buttons' => array(
			'title'             => __( 'Share buttons', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_embedly_buttons',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embedly_width' => array(
			'title'             => __( 'Width', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_embedly_width',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_embedly_alignment' => array(
			'title'             => __( 'Alignment', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_embedly_alignment',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embedly_api_key' => array(
			'title'             => __( 'Embedly cards API key', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_embedly_api_key',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),

		/* Texts */

		'snax_embed_texts_header' => array(
			'title'             => '<h2>' . _x( 'Texts', 'Setting label', 'snax' ) . '</h2>',
			'callback'          => '__return_empty_string',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embed_singular_name' => array(
			'title'             => _x( 'Singular name', 'Setting label', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_embed_singular_name',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embed_add_new' => array(
			'title'             => _x( 'Add new', 'Setting label', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_embed_add_new',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embed_description' => array(
			'title'             => _x( 'Description', 'Setting label', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_embed_description',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_embed_overview' => array(
			'title'             => _x( 'Overview', 'Setting label', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_embed_overview',
			'sanitize_callback' => 'wp_kses_post',
			'args'              => array(),
		),
	);

	if ( defined( 'BTP_DEV' ) && BTP_DEV ) {
		$fields['snax_settings_embeds']['snax_demo_embed_post_id'] = array(
			'title'             => __( 'Example Embed', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_demo_post',
			'sanitize_callback' => 'intval',
			'args'              => array( 'format' => 'embed' ),
		);
	}

	return $fields;
}

function snax_admin_embeds_settings() {
	?>

	<div class="wrap">

		<h1><?php esc_html_e( 'Snax Settings', 'snax' ) ?></h1>
		<h2 class="nav-tab-wrapper"><?php snax_admin_settings_tabs( __( 'Formats', 'snax' ) ); ?></h2>
		<?php snax_admin_settings_subtabs( 'formats', __( 'Embeds', 'snax' ) ); ?>

		<form action="options.php" method="post">

			<?php settings_fields( 'snax-embeds-settings' ); ?>
			<?php do_settings_sections( 'snax-embeds-settings' ); ?>

			<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'snax' ); ?>" />
			</p>
		</form>
	</div>

	<?php
}

/**
 * Render section description
 */
function snax_admin_settings_embeds_section_description() {}

/**
 * Featured media field
 */
function snax_admin_setting_embed_featured_media_field() {
	$field = snax_embed_featured_media_field();
	?>

	<select name="snax_embed_featured_media_field" id="snax_embed_featured_media_field">
		<option value="disabled" <?php selected( $field, 'disabled' ) ?>><?php esc_html_e( 'disabled', 'snax' ) ?></option>
		<option value="required" <?php selected( $field, 'required' ) ?>><?php esc_html_e( 'required', 'snax' ) ?></option>
		<option value="optional" <?php selected( $field, 'optional' ) ?>><?php esc_html_e( 'optional', 'snax' ) ?></option>
	</select>
	<?php
}

/**
 * Featured media on single post
 */
function snax_admin_setting_embed_show_featured_media() {
	$checked = snax_embed_show_featured_media();
	?>
	<input name="snax_embed_show_featured_media" id="snax_embed_show_featured_media" value="standard" type="checkbox" <?php checked( $checked ); ?> />
	<?php
}

/**
 * Category field
 */
function snax_admin_setting_embed_category_field() {
	$field = snax_embed_category_field();
	?>

	<select name="snax_embed_category_field" id="snax_embed_category_field">
		<option value="disabled" <?php selected( $field, 'disabled' ) ?>><?php esc_html_e( 'disabled', 'snax' ) ?></option>
		<option value="required" <?php selected( $field, 'required' ) ?>><?php esc_html_e( 'required', 'snax' ) ?></option>
		<option value="optional" <?php selected( $field, 'optional' ) ?>><?php esc_html_e( 'optional', 'snax' ) ?></option>
	</select>
	<?php
}

/**
 * Multiple categories selection.
 */
function snax_admin_setting_embed_category_multi() {
	$checked = snax_embed_multiple_categories_selection();
	?>
	<input name="snax_embed_category_multi" id="snax_embed_category_multi" value="standard" type="checkbox" <?php checked( $checked ); ?> />
	<?php
}

/**
 * Category white-list
 */
function snax_admin_setting_embed_category_whitelist() {
	$whitelist      = snax_embed_get_category_whitelist();
	$all_categories = get_categories( 'hide_empty=0' );
	?>
	<select size="10" name="snax_embed_category_whitelist[]" id="snax_embed_category_whitelist" multiple="multiple">
		<option value="" <?php selected( in_array( '', $whitelist, true ) ); ?>><?php esc_html_e( '- Allow all -', 'snax' ) ?></option>
		<?php foreach ( $all_categories as $category_obj ) : ?>
			<?php
			// Exclude the Uncategorized option.
			if ( 'uncategorized' === $category_obj->slug ) {
				continue;
			}
			?>

			<option value="<?php echo esc_attr( $category_obj->slug ); ?>" <?php selected( in_array( $category_obj->slug, $whitelist, true ) ); ?>><?php echo esc_html( $category_obj->name ) ?></option>
		<?php endforeach; ?>
	</select>
	<p class="description"><?php esc_html_e( 'Categories allowed for user while creating a new post.', 'snax' ); ?></p>
	<?php
}

/**
 * Auto assign to category.
 */
function snax_admin_setting_embed_category_auto_assign() {
	$auto_assign_list = snax_embed_get_category_auto_assign();
	$all_categories = get_categories( 'hide_empty=0' );
	?>
	<select size="10" name="snax_embed_category_auto_assign[]" id="snax_embed_category_auto_assign" multiple="multiple">
		<option value="" <?php selected( in_array( '', $auto_assign_list, true ) ); ?>><?php esc_html_e( '- Not assign -', 'snax' ) ?></option>
		<?php foreach ( $all_categories as $category_obj ) : ?>
			<?php
			// Exclude the Uncategorized option.
			if ( 'uncategorized' === $category_obj->slug ) {
				continue;
			}
			?>

			<option value="<?php echo esc_attr( $category_obj->slug ); ?>" <?php selected( in_array( $category_obj->slug, $auto_assign_list, true ) ); ?>><?php echo esc_html( $category_obj->name ) ?></option>
		<?php endforeach; ?>
	</select>
	<?php
}

/**
 * Whether to allow the Snax Author to add referral links to posts and items
 */
function snax_admin_setting_embed_allow_snax_authors_to_add_referrals() {
	$allow = snax_embed_allow_snax_authors_to_add_referrals();
	?>

	<select name="snax_embed_allow_snax_authors_to_add_referrals" id="snax_embed_allow_snax_authors_to_add_referrals">
		<option value="standard" <?php selected( $allow, true ) ?>><?php esc_html_e( 'show', 'snax' ) ?></option>
		<option value="none" <?php selected( $allow, false ) ?>><?php esc_html_e( 'hide', 'snax' ) ?></option>
	</select>
	<p class="description"><?php esc_html_e( 'Applies only to Snax Authors', 'snax' ); ?></p>
	<?php
}


/**
 * Embedly enabled?
 */
function snax_admin_setting_callback_embedly_enable() {
	?>
	<input name="snax_embedly_enable" id="snax_embedly_enable" type="checkbox" <?php checked( snax_is_embedly_enabled() ); ?> />
	<p>
		<?php echo wp_kses_post( __( '<a href="http://Embedly.com" target="_blank">Embedly</a> is an alternative embed handler for Snax. It allows you to have unified, beautiful embeds on your site with more than 400 services supported. Free plan does not require any API key - just enable and enjoy!', 'snax' ) ); ?>
	</p>
	<?php
}

/**
 * Dark skin enabled?
 */
function snax_admin_setting_callback_embedly_dark_skin() {
	?>
	<input name="snax_embedly_dark_skin" id="snax_embedly_dark_skin" type="checkbox" <?php checked( snax_is_embedly_dark_skin() ); ?> />
	<?php
}

/**
 * Share buttons enabled?
 */
function snax_admin_setting_callback_embedly_buttons() {
	?>
	<input name="snax_embedly_buttons" id="snax_embedly_buttons" type="checkbox" <?php checked( snax_is_embedly_buttons() ); ?> />
	<?php
}

/**
 * Embed width
 */
function snax_admin_setting_callback_embedly_width() {
	?>
	<input name="snax_embedly_width" id="snax_embedly_width" type="number" value="<?php echo esc_attr( snax_get_embedly_width() ); ?>" placeholder="<?php esc_attr_e( 'e.g. 500px', 'snax' ); ?>" />
	<p class="description"><?php esc_html_e( 'Leave empty for responsive.', 'snax' ); ?></p	>
	<?php
}

/**
 * Embed alignment
 */
function snax_admin_setting_callback_embedly_alignment() {
	$alignment = snax_get_embedly_alignment();
	?>

	<select name="snax_embedly_alignment" id="snax_embedly_alignment">
		<option value="center" <?php selected( 'center' === $alignment, true ) ?>><?php esc_html_e( 'center', 'snax' ) ?></option>
		<option value="left" <?php selected( 'left' === $alignment, true ) ?>><?php esc_html_e( 'left', 'snax' ) ?></option>
		<option value="right" <?php selected( 'right' === $alignment, true ) ?>><?php esc_html_e( 'right', 'snax' ) ?></option>
	</select>
	<?php
}

/**
 * Embedly API key
 */
function snax_admin_setting_callback_embedly_api_key() {
	$api_key = snax_get_embedly_api_key();
	?>
	<input name="snax_embedly_api_key" id="snax_embedly_api_key" class="regular-text" type="text" size="5" value="<?php echo esc_attr( $api_key ); ?>" />
	<?php
	if ( $api_key ) {
		if ( snax_embedly_verify_cards_key( $api_key ) ) {
			echo wp_kses_post( __( '&#10004;', 'snax' ) );
		} else {
			echo wp_kses_post( __( '&#10006;', 'snax' ) );
		}
	} else {
		?><p class="description"><?php
		echo wp_kses_post( sprintf( __( 'Get your Embedly API key at <a href="%s" target="_blank">Embed.ly/cards</a>', 'snax' ), esc_url( 'http://Embed.ly/cards' ) ) );
		?></p><?php
	}
}

/*
 * Texts > Singular Name
 */
function snax_admin_setting_callback_embed_singular_name() {
	?>
	<input name="snax_embed_singular_name" id="snax_embed_singular_name" class="regular-text" type="text" value="<?php echo esc_attr( snax_embed_get_singular_name() ); ?>" placeholder="<?php echo esc_attr_x( 'e.g. Embed', 'Setting placeholder', 'snax' ); ?>" />
	<p class="description">
		<?php echo esc_html_x( 'Leave empty to use default.', 'Setting description', 'snax' ); ?>
	</p>
	<?php
}

/*
 * Texts > Add new
 */
function snax_admin_setting_callback_embed_add_new() {
	?>
	<input name="snax_embed_add_new" id="snax_embed_add_new" class="regular-text" type="text" value="<?php echo esc_attr( snax_embed_get_add_new() ); ?>" placeholder="<?php echo esc_attr_x( 'e.g. Embed', 'Setting placeholder', 'snax' ); ?>" />
	<p class="description">
		<?php echo esc_html_x( 'Leave empty to use default.', 'Setting description', 'snax' ); ?>
	</p>
	<?php
}

/*
 * Texts > Description
 */
function snax_admin_setting_callback_embed_description() {
	?>
	<input name="snax_embed_description" id="snax_embed_description" class="regular-text" type="text" value="<?php echo esc_attr( snax_embed_get_description() ); ?>" placeholder="<?php echo esc_attr_x( 'e.g. Facebook post, Twitter status, etc.', 'Setting placeholder', 'snax' ); ?>" />
	<p class="description">
		<?php echo esc_html_x( 'Leave empty to use default.', 'Setting description', 'snax' ); ?>
	</p>
	<?php
}

/*
 * Texts > Overview
 */
function snax_admin_setting_callback_embed_overview() {
	?>
	<textarea name="snax_embed_overview" id="snax_embed_overview" rows="5" class="large-text"><?php echo esc_attr( snax_embed_get_overview() ); ?></textarea>
	<?php
}
