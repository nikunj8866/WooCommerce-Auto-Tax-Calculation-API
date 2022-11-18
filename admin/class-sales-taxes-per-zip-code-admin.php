<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://crestinfosystems.com
 * @since      1.0.0
 *
 * @package    Sales_Taxes_Per_Zip_Code
 * @subpackage Sales_Taxes_Per_Zip_Code/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sales_Taxes_Per_Zip_Code
 * @subpackage Sales_Taxes_Per_Zip_Code/admin
 * @author     Crest Infosystems Pvt. Ltd <nikunj.h@crestinfosystems.net>
 */
class Sales_Taxes_Per_Zip_Code_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sales_Taxes_Per_Zip_Code_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sales_Taxes_Per_Zip_Code_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name.'-dataTables', plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sales-taxes-per-zip-code-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sales_Taxes_Per_Zip_Code_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sales_Taxes_Per_Zip_Code_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name.'-dataTables', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sales-taxes-per-zip-code-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'ajax_object',
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		)
	);


	}
	/*
	Add settings tab in the WooCommerce > Settings > Tax tab.
	*/
	public function tax_section_sales_taxes_section( $sections  ) {

		$sections['sales-taxes-settings'] = __( 'US Sales Taxes per Zip Code', 'sales-taxes-per-zip-code' );
		if(Sales_Taxes_Per_Zip_Code::is_enable()) {
			$sections['sales-taxes-data'] = __( 'Saved Postalcode Tax', 'sales-taxes-per-zip-code' );
		}
		return $sections;
	}

	public function woocommerce_admin_field_sales_taxes_data( $value ){
		include_once( plugin_dir_path( __FILE__ ) . 'partials/sales-taxes-per-zip-code-admin-display.php' );
			if(isset($_REQUEST['section']) && $_REQUEST['section'] == "sales-taxes-data" ) {
				?>
				<style>
					#mainform .woocommerce-save-button {
						display: none;
					}
				</style>
				<?php
			}
	}
	/*
	Add Fields for the WooCommerce > Settings > Tax > U.S.A Sales Taxes per Zip Code
	*/
	public function tax_section_sales_taxes_settings( $settings, $current_section  ) {

		/**
		 * Check the current section is what we want
		 **/
		if ( $current_section == 'sales-taxes-settings' ) {
			$settings_slider = array();
			
			$settings_slider[] = array( 'name' => __( 'US Sales Taxes per Zip Code', 'sales-taxes-per-zip-code' ), 'type' => 'title', 'id' => 'sales-taxes-settings' );
			
			$settings_slider[] = array(
				'name'     => __( 'Enable', 'sales-taxes-per-zip-code' ),
				'desc_tip' => __( 'This will enable tax calculation based on zip code', 'sales-taxes-per-zip-code' ),
				'id'       => 'enable_sales_tax_us',
				'type'     => 'checkbox',
				'css'      => 'min-width:300px;',
			);
			
			$settings_slider[] = array(
				'name'     => __( 'API Key', 'sales-taxes-per-zip-code' ),
				'desc_tip' => __( 'API key from <a href="https://rapidapi.com/perodriguezl/api/u-s-a-sales-taxes-per-zip-code" target="_blank">https://rapidapi.com/perodriguezl/api/u-s-a-sales-taxes-per-zip-code</a>', 'sales-taxes-per-zip-code' ),
				'id'       => 'us_tax_api',
				'type'     => 'text',
				'desc'     => __( 'Visit <a href="https://rapidapi.com/perodriguezl/api/u-s-a-sales-taxes-per-zip-code" target="_blank">https://rapidapi.com/perodriguezl/api/u-s-a-sales-taxes-per-zip-code</a> to genrate api key', 'sales-taxes-per-zip-code' ),
			);

			
			$settings_slider[] = array( 'type' => 'sectionend', 'id' => 'sales-taxes-settings' );
			return $settings_slider;
		}
		else if( $current_section == "sales-taxes-data"){
			$settings_slider[] = array(
				'type'     => 'sales-taxes-data',
				'id' => 'sales_taxes_data'
			);
			$settings_slider[] = array( 'type' => 'sectionend', 'id' => 'sales-taxes-data' );

			return $settings_slider;
		}
		else{
			/**
			 * If not, return the standard settings
			 **/
			return $settings;
		}
		return $settings;
	}

	public function resync_all_tax_rate() {
		global $wpdb;
		$sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
            FROM  $wpdb->options
            WHERE `option_name` LIKE '%transient_us_taxes_%'
            ORDER BY `option_name`";

   		$results = $wpdb->get_results( $sql );
		if(!empty( $results ))
		{
			foreach( $results as $result) {
				$key = str_replace( "_transient_", "",$result->name);
				delete_transient( $key );
			}
		}
		$table_name = $wpdb->prefix . 'us_state_tax';
		$wpdb->query("TRUNCATE TABLE $table_name");
		
		wp_send_json( array( "status" => "success", "data" => $results));
	}

	public function remove_postcode_tax() {
		$postalcode = $_REQUEST['postalcode'];
		if(!empty($postalcode)) {
			Sales_Taxes_Per_Zip_Code::remove_postcode_tax( $postalcode );
		}
		wp_send_json( array( "status" => "success"));
	}

	public function update_postcode_tax() {
		$postalcode = $_REQUEST['postalcode'];
		if(!empty($postalcode)) {
			delete_transient( 'us_taxes_'.$postalcode );
			$rate = Sales_Taxes_Per_Zip_Code_Public::calculate_tax( $postalcode );
		}
		wp_send_json( array( "status" => "success", "rate" => $rate));
	}

}
