<?php
/**
 * General Settings page
 *
 * @package whats-your-reaction
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'wyr_settings_pages', 'wyr_register_general_settings_page', 10 );

function wyr_get_general_settings_page_id() {
	return apply_filters( 'wyr_general_settings_page_id', 'wyr-general-settings' );
}

function wyr_get_general_settings_page_config() {
	return apply_filters( 'wyr_general_settings_config', array(
		'tab_title'                 => _x( 'General', 'Settings Page', 'wyr' ),
		'page_title'                => '',
		'page_description_callback' => 'wyr_general_settings_page_description',
		'page_callback'             => 'wyr_general_settings_page',
		'fields'                    => array(
            'wyr_voting_post_types' => array(
                'title'             => __( 'Allow users to vote on post types', 'wyr' ),
                'callback'          => 'wyr_setting_voting_post_types',
                'sanitize_callback' => 'wyr_sanitize_text_array',
                'args'              => array(),
            ),
			'wyr_member_profile_link' => array(
				'title'             => _x( 'Show Member Profile Page link', 'Settings Page', 'wyr' ),
				'callback'          => 'wyr_setting_member_profile_link',
				'sanitize_callback' => 'sanitize_text_field',
				'args'              => array(),
			),
		),
	) );
}

function wyr_register_general_settings_page( $pages ) {
	$pages[ wyr_get_general_settings_page_id() ] = wyr_get_general_settings_page_config();

	return $pages;
}

/**
 * Settings page description
 */
function wyr_general_settings_page_description() {}

/**
 * Settings page
 */
function wyr_general_settings_page() {
	$page_id        = wyr_get_general_settings_page_id();
	$page_config    = wyr_get_general_settings_page_config();
	?>

	<div class="wrap">

		<h1><?php esc_html_e( 'What\'s Your Reaction Settings', 'wyr' ); ?> </h1>

		<h2 class="nav-tab-wrapper"><?php wyr_admin_settings_tabs( $page_config['tab_title'] ); ?></h2>
		<form action="options.php" method="post">

			<?php settings_fields( $page_id ); ?>
			<?php do_settings_sections( $page_id ); ?>

			<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'wyr' ); ?>" />
			</p>

		</form>
	</div>

	<?php
}

/**
 * Post types.
 */
function wyr_setting_voting_post_types() {
    $post_types         = wyr_get_supported_post_types();
    $checked_post_types = wyr_voting_get_post_types();

    foreach ( $post_types as $post_type ) {
        $checkbox_id = 'wyr_voting_post_type_' . $post_type;
        ?>
        <fieldset>
            <label for="<?php echo esc_attr( $checkbox_id ); ?>">
                <input name="wyr_voting_post_types[]" id="<?php echo esc_attr( $checkbox_id ); ?>" type="checkbox" value="<?php echo esc_attr( $post_type ); ?>" <?php checked( in_array( $post_type, $checked_post_types, true ) , true ); ?> /> <?php echo esc_html( $post_type ); ?>
            </label>
        </fieldset>
        <?php
    }
    ?>
    <?php
}

function wyr_setting_member_profile_link() {
	$checked = wyr_member_profile_link();
	?>
	<input type="checkbox" name="wyr_member_profile_link" id="wyr_member_profile_link" value="standard"<?php checked( $checked, true ); ?> />
    <p class="description">
        <?php esc_html_e( 'Uncheck to hide the info "Browse and manage your reactions from your Member Profile Page" under the reactions box.', 'wyr' ); ?><br />
    </p>
	<?php
}