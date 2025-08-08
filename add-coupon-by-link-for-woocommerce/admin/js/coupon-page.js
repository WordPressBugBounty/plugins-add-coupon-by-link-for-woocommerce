(function ($) {
	'use strict';
    jQuery(document).ready(function($) {
        function dynamicFields() {
            jQuery(".pisol-attribute").selectWoo({
                width: '100%',
                closeOnSelect: false,
                placeholder: 'Select an attribute',
                cache: true,
                ajax: {
                    url: window.ajaxurl,
                    dataType: 'json',
                    type: "GET",
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term,
                            action: "pisol_get_attribute",
                            _nonce: jQuery(this).data('nonce')
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };

                    },
                },
                minimumInputLength: 3
            });
        }
        dynamicFields();

        jQuery(document).on('click', '#send_store_credit_email', function(e) {
            e.preventDefault();
            var href = jQuery(this).attr('href');
            jQuery.ajax(
                {
                    url: href,
                    type: 'GET',
                    success: function (response) {

                        alert(response.data);
                    }
                }
            )
        });

        function optionEnabled($parent, $container){

            jQuery(document).on('change', $parent, function(e) {
                if (jQuery(this).is(':checked')) {
                    jQuery($container).show();
                } else {
                    jQuery($container).hide();
                }
            });
            jQuery($parent).trigger('change');
        }

        optionEnabled('#pisol_aclw_day_based_scheduling_enabled', '#pi-day-based-scheduling-container');
        optionEnabled('#pisol_aclw_date_based_scheduling_enabled', '#pi-date-based-scheduling-container');

        function dateRange(){
        
            this.init = function(){
                this.count = jQuery('.pi-date-schedules .pisol-date-range').length;
                this.bindEvents();
                this.flatpickr();
            }

            this.bindEvents = function(){
                var self = this;
                jQuery(document).on('click', '#pi-add-date-schedule', function(e){
                    e.preventDefault();
                    self.addDateRange();
                });

                jQuery(document).on('click', '.pisol-date-schedule-remove', function(e){
                    e.preventDefault();
                    self.removeDateRange(this);
                });
            }

            this.removeDateRange = function(el){
                jQuery(el).closest('.pisol-date-range').remove();
            }

            this.addDateRange = function(){
                var template = jQuery('#date-schedule-row-template').html();

                //remove {count} from template
                template = template.replace(/{count}/g, this.count);
                jQuery('.pi-date-schedules').append(template);
                this.flatpickr();
                this.count++;
            }

            this.flatpickr = function(){
                flatpickr(".pi-date-time", {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    time_24hr: true // Use 24-hour format
                });
            }
        }

        var dateRange = new dateRange();
        dateRange.init();
    });



})(jQuery);