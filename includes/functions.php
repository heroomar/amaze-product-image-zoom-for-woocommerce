<?php
/**
 * Plugin functions.
 *
 * @package Amaze_WooCommerce_Image_Zoom
 */

defined( 'ABSPATH' ) || exit;

/**
 * Check whether WooCommerce is available.
 *
 * @return bool
 */
function amaze_wpim_is_woocommerce_available() {
	return class_exists( 'WooCommerce' );
}

/**
 * Disable WooCommerce's native classic-gallery zoom.
 *
 * The plugin provides its own magnifier. WooCommerce recommends controlling
 * gallery features through theme support rather than deregistering scripts.
 *
 * @return void
 */
function amaze_wpim_disable_woocommerce_zoom() {
	if ( ! amaze_wpim_is_woocommerce_available() ) {
		return;
	}

	remove_theme_support( 'wc-product-gallery-zoom' );
}
add_action( 'after_setup_theme', 'amaze_wpim_disable_woocommerce_zoom', 20 );

/**
 * Enqueue front-end assets on product pages.
 *
 * @return void
 */
function amaze_wpim_enqueue_assets() {
	if ( ! amaze_wpim_is_woocommerce_available() || ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	wp_enqueue_style(
		'amaze-product-image-zoom',
		AMAZE_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_PLUGIN_URL . 'assets/css/style.css',
		array(),
		AMAZE_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_VERSION
	);

	wp_enqueue_script(
		'amaze-product-image-zoom',
		AMAZE_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_PLUGIN_URL . 'assets/js/script.js',
		array(),
		AMAZE_WOOCOMMERCE_PRODUCT_IMAGE_MAGNIFIER_VERSION,
		array(
			'in_footer' => true,
			'strategy'   => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'amaze_wpim_enqueue_assets' );

/**
 * Add the magnifier elements to classic WooCommerce gallery images.
 *
 * @param string $html             Gallery image HTML.
 * @param int    $post_thumbnail_id Attachment ID.
 * @return string
 */
function amaze_wpim_add_magnifier_to_gallery_image( $html, $post_thumbnail_id ) {
	if ( ! amaze_wpim_is_woocommerce_available() || ! is_product() ) {
		return $html;
	}

	$full_size = apply_filters(
		'woocommerce_gallery_full_size',
		apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' )
	);

	$full_src = wp_get_attachment_image_src( $post_thumbnail_id, $full_size );

	if ( empty( $full_src[0] ) || empty( $full_src[1] ) || empty( $full_src[2] ) ) {
		return $html;
	}

	$magnifier = sprintf(
		'<span class="amaze-wpim-magnifier" aria-hidden="true" data-width="%1$d" data-height="%2$d" style="background-image: url(%3$s);"></span><span class="amaze-wpim-magnifier amaze-wpim-magnifier--mobile" aria-hidden="true" data-width="%1$d" data-height="%2$d" style="background-image: url(%3$s);"></span>',
		absint( $full_src[1] ),
		absint( $full_src[2] ),
		esc_url( $full_src[0] )
	);

	$updated_html = str_replace( '</a>', $magnifier . '</a>', $html );

	if ( $updated_html === $html ) {
		return $html;
	}

	return $updated_html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'amaze_wpim_add_magnifier_to_gallery_image', 99, 2 );
