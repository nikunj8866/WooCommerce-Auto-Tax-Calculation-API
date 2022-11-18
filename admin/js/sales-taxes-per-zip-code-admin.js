(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function(){

		$(document).on("click", '#resync-tax-rate', function(){
			var $thisButton = $(this);
			$.ajax({
				url: ajax_object.ajaxurl,
				type: 'post',
				data: {
					'action':'resync_all_tax_rate',
				},
				beforeSend: function(){
					$thisButton.addClass("processing")
				},
				success: function( response ) {
					$thisButton.removeClass("processing");
					alert("Success! Taxes has been resynced.")
				},
				error: function(){
					alert("Opps! Something went wrong. Please try again later!")
					$thisButton.removeClass("processing")
				}
			});
		});

		var table = $('.tax-postalcode-table').DataTable( {
			responsive: true,
		} );

		$(document).on("click", ".remove-postalcode-tax", function(){
			if( confirm("Are you sure to remove?") == true) {
				var postalcode = $(this).data("id");
				$.ajax({
					url: ajax_object.ajaxurl,
					type: 'post',
					data: {
						'action':'remove_postcode_tax',
						'postalcode' : postalcode
					},
					success: function( response ) {
					},
					error: function(){
						alert("Opps! Something went wrong. Please try again later!")
					}
				});
				table
				.row( $(this).parents('tr') )
				.remove()
				.draw();
			}
		});

		$(document).on("click", ".update-postalcode-rate", function(){
			var $td = $(this).parents("tr").find("td:eq(2)");
			var $iconSpan = $(this).find("span");
			var postalcode = $(this).data("id");
			$.ajax({
				url: ajax_object.ajaxurl,
				type: 'post',
				data: {
					'action':'update_postcode_tax',
					'postalcode' : postalcode
				},
				beforeSend: function(){
					$iconSpan.addClass("processing")
				},
				success: function( response ) {
					if(response.status == "success"){
						$td.html(response.rate)
					}
					$iconSpan.removeClass("processing")
				},
				error: function(){
					alert("Opps! Something went wrong. Please try again later!")
					$iconSpan.removeClass("processing")
				}
			});
		});

	})
})( jQuery );
