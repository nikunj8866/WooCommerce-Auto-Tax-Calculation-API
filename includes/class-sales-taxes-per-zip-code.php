<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://crestinfosystems.com
 * @since      1.0.0
 *
 * @package    Sales_Taxes_Per_Zip_Code
 * @subpackage Sales_Taxes_Per_Zip_Code/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sales_Taxes_Per_Zip_Code
 * @subpackage Sales_Taxes_Per_Zip_Code/includes
 * @author     Crest Infosystems Pvt. Ltd <nikunj.h@crestinfosystems.net>
 */
class Sales_Taxes_Per_Zip_Code {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sales_Taxes_Per_Zip_Code_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SALES_TAXES_PER_ZIP_CODE_VERSION' ) ) {
			$this->version = SALES_TAXES_PER_ZIP_CODE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'sales-taxes-per-zip-code';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sales_Taxes_Per_Zip_Code_Loader. Orchestrates the hooks of the plugin.
	 * - Sales_Taxes_Per_Zip_Code_i18n. Defines internationalization functionality.
	 * - Sales_Taxes_Per_Zip_Code_Admin. Defines all hooks for the admin area.
	 * - Sales_Taxes_Per_Zip_Code_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sales-taxes-per-zip-code-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sales-taxes-per-zip-code-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sales-taxes-per-zip-code-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sales-taxes-per-zip-code-public.php';

		$this->loader = new Sales_Taxes_Per_Zip_Code_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sales_Taxes_Per_Zip_Code_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sales_Taxes_Per_Zip_Code_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Sales_Taxes_Per_Zip_Code_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_filter( 'woocommerce_get_sections_tax', $plugin_admin, 'tax_section_sales_taxes_section', 10, 1 );
		$this->loader->add_filter( 'woocommerce_get_settings_tax', $plugin_admin, 'tax_section_sales_taxes_settings', 10, 2 );
		$this->loader->add_action( 'woocommerce_admin_field_sales-taxes-data', $plugin_admin, 'woocommerce_admin_field_sales_taxes_data' );
		$this->loader->add_action( 'wp_ajax_resync_all_tax_rate', $plugin_admin, 'resync_all_tax_rate' );
		$this->loader->add_action( 'wp_ajax_remove_postcode_tax', $plugin_admin, 'remove_postcode_tax' );
		$this->loader->add_action( 'wp_ajax_update_postcode_tax', $plugin_admin, 'update_postcode_tax' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Sales_Taxes_Per_Zip_Code_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter( 'woocommerce_find_rates', $plugin_public, 'woocommerce_find_rates', 10, 2 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sales_Taxes_Per_Zip_Code_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function is_enable() {
		$enable_sales_tax_us = get_option('enable_sales_tax_us', true);
		if($enable_sales_tax_us == "yes"){
			return true;
		}
		else{
			return false;
		}
	}

	public function add_update_state_data( $postalcode, $state_code, $tax_rate) {
		if( !empty($state_code) && !empty($tax_rate) && !empty( $postalcode )) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'us_state_tax';

			$sql = "SELECT * FROM  $table_name WHERE `postalcode` = $postalcode ";
			$results = $wpdb->get_results( $sql );
			
			$state_name = WC()->countries->get_states( 'US' )[$state_code];
			if(!empty( $results ))
			{
				foreach( $results as $result) {
					$wpdb->update( $table_name, 
								   array('tax_rate' => $tax_rate ),
								   array('id' => $result->id )
								);
				}
			}
			else{
				$wpdb->insert( $table_name, array(
					'us_state_code' => $state_code,
					'us_state' => $state_name,
					'postalcode' => $postalcode,
					'tax_rate' => $tax_rate
				));
			}
		}
	}

	public function remove_postcode_tax( $postalcode ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'us_state_tax';
		$wpdb->query("DELETE FROM $table_name WHERE `postalcode` = '$postalcode'");
		delete_transient( 'us_taxes_'.$postalcode );
	}

}
