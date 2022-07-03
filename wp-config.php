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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'wordpress' );

/** Database hostname */
define( 'DB_HOST', 'db' );

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
define( 'AUTH_KEY',         '9!YBLuYi%{-xHon+:D$?[e(gf_%-aXIwLM2)PjNiDuyXL>/!umt:KSUy_%l4R4r?' );
define( 'SECURE_AUTH_KEY',  '~ P|ZCTl$>Kq3d.I`r)8{~>kY&s;%Zg<t5|kwI$N2PN$Yl!}1OK/tQ6}fd/~y6-w' );
define( 'LOGGED_IN_KEY',    'mK_cXtBFKxs-x?|,aT^S%xktxQ5H:u&c[)EYFl+ +8uD?J7l,91QL[M!P>0-NOwD' );
define( 'NONCE_KEY',        ',g5}=He=F.OK}?WfqKx8DD:k(E1p}r =kp_TBa2qi3ykVjqMB?NH@H$:y`qtw$qd' );
define( 'AUTH_SALT',        '^96pU{09.dPpLdfgYe=HiedJ}BkU>o~Dk)AKhtPi4Tnc;!:FUY4:I90HuaN{k2~v' );
define( 'SECURE_AUTH_SALT', 'IC%7&0]qbM#zh^>2RG.QOwq&fq4FYin0NgoIwWjqek0b,WdzBP+,i~GZjIR|.rK/' );
define( 'LOGGED_IN_SALT',   'W+q*y!|g;bNNm2$^#rS-s%;1  OGc_+r,V2(hVB6Tu)QY j.ay0<34{6H]5S;/S[' );
define( 'NONCE_SALT',       '-dKYQG^uzwB1rF.Z}CteEE=Qo}~cs2cu%g=Z8J?yfNCx)]z7AgW|4g@IGivv*SRW' );

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
