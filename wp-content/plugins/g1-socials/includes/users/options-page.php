<?php
/**
 * Options Page for Users
 *
 * @package G1 Socials
 * @subpackage Functions
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

add_filter( 'g1_socials_options_tabs', 'g1_socials_users_add_options_tab' );
add_action( 'admin_menu', 'g1_socials_add_users_options_sections_and_fields' );

/**
 * Add Options Tab
 */
function g1_socials_users_add_options_tab( $tabs = array() ) {
	$tabs['g1_socials_users'] = array(
		'path'     => add_query_arg( array(
			'page' => g1_socials_options_page_slug(),
			'tab'  => 'g1_socials_users',
		), '' ),
		'label'    => esc_html__( 'Users', 'g1_socials' ),
		'settings' => 'g1_socials_users',
	);
	return $tabs;
}

/**
 * Add options page sections, fields and options.
 */
function g1_socials_add_users_options_sections_and_fields() {
	// Add setting section.
	add_settings_section(
		'g1_socials_users', // Section id.
		'', // Section title.
		'g1_socials_options_users_description_renderer_callback', // Section renderer callback with args pass.
		'g1_socials_users' // Page.
	);

    // Enable User Profile
    add_settings_field(
        'g1_socials_social_media_header', // Field ID.
        '<h2>' . __( 'Social Media Profiles', 'g1_socials' ) . '</h2>',
        '__return_empty_string', // Callback.
        'g1_socials_users', // Page.
        'g1_socials_users'
    );

	// Enable User Profile
	add_settings_field(
		'g1_socials_enable_user_profiles', // Field ID.
        __( 'Enable', 'g1_socials' ), // Field title.
		'g1_socials_options_users_fields_renderer_callback', // Callback.
		'g1_socials_users', // Page.
		'g1_socials_users', // Section.
		array(
			'field_for' => 'g1_socials_enable_user_profiles',
		) // Data for callback.
	);
	// Register setting.
	register_setting(
		'g1_socials_users', // Option group.
		'g1_socials_enable_user_profiles' // Option name.
	);

    // User Profile Active Icons
    add_settings_field(
        'g1_socials_user_supported_networks', // Field ID.
        __( 'Active networks', 'g1_socials' ), // Field title.
        'g1_socials_options_users_fields_renderer_callback', // Callback.
        'g1_socials_users', // Page.
        'g1_socials_users', // Section.
        array(
            'field_for' => 'g1_socials_user_supported_networks',
        ) // Data for callback.
    );
    // Register setting.
    register_setting(
        'g1_socials_users', // Option group.
        'g1_socials_user_supported_networks' // Option name.
    );
}

function g1_socials_options_users_description_renderer_callback() {}

/**
 * Options fields renderer.
 *
 * @param array $args Field arguments.
 */
function g1_socials_options_users_fields_renderer_callback( $args ) {
	switch ( $args['field_for'] ) {
		case 'g1_socials_enable_user_profiles':
			$option = get_option( $args['field_for'], 'standard' );
			?>
			<input type="checkbox" id="<?php echo( esc_html( $args['field_for'] ) ); ?>" name="<?php echo( esc_html( $args['field_for'] ) ); ?>" value="standard"<?php checked( 'standard', $option ); ?> />
            <small>
                <?php _e( 'Allow users, on Profile > Edit page, to provide their social media profile URLs', 'g1_socials' ); ?>
            </small>

            <?php
			break;

        case 'g1_socials_user_supported_networks':
            $field_name = $args['field_for'];
            $active_networks = g1_socials_user_get_supported_networks();
            $all_networks = G1_Socials()->get_items();
            ?>
            <small>
                <?php _e( 'Click to toggle', 'g1_socials' ); ?>
            </small>
            <ul class="g1-socials-items g1-socials-items-tpl-grid g1-socials-hb-list g1-socials-s">
            <?php
            foreach ($all_networks as $network => $data) {
                $is_active = isset( $active_networks[ $network ] );

                $item_icon_class = array(
                    'g1-socials-item-icon',
                    'g1-socials-item-icon-48',
                    'g1-socials-item-icon-' . $network
                );

                if ( ! $is_active ) {
                    $item_icon_class[] = 'g1-socials-item-icon-text';
                }
                ?>
                <li class="g1-socials-item g1-socials-item-<?php echo sanitize_html_class( $network ); ?>">
                    <label class="g1-socials-checkbox-label">
                        <?php printf( '<input type="checkbox" name="%s[%s]" value="%s" %s/>', $field_name, $network, $network, checked( $is_active, true, false ) ); ?>
                        <span class="<?php echo implode( ' ', array_map( 'sanitize_html_class', $item_icon_class ) ); ?>"></span>
	                    <span class="g1-social-item-text"><?php echo esc_html( $network ); ?></span>
                    </label>
                </li>
                <?php
            }
            ?>
            </ul>
            <?php
            break;
	}
}

