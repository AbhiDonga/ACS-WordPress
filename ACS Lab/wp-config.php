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
define( 'DB_NAME', 'ACS Lab' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '*do@df<MmuI>WTBEL{&X0M&S[xemcs]:4{h1mJ6XO;H44YKd7]VYcAS>Ob9ay`;K' );
define( 'SECURE_AUTH_KEY',  '$csw4V}hr-O<x5l!bHz$f=<GT6L8dwf%XC_`P9[(-e,Z4|bi/4m/}=pkX|tZ2,h^' );
define( 'LOGGED_IN_KEY',    '?J;iL#:7/I.syj{KRGfv0nItJ7f$PV#oWMpVKA_D.f3=&} Ker!&/Rc[FoN`hO}G' );
define( 'NONCE_KEY',        'K%#7(,j/A0,EEq*.;F+O%4OM{K#q8:o2~S]=:O{xHo35x7X.$H~XkD~BrCl9$%^R' );
define( 'AUTH_SALT',        'qRN{BIe#M2AJp}<hj.ra&[pal<oo2mfzrd<^L6qLk.Ife`&*KqSdm#R{qo65muN<' );
define( 'SECURE_AUTH_SALT', 'CmSeJokqpq,GNfc>m.d`VKn=OBbF_d/VOxv>/I<PHl[*J]h<3s4/`A=A1J))E8E;' );
define( 'LOGGED_IN_SALT',   ',>pvpIrLxe|l*O4G}~t7B]P]5RqZP+??,(8a@ KE:Vl(a0RzymuydoVJo]4hN_!!' );
define( 'NONCE_SALT',       'To}FGm(=Q}1V3Xb*pK@S;NQuQ~C^=q:xh5+o*d *BEM9m p@Au5nAKXM|%#u2w8o' );

/**#@-*/

/**
 * WordPress database table prefix.
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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
