<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://crestinfosystems.com
 * @since             1.0.0
 * @package           Sales_Taxes_Per_Zip_Code
 *
 * @wordpress-plugin
 * Plugin Name:       US Sales Taxes per Zip Code
 * Plugin URI:        https://crestinfosystems.com
 * Description:       United states of America sales taxes rates per zip/postal codes using https://rapidapi.com/perodriguezl/api/u-s-a-sales-taxes-per-zip-code api.
 * Version:           1.0.0
 * Author:            Crest Infosystems Pvt. Ltd
 * Author URI:        https://crestinfosystems.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sales-taxes-per-zip-code
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SALES_TAXES_PER_ZIP_CODE_VERSION', '1.0.0' );

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'sales_taxes_settings_link');
function sales_taxes_settings_link( $links ) {

    $url = add_query_arg( array(
        'page' => 'wc-settings',
		'tab' => 'tax',
		'section' => 'sales-taxes-settings'
    ), get_admin_url() . 'admin.php' );

	$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
	
	array_unshift(
		$links,
		$settings_link
	);
	return $links;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sales-taxes-per-zip-code-activator.php
 */
function activate_sales_taxes_per_zip_code() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sales-taxes-per-zip-code-activator.php';
	Sales_Taxes_Per_Zip_Code_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sales-taxes-per-zip-code-deactivator.php
 */
function deactivate_sales_taxes_per_zip_code() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sales-taxes-per-zip-code-deactivator.php';
	Sales_Taxes_Per_Zip_Code_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sales_taxes_per_zip_code' );
register_deactivation_hook( __FILE__, 'deactivate_sales_taxes_per_zip_code' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sales-taxes-per-zip-code.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sales_taxes_per_zip_code() {

	$plugin = new Sales_Taxes_Per_Zip_Code();
	$plugin->run();

}
run_sales_taxes_per_zip_code();
