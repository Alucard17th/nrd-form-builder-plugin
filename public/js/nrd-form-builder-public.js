(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

	$(window).load(function () {
		jQuery(function ($) {
			$(document).ready(function() {
				// render the form
				if (typeof formData !== 'undefined') {
					console.log('formData', formData);
					$('#fb-rendered-form').formRender({
						formData: formData
					});
				}
			})

			var formRenderContainer = $('#fb-rendered-form');
			formRenderContainer.on('submit', function(event) {
				event.preventDefault(); // Prevent the default form submission
				// get form data using jQuery serialize
				var formData = formRenderContainer.serialize();
				var submitButton = formRenderContainer.find('button[type="submit"]');
    			submitButton.prop('disabled', true).text('Loading...');

				var google_sheet_id = $('#google_sheet_id').val();
				postNrdFb(formData, submitButton, google_sheet_id);
			});

			function postNrdFb(data, submitButton, google_sheet_id) {
				// console.log(data);
				$('.nrd-form-bd-message').remove();
				$.ajax({
					data: { action: 'post_nrd_wp_fb', data: data, google_sheet_id: google_sheet_id },
					type: 'post',
					url: my_ajax_object.ajax_url,
					success: function (data) {
						submitButton.prop('disabled', false).text('Submit');
						$('<div class="nrd-form-bd-message nrd-form-bd-success-message">Form submitted successfully!</div>').insertAfter(submitButton);
					},
					error: function (jqXHR, textStatus, errorThrown) {
						submitButton.prop('disabled', false).text('Submit');
						$('<div class="nrd-form-bd-message nrd-form-bd-error-message">An error occurred while submitting the form. Please try again later.</div>').insertAfter(submitButton);
					}
				});
			}
		});
	});

})( jQuery );
