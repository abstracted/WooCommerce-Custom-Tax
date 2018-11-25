<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       cameron.computer
 * @since      1.0.0
 *
 * @package    Wc_Custom_Tax
 * @subpackage Wc_Custom_Tax/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wc_Custom_Tax
 * @subpackage Wc_Custom_Tax/public
 * @author     Cameron Sanders <csanders@protonmail.com>
 */
class Wc_Custom_Tax_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Custom_Tax_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Custom_Tax_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-custom-tax-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Custom_Tax_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Custom_Tax_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-custom-tax-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Gets the tax rates from the taxes.json file
	 * 
	 * @since 1.0.0
	 */
	private function wc_get_taxrates() {
		$taxrates_file = plugin_dir_path( __DIR__ ) . 'includes/taxes.json';
		$taxrates_contents = file_get_contents($taxrates_file);
		return json_decode($taxrates_contents);
	}

	/**
	 * Gets the product category slug name based on category id
	 * 
	 * @since 1.0.0
	 */
	private function wc_get_category_name($id) {
		if( $term = get_term_by( 'id', $id, 'product_cat' ) ){
    	return $term->name;
		}
	} 

	/**
	 * Adds the custom taxes defined in includes/taxes.json to the checkout in woocommerce
	 * 
	 * @since 1.0.0
	 */
	public function wc_add_custom_taxes() {
		global $woocommerce;
 
		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
			return;
		
		$tax_amount = 0;
		$tax_rates = $this->wc_get_taxrates();
		$cart_items = $woocommerce->cart->get_cart();
		
		var_dump($cart_items);
		foreach ($cart_items as $cart_item) {
		}

		// if ( in_array( $woocommerce->customer->get_shipping_country(), $country ) ) {
		// 	$surcharge = ( $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total ) * $percentage;
		// }
		if ($tax_amount > 0) {
			$woocommerce->cart->add_fee( 'Additional Taxes', $tax_amount, true, '' );
		}

		// Create a variable to hold the surcharge tax amount
		// Get a list of all products
		// Loop through products
			// Determine the product category
			// Check if product cateogry is in taxes.json
			// If it is, determine if billing address state is listed in tax 	rates property
			// Calculate the tax amount based on cost of goods
			// Append tax amount to surcharge variable
		// Add surcharge to cart fee
		
	}

}
