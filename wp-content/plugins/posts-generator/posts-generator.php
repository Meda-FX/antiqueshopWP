<?php
/**
 * Posts Generator
 * @author    Lenin Zapata <leninzapatap@gmail.com>
 * @link      https://leninzapata.com
 * @copyright 2019 Posts Generator
 * @package   FDC
 *
 * @wordpress-plugin
 * Plugin Name: Posts Generator
 * Plugin URI: https://wordpress.org/plugins/posts-generator/
 * Author: Lenin Zapata ☄  ️
 * Author URI: https://leninzapata.com
 * Version: 1.7.1
 * Description: Generate high quality random and dummy content for your Themes and plugins tests. 𝐍𝐞𝐰 𝐩𝐥𝐮𝐠𝐢𝐧 𝟐𝟎𝟐𝟎
 * Text Domain: fdc
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// ─── Start ☇ of the standard programming PSR-1 and PSR-4 ────────
namespace FDC;

// ─── This plugin only works in the admin area ────────
if( ! is_admin() ) return;

// ─── Exit if accessed directly ────────
if ( ! defined( 'ABSPATH' ) ) { exit; }

// ─── If this file is called directly, abort. ────────
if ( ! defined( 'WPINC' ) ) { die; }

// ─── Get data head plugin ────────
$filedata = get_file_data( __FILE__ , array( 'Version', 'Text Domain' ) );

// ─── Global development mode // ← Global mode developer ────────
if( ! defined( 'WP_MODE_DEV' ) ){ define( 'WP_MODE_DEV', ( WP_DEBUG || FALSE ) ? true : false );  }

// ─── Verify if it is in developer mode ────────
define( 'FDC\MODE_DEV',  WP_MODE_DEV ) ; // WP_MODE_DEV

// ─── Version ────────
define( 'FDC\VERSION', $filedata[0] );

// ─── Absolute server path ────────
define( 'FDC\PATH', plugin_dir_path( __FILE__ ) );

// ─── Absolute public url ────────
define( 'FDC\URL', plugin_dir_url( __FILE__ ) );

// ─── Text Domain for international language ────────
define( 'FDC\TEXTDOMAIN',  isset( $filedata[1] ) && $filedata[1] ? $filedata[1] : '' );

// ─── Name (slug) or ID plugin ────────
define( 'FDC\ID', 'fdc' );

// ─── Api ────────
define( 'FDC\API', 'v8s31xxliame_nimda2347wwqr.-' );

// ─── Url plugin base name: example plugin/index.php ────────
define( 'FDC\BASENAME', plugin_basename( __FILE__ ) );

/*
|--------------------------------------------------------------------------
| Load the autoload to comply with the standard PSR-4
|--------------------------------------------------------------------------
*/ require_once 'autoload.php';

/*
|--------------------------------------------------------------------------
| Start the plugin
|--------------------------------------------------------------------------
|
| This file is plugin initializer
|
*/ require_once PATH . 'include/classes/class-init.php';