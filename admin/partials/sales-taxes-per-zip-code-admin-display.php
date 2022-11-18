<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://crestinfosystems.com
 * @since      1.0.0
 *
 * @package    Sales_Taxes_Per_Zip_Code
 * @subpackage Sales_Taxes_Per_Zip_Code/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'us_state_tax';

$sql = "SELECT * FROM  $table_name order by `us_state` ASC";
$results = $wpdb->get_results( $sql );
if(!empty($results)) {
    ?>
    <table class="tax-postalcode-table">
    <thead>
        <tr>
            <th>State</th>
            <th>Postalcode</td>
            <th>Rate</td>
            <th>Actions</td>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach($results as $result) {
    ?>
    <tr>
        <td><?php echo $result->us_state; ?></td>
        <td><?php echo $result->postalcode; ?></td>
        <td><?php echo $result->tax_rate; ?></td>
        <td>
            <a href="javascript:void(0)" title="Update Postcode Rate" class="update-postalcode-rate" data-id="<?php echo $result->postalcode; ?>"><span class="dashicons dashicons-update"></span></a>
            <a href="javascript:void(0)" title="Remove" class="remove-postalcode-tax" data-id="<?php echo $result->postalcode; ?>"><span class="dashicons dashicons-trash"></span></a>
        </td>
    </tr>
    <?php
    }
    ?>
    </tbody>
    </table>
    <?php
}
?>