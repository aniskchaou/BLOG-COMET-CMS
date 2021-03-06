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
add_filter( 'snax_admin_get_settings_sections', 'snax_admin_settings_sections_voting' );
add_filter( 'snax_admin_get_settings_fields',   'snax_admin_settings_fields_voting' );

/**
 * Register section
 *
 * @param array $sections       Sections.
 *
 * @return array
 */
function snax_admin_settings_sections_voting( $sections ) {
	$sections['snax_settings_voting'] = array(
		'title'    => __( 'Voting', 'snax' ),
		'callback' => 'snax_admin_settings_voting_section_description',
		'page'      => 'snax-voting-settings',
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
function snax_admin_settings_fields_voting( $fields ) {
	$fields['snax_settings_voting'] = array(
		'snax_voting_is_enabled' => array(
			'title'             => __( 'Enable voting?', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_enabled',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_guest_voting_is_enabled' => array(
			'title'             => __( 'Guests can vote?', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_guest_voting_enabled',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_voting_post_types' => array(
			'title'             => __( 'Allow users to vote on post types', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_post_types',
			'sanitize_callback' => 'snax_sanitize_text_array',
			'args'              => array(),
		),
		'snax_voting_actions'  => array(
			'title'             => __( 'Voting Actions', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_actions',
			'sanitize_callback' => 'snax_sanitize_text_field',
			'args'              => array(),
		),
		'snax_voting_post_icon' => array(
			'title'             => __( 'Post Icon', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_post_icon',
			'sanitize_callback' => 'snax_sanitize_text_field',
			'args'              => array(),
		),
        'snax_voting_member_profile_page_link' => array(
            'title'             => __( 'Show Member Profile Page link', 'snax' ),
            'callback'          => 'snax_admin_setting_callback_voting_member_profile_page_link',
            'sanitize_callback' => 'sanitize_text_field',
            'args'              => array(),
        ),
		'snax_hide_votes_header' => array(
			'title'             => '<h2>' . __( 'Hide votes', 'snax' ) . '</h2>',
			'callback'          => '__return_empty_string',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_hide_votes_threshold' => array(
			'title'             => __( 'Threshold', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_hide_votes_threshold',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_fake_votes_header' => array(
			'title'             => '<h2>' . __( 'Fake votes', 'snax' ) . '</h2>',
			'callback'          => '__return_empty_string',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_fake_vote_count_base' => array(
			'title'             => __( 'Count base', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_fake_vote_count_base',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_fake_vote_for_new' => array(
			'title'             => __( 'Disable for new submissions', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_fake_vote_for_new',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(),
		),
		'snax_voting_labels_header' => array(
			'title'             => '<h2>' . __( 'Labels', 'snax' ) . '</h2>',
			'callback'          => '__return_empty_string',
			'sanitize_callback' => 'intval',
			'args'              => array(),
		),
		'snax_voting_labels_vote_1' => array(
			'title'             => __( 'Vote: Singular', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_label',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(
				'id'        => 'vote_1',
				'default'   => _n( 'Vote', 'Votes', 1, 'snax' ),
			),
		),
		'snax_voting_labels_vote_n' => array(
			'title'             => __( 'Vote: Plural', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_label',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(
				'id'        => 'vote_n',
				'default'   => _n( 'Vote', 'Votes', 2, 'snax' ),
			),
		),
		'snax_voting_labels_upvote_1' => array(
			'title'             => __( 'Upvote: Singular', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_label',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(
				'id'        => 'upvote_1',
				'default'   => _n( 'Upvote', 'Upvotes', 1, 'snax' ),
			),
		),
		'snax_voting_labels_upvote_n' => array(
			'title'             => __( 'Upvote: Plural', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_label',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(
				'id'        => 'upvote_n',
				'default'   => _n( 'Upvote', 'Upvotes', 2, 'snax' ),
			),
		),
		'snax_voting_labels_downvote_1' => array(
			'title'             => __( 'Downvote: Singular', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_label',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(
				'id'        => 'upvote_1',
				'default'   => _n( 'Upvote', 'Upvotes', 1, 'snax' ),
			),
		),
		'snax_voting_labels_downvote_n' => array(
			'title'             => __( 'Downvote: Plural', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_label',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(
				'id'        => 'downvote_n',
				'default'   => _n( 'Downvote', 'Downvotes', 2, 'snax' ),
			),
		),
		'snax_voting_labels_point_1' => array(
			'title'             => __( 'Point: Singular', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_label',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(
				'id'        => 'point_1',
				'default'   => _n( 'Point', 'Points', 1, 'snax' ),
			),
		),
		'snax_voting_labels_point_n' => array(
			'title'             => __( 'Point: Plural', 'snax' ),
			'callback'          => 'snax_admin_setting_callback_voting_label',
			'sanitize_callback' => 'sanitize_text_field',
			'args'              => array(
				'id'        => 'point_n',
				'default'   => _n( 'Point', 'Points', 2, 'snax' ),
			),
		),
	);

	return $fields;
}

function snax_admin_voting_settings() {
	?>

	<div class="wrap">

		<h1><?php esc_html_e( 'Snax Settings', 'snax' ); ?> </h1>

		<h2 class="nav-tab-wrapper"><?php snax_admin_settings_tabs( __( 'Voting', 'snax' ) ); ?></h2>
		<form action="options.php" method="post">

			<?php settings_fields( 'snax-voting-settings' ); ?>
			<?php do_settings_sections( 'snax-voting-settings' ); ?>

			<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'snax' ); ?>" />
			</p>

		</form>
	</div>

	<?php
}

/**
 * Voting section description
 */
function snax_admin_settings_voting_section_description() {}

/**
 * Voting enabled?
 */
function snax_admin_setting_callback_voting_enabled() {
	?>
	<input name="snax_voting_is_enabled" id="snax_voting_is_enabled" type="checkbox" <?php checked( snax_voting_is_enabled() ); ?> />
	<?php
}

/**
 * Guest Voting enabled?
 */
function snax_admin_setting_callback_guest_voting_enabled() {
	?>
	<input name="snax_guest_voting_is_enabled" id="snax_guest_voting_is_enabled" type="checkbox" <?php checked( snax_guest_voting_is_enabled() ); ?> />
	<?php
}


/**
 * Post types.
 */
function snax_admin_setting_callback_voting_post_types() {
	$post_types = get_post_types();
	$hidden_post_types = array(
		'adace-ad',             // @todo
		'adace_link',           // @todo
		'adace_coupon',         // @todo
		'amp_validated_url',    // @todo
		'bp-email',             // @todo
		'attachment',
		'custom_css',
		'customize_changeset',
		'mc4wp-form',           // @todo
		'mycred_rank',          // @todo
		'mycred_badge',         // @todo
		'nav_menu_item',
		'oembed_cache',
		'revision',
		'shop_coupon',                  // WooCommerce
		'shop_order',                   // WooCommerce
		'shop_order_refund',            // WooCommerce
		'user_request',
		'vc_grid_item',         // @todo
		'vc4_templates',        // @todo
		'wpcf7_contact_form',   // @todo
		'wp_block',
		'wp_log',
		snax_get_item_post_type(),
		snax_get_question_post_type(),
		snax_get_answer_post_type(),
		snax_get_result_post_type(),
		snax_get_poll_question_post_type(),
		snax_get_poll_answer_post_type(),
		snax_get_poll_result_post_type(),
	);
	$post_types = array_diff( $post_types, $hidden_post_types );

	$checked_post_types = snax_voting_get_post_types();

	foreach ( $post_types as $post_type ) {
		$checkbox_id = 'snax_voting_post_type_' . $post_type;
		?>
		<fieldset>
			<label for="<?php echo esc_attr( $checkbox_id ); ?>">
				<input name="snax_voting_post_types[]" id="<?php echo esc_attr( $checkbox_id ); ?>" type="checkbox" value="<?php echo esc_attr( $post_type ); ?>" <?php checked( in_array( $post_type, $checked_post_types, true ) , true ); ?> /> <?php echo esc_html( $post_type ); ?>
			</label>
		</fieldset>
		<?php
	}
	?>
	<?php
}

/**
 * Post Icon.
 */
function snax_admin_setting_callback_voting_post_icon() {
	$current = snax_get_post_voting_icon();
	$icons = array(
		'caret' => array(
			'name'      => __( 'Carets', 'icon, snax' ),
			'icon_up'   => 'ue043-vote-up.svg',
			'icon_down' => 'ue044-vote-down.svg',
		),
		'chevron' => array(
			'name'      => __( 'Chevrons', 'icon, snax' ),
			'icon_up'   => 'ue070-vote-chevron-up.svg',
			'icon_down' => 'ue071-vote-chevron-down.svg',
		),
		'arrow' => array(
			'name'      => __( 'Arrows', 'icon, snax' ),
			'icon_up'   => 'ue072-vote-arrow-up.svg',
			'icon_down' => 'ue073-vote-arrow-down.svg',
		),
		'thumb' => array(
			'name'      => __( 'Thumbs', 'icon, snax' ),
			'icon_up'   => 'ue038-vote-thumb-up.svg',
			'icon_down' => 'ue037-vote-thumb-down.svg',
		),
		'plus' => array(
			'name'      => __( 'Plus Minus', 'icon, snax' ),
			'icon_up'   => 'ue074-vote-plus-up.svg',
			'icon_down' => 'ue075-vote-plus-down.svg',
		),
		'smile' => array(
			'name'      => __( 'Smile', 'icon, snax' ),
			'icon_up'   => 'ue076-vote-smile-up.svg',
			'icon_down' => 'ue077-vote-smile-down.svg',
		),
	);

	$icon_url = trailingslashit( snax()->css_url ) . 'snaxicon/svg/';
	?>
	<?php foreach( $icons as $icon_slug => $icon_args ) : ?>
		<label class="snax-admin-voting-iconset">
			<img src="<?php echo esc_url( $icon_url . $icon_args['icon_up'] ); ?>" width="16" height="16" alt="<?php echo esc_attr( $icon_args['name'] ); ?>" />
			<img src="<?php echo esc_url( $icon_url . $icon_args['icon_down'] ); ?>" width="16" height="16" alt="<?php echo esc_attr( $icon_args['name'] ); ?>" />
			<span><?php echo esc_html( $icon_args['name'] ); ?></span>
			<input name="snax_voting_post_icon" type="radio" value="<?php echo esc_attr( $icon_slug ); ?>" <?php checked( $icon_slug, $current ); ?> />
		</label>
	<?php endforeach; ?>
	<?php
}

/**
 * Guest Voting enabled?
 */
function snax_admin_setting_callback_voting_member_profile_page_link() {
    ?>
    <input name="snax_voting_member_profile_page_link" id="snax_voting_member_profile_page_link" type="checkbox" <?php checked( snax_voting_member_profile_page_link() ); ?> />
    <p class="description">
        <?php esc_html_e( 'Uncheck to hide the info "Browse and manage your votes from your Member Profile Page" under the voting box.', 'snax' ); ?><br />
    </p>
    <?php
}

/**
 * Voting Actions.
 */
function snax_admin_setting_callback_voting_actions() {
	$current = snax_get_voting_actions();
	$icons = array(
		'up-down' => array(
			'name'      => __( 'Up & Downvotes', 'snax' ),
			'icons'     => array( 'ue043-vote-up.svg', 'ue044-vote-down.svg' ),
		),
		'up' => array(
			'name'      => __( 'Upvotes Only', 'snax' ),
			'icons'     => array( 'ue043-vote-up.svg' ),
		),
	);

	$icon_url = trailingslashit( snax()->css_url ) . 'snaxicon/svg/';
	?>
	<?php foreach ( $icons as $icon_slug => $icon_args ) : ?>
		<label class="snax-admin-voting-iconset">
			<?php foreach ( $icon_args['icons'] as $svg ) : ?>
				<img src="<?php echo esc_url( $icon_url . $svg ); ?>" width="16" height="16" alt="<?php echo esc_attr( $icon_args['name'] ); ?>" />
			<?php endforeach; ?>

			<span><?php echo esc_html( $icon_args['name'] ); ?></span>
			<input name="snax_voting_actions" type="radio" value="<?php echo esc_attr( $icon_slug ); ?>" <?php checked( $icon_slug, $current ); ?> />
		</label>
	<?php endforeach; ?>
	<?php
}


/**
 * Fake vote count base
 */
function snax_admin_setting_callback_fake_vote_count_base() {
	?>
	<input name="snax_fake_vote_count_base" id="snax_fake_vote_count_base" type="number" value="<?php echo esc_attr( snax_get_fake_vote_count_base() ); ?>" placeholder="<?php esc_attr_e( 'e.g. 1000', 'snax' ); ?>" />
	<p class="description">
		<?php esc_html_e( 'Fake votes for a post are calculated based on this value and a post creation date (older posts\' votes are closer to the count base).', 'snax' ); ?><br />
		<?php esc_html_e( 'Leave empty to not use "Fake votes" feature.', 'snax' ); ?>
	</p>
	<?php
}

function snax_admin_setting_callback_hide_votes_threshold() {
	?>
	<input name="snax_hide_votes_threshold" id="snax_hide_votes_threshold" type="number" value="<?php echo esc_attr( snax_get_hide_votes_threshold() ); ?>" />
	<p class="description">
		<?php esc_html_e( 'If you fill in any number here, the votes for a specific post are not shown until the vote count of this number is reached.', 'snax' ); ?><br />
	</p>
	<?php
}

/**
 * Fake vote count base
 */
function snax_admin_setting_callback_fake_vote_for_new() {
	?>
	<input name="snax_fake_vote_for_new" id="snax_fake_vote_for_new" type="checkbox" <?php checked( snax_is_fake_vote_disabled_for_new() ); ?> />
	<p class="description">
		<?php esc_html_e( 'New users\' submitted posts won\'t be affected with fake votes', 'snax' ); ?>
	</p>
	<?php
}


function snax_admin_setting_callback_voting_label( $args) {
	$value = get_option( 'snax_voting_labels_' . $args['id'] );
	$placeholder = 'e.g. ' . $args['default'];
	?>
	<input
		name="snax_voting_labels_<?php echo esc_attr( $args['id'] ); ?>"
		id="snax_voting_labels_<?php echo esc_attr( $args['id']); ?>"
		type="text"
		placeholder="<?php echo esc_attr( $placeholder ); ?>"
		value="<?php echo esc_attr( $value ); ?>"
	/>
	<span class="description"><?php esc_html_e( 'Leave empty to use default', 'snax' ); ?></span>
	<?php
}