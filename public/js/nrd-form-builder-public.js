(function ($) {
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
			$(document).ready(function () {
				// render the form
				if (typeof formRenderData !== 'undefined') {
					console.log('formRenderData', formRenderData);
					$('#fb-rendered-form').formRender({
						formData: formRenderData
					});
				}
			})

			var formRenderContainer = $('#fb-rendered-form');
			var formRenderContainer = $('#fb-rendered-form');
			formRenderContainer.on('submit', function (event) {
				event.preventDefault(); // Prevent the default form submission

				// Create a FormData object to capture all form data including files
				var formData = new FormData(this);

				// Append additional data
				formData.append('action', 'post_nrd_wp_fb');
				formData.append('google_sheet_id', $('#google_sheet_id').val());
				formData.append('google_sheet_page', $('#google_sheet_page').val());
				formData.append('form_title', $('#form_title').val());

				var submitButton = formRenderContainer.find('button[type="submit"]');
				submitButton.prop('disabled', true).text('Loading...');

				postNrdFb(formData, submitButton);
			});

			function postNrdFb(data, submitButton) {
				// console.log(data);
				$('.nrd-form-bd-message').remove();
				$.ajax({
					data: data,
					type: 'POST',
					url: my_ajax_object.ajax_url,
					processData: false, // Prevent jQuery from processing the data
					contentType: false, // Prevent jQuery from setting the content type
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

})(jQuery);
