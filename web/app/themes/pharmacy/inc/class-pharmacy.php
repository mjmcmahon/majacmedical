<?php
/**
 * Pharmacy Class
 *
 * @author   WooThemes
 * @since    1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Pharmacy' ) ) {

class Pharmacy {
	/**
	 * Setup class.
	 *
	 * @since 1.0.1
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts',               array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts',               array( $this, 'enqueue_child_styles' ), 99 );
		add_action( 'storefront_loop_columns', 			array( $this, 'pharmacy_loop_columns' ) );
		add_action( 'swc_product_columns_default', 		array( $this, 'pharmacy_loop_columns' ) );
		add_filter( 'storefront_related_products_args', array( $this, 'pharmacy_related_products_args' ) );
	}

	/**
	 * Enqueue Storefront Styles
	 * @return void
	 */
	public function enqueue_styles() {
		global $storefront_version;

		wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css', $storefront_version );
	}

	/**
	 * Enqueue Storechild Styles
	 * @return void
	 */
	public function enqueue_child_styles() {
		global $pharmacy_version;

		wp_style_add_data( 'storefront-child-style', 'rtl', 'replace' );

		wp_enqueue_style( 'karla', '//fonts.googleapis.com/css?family=Karla:Karla:400,400italic,700,700italic', array( 'storefront-child-style' ) );
    	wp_enqueue_style( 'oxygen', '//fonts.googleapis.com/css?family=Oxygen:400,700', array( 'storefront-child-style' ) );

		wp_enqueue_script( 'pharmacy', get_stylesheet_directory_uri() . '/assets/js/pharmacy.min.js', array( 'jquery' ), $pharmacy_version, true );
	}
	
	/**
	 * Shop columns
	 * @return int number of columns
	 */
	function pharmacy_loop_columns( $columns ) {
		$columns = 4;
		return $columns;
	}
	
	/**
	 * Adjust related products columns
	 * @return array $args the modified arguments
	 */
	function pharmacy_related_products_args( $args ) {
		$args['posts_per_page'] = 4;
		$args['columns']		= 4;
	
		return $args;
	}
}

}

return new Pharmacy();