<?php
/**
 * Plugin Name: Amaze WooCommerce Image Zoom
 * Description: Adds a product image magnifier to WooCommerce product galleries.
 * Version: 1.0.0
 * Author: UmerZaki
 * Author URI: https://www.fiverr.com/umerzaki1
 * Text Domain: amaze-woocommerce-product-image-magnifier
 * Domain Path: /languages
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * WC requires at least: 7.2
 * WC tested up to: 10.9.4
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Amaze_WooCommerce_Image_Zoom
 */

defined( 'ABSPATH' ) || exit;

define( 'AMAZE_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_VERSION', '1.0.0' );
define( 'AMAZE_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_PLUGIN_FILE', __FILE__ );
define( 'AMAZE_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'AMAZE_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once AMAZE_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_PLUGIN_PATH . 'includes/functions.php';
