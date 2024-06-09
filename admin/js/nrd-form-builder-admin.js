(function ($) {
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
	$(window).load(function () {
		jQuery(function ($) {
			const nrdActivateLicenserAPIUrl = 'http://127.0.0.1:8000/api/activate-license';
			const nrdDeactivateLicenserAPIUrl = 'http://127.0.0.1:8000/api/deactivate-license';

			var $fbEditor = $(document.getElementById('fb-editor')),
				$formContainer = $(document.getElementById('fb-rendered-form')),
				fbOptions = {
					onSave: function () {
						let saveBtn = $('.save-template')
						// $fbEditor.toggle();
						// $formContainer.toggle();
						$('form', $formContainer).formRender({
							formData: formBuilder.formData
						});
						var postIdInput = document.getElementById('post_id');
						var postId = postIdInput ? postIdInput.value : null;
						console.log(formBuilder.formData, postId);
						saveNrdFb(formBuilder.formData, postId, saveBtn);
						// make the clicked save button disabled
						
					}
				};

			// Check if formData is defined
			if (typeof formData !== 'undefined') {
				// Add formData to fbOptions
				fbOptions.formData = formData;
			}

			var formBuilder = $fbEditor.formBuilder(fbOptions);

			$('.edit-form', $formContainer).click(function () {
				$fbEditor.toggle();
				$formContainer.toggle();
			});

			function saveNrdFb(data, postID, saveBtn) {
				saveBtn.prop('disabled', true);
				saveBtn.text('Saving...');
				let title = document.getElementById('title').value;
				let googleSheetID = document.getElementById('google_sheet_id') ? document.getElementById('google_sheet_id').value : '';
				if (title == '') {
					title = 'Untitled Form';
				}
				$.ajax({
					data: { action: 'save_nrd_wp_fb', title: title, content: data, post_id: postID, google_sheet_id: googleSheetID },
					type: 'post',
					url: ajaxurl,
					success: function (data) {
						console.log(data.data); //should print out the name since you sent it along
						let editUrl = data.data;
						window.location.href = editUrl;

					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log(errorThrown);
					}
				});
			}

			$('.nrd-short-code').click(function () {
				const postID = $('#post_id').val();
				const customerLink = '[nrd_form_bd id="' + postID + '"]';
    			navigator.clipboard.writeText(customerLink);
				$('.nrd-short-code-copy').show();
				// hide the copy button after 3 seconds
				setTimeout(function() {
					$('.nrd-short-code-copy').hide();
				}, 1500);
			})

			// activate license
			$('#nrd-form-bd-activate-button').click(function () {
				const licenseKey = $('#nrd-form-bd-license-key').val();
				$('#nrd-form-bd-activate-button').prop('disabled', true);
				$('#nrd-form-bd-activate-button').text('Activating...');
				$('.nrd-form-bd-message').remove();
				var form = new FormData();
				// form.append("key", "A3D697Q9WWJhFS4TlkvnmpXLY");
				form.append("key", licenseKey);
				form.append("email", "eddallal.noureddine@gmail.com");
				form.append("domain", "www.zenappoint.com");

				var settings = {
				"url": nrdActivateLicenserAPIUrl,
				"method": "POST",
				"timeout": 0,
				"headers": {
					"Authorization": "Bearer 3|7LDk8Aopn8eaIchFfBkvOD500miXLhzVOtVBrdfYe3487bf9",
					"Cookie": "XSRF-TOKEN=eyJpdiI6ImxJcUEwelgweDhaZlBzU1BkQkZtSVE9PSIsInZhbHVlIjoiaU5XQ3ZEc3J0c1NVY2YzU0pHUERhWGRUTU5abFZxa0RtaGlTM1laeXpTNFcwMHpCaFA5RXN3RVNkdm5GU1lrQVVpYlVhclA4MHAyaGVMQy9tSWZwajU2VExxRzFQRG15Qjc3OU5lUEVsN2tUY0pISktYRzZwZWhYN3JnY2VtVGMiLCJtYWMiOiJmYjhkZTk2Nzc1NTA3Mzc0NjJlODcyZGNhNTcxODFjMjI2NWNiY2JmOThhNjQ0OTNjODBkNGYzZTgyNjgxOTI2IiwidGFnIjoiIn0%3D; laravel_session=eyJpdiI6IjBVeU5OK3JMTmpEVXVtU1pBYVR2dUE9PSIsInZhbHVlIjoiWlFxYzR1RWlidUxPT2xDdnA3RURZUkM5ZzdpZ1dTMlJVTVN2MVpxbitMazlrWXppaFFGa0tON3BWN1lUNTVzeVZUR0VIRHdhV1E0Y2RMWDNRS1ZjNTlwOWtKYW81cDhtQU9yaDF1cTRUWUNxWEhhN2ZOb0pIcU9jR2tFTGtxZHMiLCJtYWMiOiJjMjUwMDc4Y2M3MDlkN2M3NjIzZjk5ZmZjZThiMTgzODlkOWVlODFiMzgyZjdkZGY0N2FmMzI1OThhOWNiY2ZiIiwidGFnIjoiIn0%3D"
				},
				"processData": false,
				"mimeType": "multipart/form-data",
				"contentType": false,
				"data": form
				};

				$.ajax(settings).done(function (response) {
					// convert response to json
					response = JSON.parse(response);
					console.log(response[0])
					const status = response[0];
					if(status == "invalid"){
						$('#nrd-form-bd-activate-button').prop('disabled', false);
						$('#nrd-form-bd-activate-button').text('Activate');
						// append error message after #nrd-form-bd-activate-button
						$('<div class="nrd-form-bd-message nrd-form-bd-error-message">Invalid license key</div>').insertAfter($('#nrd-form-bd-activate-button'));
					}

					if(status == "active"){
						$.ajax({
							data: { action: 'save_nrd_license_response', status: status, license_key: licenseKey },
							type: 'post',
							url: ajaxurl,
							success: function (data) {
								console.log(data); //should print out the name since you sent it along
								window.location.reload();
							},
							error: function (jqXHR, textStatus, errorThrown) {
								console.log(errorThrown);
							}
						})
					}
					else if(status == "already_active"){
						$('#nrd-form-bd-activate-button').prop('disabled', false);
						$('#nrd-form-bd-activate-button').text('Activate');
						// append error message after #nrd-form-bd-activate-button
						$('<div class="nrd-form-bd-message nrd-form-bd-error-message">This license key is already active on another website.</div>').insertAfter($('#nrd-form-bd-activate-button'));
					}
					else{
						$('<div class="nrd-form-bd-message nrd-form-bd-error-message">This license key is suspended</div>').insertAfter($('#nrd-form-bd-activate-button'));
					}
				});
			})

			// deactivate license
			$('#nrd-form-bd-deactivate-button').click(function () {
				const licenseKey = $('#nrd-form-bd-license-key').val();

				$('#nrd-form-bd-deactivate-button').prop('disabled', true);
				$('#nrd-form-bd-deactivate-button').text('Deactivating...');
				var form = new FormData();
				// form.append("key", "A3D697Q9WWJhFS4TlkvnmpXLY");
				form.append("key", licenseKey);

				var settings = {
				"url": nrdDeactivateLicenserAPIUrl,
				"method": "POST",
				"timeout": 0,
				"headers": {
					"Authorization": "Bearer 3|7LDk8Aopn8eaIchFfBkvOD500miXLhzVOtVBrdfYe3487bf9"
				},
				"processData": false,
				"mimeType": "multipart/form-data",
				"contentType": false,
				"data": form
				};

				$.ajax(settings).done(function (response) {
					response = JSON.parse(response);
					console.log(response[0])
					const status = response[0];
					if(status == "invalid"){
						$('#nrd-form-bd-deactivate-button').prop('disabled', false);
						$('#nrd-form-bd-deactivate-button').text('Activate');
						// append error message after #nrd-form-bd-deactivate-button
						$('<div class="nrd-form-bd-message nrd-form-bd-error-message">Invalid license key</div>').insertAfter($('#nrd-form-bd-deactivate-button'));
					}

					if(status == "inactive"){
						$.ajax({
							data: { action: 'save_nrd_license_response', status: status, license_key: licenseKey },
							type: 'post',
							url: ajaxurl,
							success: function (data) {
								//reload page
								window.location.reload();
							},
							error: function (jqXHR, textStatus, errorThrown) {
								console.log(errorThrown);
							}
						})
					}
				});
			})
		});
	})

})(jQuery);
