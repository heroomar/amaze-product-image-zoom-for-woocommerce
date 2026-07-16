<?php
/**
 * Plugin Name: Pixora Product Magnifier for WooCommerce
 * Description: Adds a product image magnifier to WooCommerce product galleries.
 * Version: 1.0.0
 * Author: UmerZaki
 * Author URI: https://www.fiverr.com/umerzaki1
 * Text Domain: pixora-product-magnifier-for-woocommerce
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 * WC requires at least: 7.2
 * WC tested up to: 10.9.4
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Pixora_Product_Magnifier_for_WooCommerce
 */

defined( 'ABSPATH' ) || exit;

define( 'PIXORA_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_VERSION', '1.0.0' );
define( 'PIXORA_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_PLUGIN_FILE', __FILE__ );
define( 'PIXORA_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'PIXORA_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
/**
 * Declare WooCommerce feature compatibility.
 *
 * @return void
 */
function pixora_wpim_declare_woocommerce_compatibility() {
	if ( ! class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		return;
	}

	\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
		'custom_order_tables',
		__FILE__,
		true
	);

	\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
		'product_block_editor',
		__FILE__,
		true
	);
}
add_action( 'before_woocommerce_init', 'pixora_wpim_declare_woocommerce_compatibility' );

require_once PIXORA_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_PLUGIN_PATH . 'includes/functions.php';
