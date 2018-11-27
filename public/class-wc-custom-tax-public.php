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
	 * Returns the tax rates from the taxes.json file
	 * 
	 * @since 1.0.0
	 */
	private function wc_get_taxrates() {
		$taxrates_file = plugin_dir_path( __DIR__ ) . 'includes/taxes.json';
		$taxrates_contents = file_get_contents($taxrates_file);
		return json_decode($taxrates_contents);
	}

	/**
	 * Return boolean for customer is in State with custom tax
	 * @since 1.0.0
	 */
	private function wc_is_taxable_state($billing_state) {
		$is_taxable_state = false;
		foreach ($this->wc_get_taxrates() as $category) {
			foreach (get_object_vars($category->tax_rate) as $state => $tax_rate) {
				if ($billing_state === $state) {
					$is_taxable_state = true;
				}
				if ($is_taxable_state === true) {
					break;
				}
			}
		}
		return $is_taxable_state;
	}

	/**
	 * Returns the product category slug name based on category id
	 * 
	 * @since 1.0.0
	 */
	private function wc_get_category_name($id) {
		if( $term = get_term_by( 'id', $id, 'product_cat' ) ){
    	return $term->slug;
		}
	}

	/**
	 * Returns a flat array with all product categories for the items in the cart
	 * 
	 * @since 1.0.0
	 */
	private function wc_get_cart_product_categories($cart_items) {
		$cart_product_categories = [];
		foreach ($cart_items as $cart_item) {
			$quantity = $cart_item['quantity'];
			$product_id = $cart_item['product_id'];
			$product = wc_get_product($product_id);
			$product_category_ids = $product->get_category_ids();
			for ($i = 0; $i < $quantity; $i++) {
				foreach ($product_category_ids as $id) {
					array_push($cart_product_categories, $this->wc_get_category_name($id));
				}
			}
		}
		return $cart_product_categories;
	}

	/**
	 * Returns additional tax amount to be added to checkout, calculates tax rates in process
	 * 
	 * @since 1.0.0
	 */
	private function wc_get_tax_amount($product_categories, $billing_state) {
		$tax_amount = 0;
		foreach ($product_categories as $product_category) {
			foreach ($this->wc_get_taxrates() as $category_name => $category_data) {
				if ($product_category === $category_name) {
					$tax_rate = get_object_vars($category_data->tax_rate)[$billing_state];
					$cost_of_goods = $category_data->cost_of_goods;
					$tax_amount += $cost_of_goods * $tax_rate;
				}
			}
		}
		return $tax_amount;
	}

	/**
	 * Adds the additional taxes defined in includes/taxes.json to the checkout in woocommerce
	 * 
	 * @since 1.0.0
	 */
	public function wc_add_custom_taxes() {
		global $woocommerce;
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		
		$billing_state = $woocommerce->customer->get_billing_state();
		if ($this->wc_is_taxable_state($billing_state)) {
			$cart_items = $woocommerce->cart->get_cart();
			$cart_product_categories = $this->wc_get_cart_product_categories($cart_items);
			$tax_amount = $this->wc_get_tax_amount($cart_product_categories, $billing_state);
			if ($tax_amount > 0) {
				$woocommerce->cart->add_fee( 'Additional Taxes', $tax_amount, false, '' );
			}
		}
	}
}
