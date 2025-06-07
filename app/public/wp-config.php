<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '}+fF$O526}.Io~Ve|svFhqd|_qe$c{AlPlW@W%h|<LQA4!=aUsF^)*>&]]VKiXc{' );
define( 'SECURE_AUTH_KEY',   '4$_|ZYHg8dG<GG}n&ah~znkh!#FF_[*.bj7}+@=15q/m$4DH%K]HrW`>~im|S59%' );
define( 'LOGGED_IN_KEY',     'd5yjfMn0(6l$zkYEnq|utff0wvgr(uMDWbiNdzo?hQ*F}XfbhNh;K}P^ut->$B04' );
define( 'NONCE_KEY',         '7REPc@JfM>U*gtRVn6SzQ;kzMnLThgg0%rL#d{-D$JjM[z785J4R8%3&i-GtRt)+' );
define( 'AUTH_SALT',         'OuCw{R3m+l$>>PG>*5,10SQz6~[b-5I]?nNiCd)BvCQrG!p)DQgl(hi7Xb 8Ih3,' );
define( 'SECURE_AUTH_SALT',  'TddkmB+mCP!X[S(8%nw*htE6R@Dx&cM3^t+h=^YF-o#-F]-su7.f-[EPg*(CZZz<' );
define( 'LOGGED_IN_SALT',    'J%sT^E(tq:K&; ixS<[Zt[c!h-u8-x^DsJv`}XTd*.;4`H*Xs~UI=DGP2,#aFp7#' );
define( 'NONCE_SALT',        'a[yloYOw]hZ pK/I.Sm{bFwzPf[#TV|wS44Q<oUPU_qlp`Wn#z@<LLv+g==TT5as' );
define( 'WP_CACHE_KEY_SALT', '5WE%,sOq,`^2B_>(-$-IQUG-32F@=YZs&Dhra!]c<QwEGOm>4uSH~$/:hG-};K14' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
