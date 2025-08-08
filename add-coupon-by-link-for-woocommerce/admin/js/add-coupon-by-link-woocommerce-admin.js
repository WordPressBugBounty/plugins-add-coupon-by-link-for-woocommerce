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
	jQuery(function ($) {
		var url = jQuery("#pi-qr-code").data('qr-code-url');
		if (url) {
			new QRCode(document.getElementById("pi-qr-code"), url);
			jQuery("#pi-qr-code-download").fadeIn()
		}

		jQuery("#pi-qr-code-download").on('click', function () {
			var data = jQuery("#pi-qr-code img").attr('src');
			downloadURI(data, 'Coupon Qr Code');
		});

		function downloadURI(uri, name) {
			var link = document.createElement("a");
			link.download = name;
			link.href = uri;
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
			link = null;
		}

		function shipping_discount() {
			jQuery("#pi_acblw_shipping_discount_method").on('change', function () {
				var discount_type = jQuery(this).val();
				if (discount_type == 'all') {
					jQuery("#all-shipping-method-discounted").fadeIn();
				} else {
					jQuery("#all-shipping-method-discounted").fadeOut();
				}
			}
			);
			jQuery("#pi_acblw_shipping_discount_method").trigger('change');	
		}
		shipping_discount();

		const $select = $('.pi-acblw-search-product');
		product_search($select);

		function restrict_to_one_product(){
			const count = $('.pi-aclw-add-products-row').length;
			if (count >= 1) {
				$('#pi-aclw-add-product-button').prop('disabled', true);
				$('#pi-aclw-add-product-pro-message').show();
			}else{
				$('#pi-aclw-add-product-button').prop('disabled', false);
				$('#pi-aclw-add-product-pro-message').hide();
			}
		}

		restrict_to_one_product();
		function add_product(){
			$(document).on('click', '.remove-product-button', function() {
				$(this).closest('.pi-aclw-add-products-row').remove();
				restrict_to_one_product()
			});

			$('#pi-aclw-add-product-button').on('click', function() {
				const uniqueKey = Date.now(); // use timestamp

				// Fetch template and replace placeholder
				const template = $('#add-product-fields-template').html().replace(/{{index}}/g, uniqueKey);

				$('#add-product-fields-container').append(template);
				$('.pi-aclw-discount-type').trigger('change');

				restrict_to_one_product();

				const $select = $(`select[name="products[${uniqueKey}][product_id]"]`);

				product_search($select);
			});

			$(document).on('change', '.pi-aclw-discount-type', function() {
				const $this = $(this);
				const value = $this.val();
				if (value === 'no-discount') {
					$this.closest('.pi-aclw-add-products-row').find('.pi-aclw-product-discount-amount').hide();
				} else {
					$this.closest('.pi-aclw-add-products-row').find('.pi-aclw-product-discount-amount').show();
				}
			});
			$('.pi-aclw-discount-type').trigger('change'); // Trigger change for existing rows
		}
		add_product();

		function product_search($select){
			if ($select.length) {
					$select.selectWoo({
						allowClear: true,
						minimumInputLength: 3,
						ajax: {
							url: ajaxurl,
							dataType: 'json',
							delay: 250,
							data: function (params) {
								return {
									term: params.term,
									action: 'pi_aclw_search_simple_products',
									security: wc_product_search_params.search_nonce
								};
							},
							processResults: function (data) {
								// Convert object to array
								const results = Object.entries(data).map(([id, text]) => ({
									id: id,
									text: text
								}));
								return {
									results: results
								};
							},
							cache: true
						}
					});
				}
		}

	});
})(jQuery);
