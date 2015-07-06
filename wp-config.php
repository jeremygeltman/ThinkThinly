<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'WPCACHEHOME', '/home4/saramy/public_html/Thinkthinly.com/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('WP_CACHE', true); //Added by WP-Cache Manager
define('DB_NAME', 'saramy_wrdp4');

/** MySQL database username */
define('DB_USER', 'saramy_wrdp4');

/** MySQL database password */
define('DB_PASSWORD', 'gFdmtchNppfYRkj');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
//define('AUTH_KEY',         'h?0yX)jk<t-x#TrsqwI)*WW*1X?upSvPdtvz*CgXQEGhX:5)cRe#-/(ANIE!DYCBZ^A/3AT|/)xtqnmm');
//define('SECURE_AUTH_KEY',  'U_<TwI(Kro;cC20aV~dZU|bIFj_5g>ln1_)XuOvf58b>Ezf?5036);@JFgvp==URs(0z>H/Num');
//define('LOGGED_IN_KEY',    'xbUDnfCYW\`wQ~;qxfVGa27Vcf:*MG7d0ilaOX(Z(2j|Z02DRUD3~m;uAX(f!7q>$0=R*SUy6Ke@');
//define('NONCE_KEY',        '@k\`Q_r9f5a1=ph=B>1cP@<6;Oo9c4VigGS1YM3sd^~KR/PU)ro|P0UGhmaqIT7:Ehy');
//define('AUTH_SALT',        'TPuJ^U#<#Jk5UTb3mTzQKmq!iFimG8Fw*xYyZhAT_pLYL0ZdiP$zMveOV2CVPq*2VF_;');
//define('SECURE_AUTH_SALT', 'd_-S83MZCcl_4Tu;=qJnSaK:zg~Cu:JEBnImyotDo?=fSvsZY$ZsR6>mFJlI1Qk:T$kORB*l|(');
//define('LOGGED_IN_SALT',   '=^Hxo#OZNKq<8H#c@Kl>>4XJP1AQFJyrn9~az_P5#BQG9KsdaE/dewC?#O?dNFmra)3<5@1UH|xY*Sv');
//define('NONCE_SALT',       'd98gH*f;C/0xC0jZ:IG7m#Ksp9He3a6X81ZT5yFIP;X?VP)^Fx4xgr;uN0|xJazBn)Sq1;');

define('AUTH_KEY',         '');
define('SECURE_AUTH_KEY',         '');
define('LOGGED_IN_KEY',         '');
define('NONCE_KEY',         '');
define('AUTH_SALT',         '');
define('SECURE_AUTH_SALT',         '');
define('LOGGED_IN_SALT',         '');
define('NONCE_SALT',         '');


/**#@-*/
define('AUTOSAVE_INTERVAL', 600 );
define('WP_POST_REVISIONS', 1);
define( 'WP_CRON_LOCK_TIMEOUT', 120 );
define( 'WP_AUTO_UPDATE_CORE', true );
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
//add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );
//Brian3t
define('WP_POST_REVISIONS', false );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
