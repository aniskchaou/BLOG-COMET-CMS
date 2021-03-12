<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'blog-comet' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Wl c/a723F%-;iU)mivMVk[6?` B@,z|F@Ws$u`ANP0qOC8h00S_UakL$h20eMoB' );
define( 'SECURE_AUTH_KEY',  ',-xUs&a7Er<,iQ*>V.l;xB*4YOhrjjW?D}?ff3 D+x.Q}iaV?!>P0w+Cxrx+QAZd' );
define( 'LOGGED_IN_KEY',    'vS3917pT$BjhX3Tmbca+aHpS MZv4CY^>=2UyuZNe%WEf(Nk}2UpQgHYWoG#]E=>' );
define( 'NONCE_KEY',        '2OTk,J&mF2S%HgWY1G}K83xp,f}i}n$$Q0{R/*}?gUguFVC|NDM6;sW}:L;EO~la' );
define( 'AUTH_SALT',        'KjvfXWY8le9FN~=s7bStt[0$8-St!=K2L=&5~z~LjupK]qAi:gMXsUv/l`H{KM:_' );
define( 'SECURE_AUTH_SALT', '}M S*w=_~R6+nQ0o5R3+NgrlP{[1XF?#)>&(NAq&+%bF)^)>2o`w=Zmj4,NDEdyd' );
define( 'LOGGED_IN_SALT',   'F40pMNO%JcK3ax2~ >InHSN]:U!s(gf`}<%bNmLQaNEampyL/NskPpR:b3]-4nzh' );
define( 'NONCE_SALT',       'm<&LV_t*W7J^u+7L/5Apd@;NxXQ0@Te?l9Jq:*3GST22yUw 2V<s<+cYz6$]gl>W' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
