<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://crestinfosystems.com
 * @since      1.0.0
 *
 * @package    Sales_Taxes_Per_Zip_Code
 * @subpackage Sales_Taxes_Per_Zip_Code/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sales_Taxes_Per_Zip_Code
 * @subpackage Sales_Taxes_Per_Zip_Code/public
 * @author     Crest Infosystems Pvt. Ltd <nikunj.h@crestinfosystems.net>
 */
class Sales_Taxes_Per_Zip_Code_Public {

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
		 * defined in Sales_Taxes_Per_Zip_Code_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sales_Taxes_Per_Zip_Code_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sales-taxes-per-zip-code-public.css', array(), $this->version, 'all' );

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
		 * defined in Sales_Taxes_Per_Zip_Code_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sales_Taxes_Per_Zip_Code_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sales-taxes-per-zip-code-public.js', array( 'jquery' ), $this->version, false );

	}
	public function woocommerce_find_rates( $matched_tax_rates, $args ) {

		if(Sales_Taxes_Per_Zip_Code::is_enable()) {
			$postalcode =$args["postcode"];
			if( isset($_REQUEST['s_postcode']) && !empty($_REQUEST['s_postcode'])) {
				$postalcode = $_REQUEST['s_postcode'];
			}
			$new_rates[1]["rate"] = $this->calculate_tax( $postalcode );
			$new_rates[1]["label"] = __("State Tax", "sales-taxes-per-zip-code");
			$new_rates[1]["shipping"] = "yes";
			$new_rates[1]["compound"] = "no";

			return $new_rates;
		}
		else{
			return $matched_tax_rates;
		}

	}

	public function calculate_tax( $postalcode = "") {
		$taxRate = 0;
		if( !empty($postalcode)) {
			if ( false === ( $taxRate = get_transient( 'us_taxes_'.$postalcode ) ) ) {
				$url = "https://u-s-a-sales-taxes-per-zip-code.p.rapidapi.com/".$postalcode;
				$apikey = get_option("us_tax_api", true);
				$curl = curl_init();
				curl_setopt_array($curl, [
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_HTTPHEADER => [
						"X-RapidAPI-Host: u-s-a-sales-taxes-per-zip-code.p.rapidapi.com",
						"X-RapidAPI-Key: ".$apikey
					],
				]);

				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
				$log_entry = "::: US Sales Taxes ::: \n";
				$log_entry .= "Request : \n Postalcode : ".$postalcode." \n";
				
				if ($err) {
					$log_entry .= "Message: \n cURL Error #:" . $err;
				} else {
					$log_entry .= "Message: \n cURL Success :".$response;
					$result =  json_decode($response, true);
					$taxRate = $result['state_rate'] * 100;
					Sales_Taxes_Per_Zip_Code::add_update_state_data( $postalcode, $result['state'], $taxRate);
				}
				$log_entry .= " \n";
				wc_get_logger()->notice( $log_entry, array( 'source' => 'Us-State-Tax-Log' ) );	
				set_transient( 'us_taxes_'.$postalcode, $taxRate, 7 * DAY_IN_SECONDS );
			}
		}
		return $taxRate;
	}

}
