<?php

/**
 * Fired during plugin activation
 *
 * @link       https://crestinfosystems.com
 * @since      1.0.0
 *
 * @package    Sales_Taxes_Per_Zip_Code
 * @subpackage Sales_Taxes_Per_Zip_Code/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sales_Taxes_Per_Zip_Code
 * @subpackage Sales_Taxes_Per_Zip_Code/includes
 * @author     Crest Infosystems Pvt. Ltd <nikunj.h@crestinfosystems.net>
 */
class Sales_Taxes_Per_Zip_Code_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'us_state_tax';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			us_state_code varchar(255) DEFAULT '' NOT NULL,
			us_state varchar(255) DEFAULT '' NOT NULL,
			postalcode varchar(255) DEFAULT '' NOT NULL,
			tax_rate varchar(255) DEFAULT '' NOT NULL,
			tax_date DATETIME DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
