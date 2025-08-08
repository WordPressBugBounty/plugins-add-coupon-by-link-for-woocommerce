/**
 * JavaScript for handling dynamic condition form with groups
 */
(function($) {
    'use strict';
    
    var PISOL_ACLW_Conditions = {
        init: function() {
            this.bindEvents();
            this.initExistingConditions();
            this.initSelectWooFields();
            this.initTimePeriodFields();
            this.initTaxonomyFields();
            this.initMetaDataFields();
        },
        
        bindEvents: function() {
            // Group related events
            $(document).on('click', '.pisol-aclw-add-group', this.addGroup);
            $(document).on('click', '.pisol-aclw-remove-group', this.removeGroup);
            $(document).on('click', '.pisol-aclw-add-condition-to-group', this.addConditionToGroup);
            
            // Condition related events
            $(document).on('click', '.pisol-aclw-remove-condition', this.removeCondition);
            $(document).on('change', '.pisol-aclw-condition-type', this.handleConditionTypeChange);
            $(document).on('change', '.pisol-aclw-taxonomy-select', this.handleTaxonomyChange);
            $(document).on('change', '.pisol-aclw-data-type-select', this.handleDataTypeChange);
        },
        
        initExistingConditions: function() {
            // Make sure any existing conditions are initialized properly
            $('.pisol-aclw-condition-type').each(function() {
                if ($(this).val()) {
                    var $condition = $(this).closest('.pisol-aclw-condition');
                    var $conditionData = $condition.find('.pisol-aclw-condition-data');
                    
                    // Store any existing values from data attributes for debugging
                    console.log('Initializing condition:', {
                        conditionId: $condition.data('condition-id'),
                        type: $conditionData.data('condition-type'),
                        operator: $conditionData.data('condition-operator'),
                        value: $conditionData.data('condition-value')
                    });
                    
                    // Now trigger the change to load the operator and value fields
                    $(this).trigger('change');
                }
            });
        },
        
        // Initialize SelectWoo on all select fields that need it
        initSelectWooFields: function() {
            // Initialize SelectWoo on country select fields
            $('.pisol-aclw-multi-select').each(function() {
                if (!$(this).data('select2')) {
                    $(this).selectWoo();
                }
            });
            
            // Initialize WooCommerce enhanced select for product search
            $(document.body).trigger('wc-enhanced-select-init');
            
            // Handle dynamic fields loaded via AJAX
            $(document).on('pisol_aclw_condition_fields_loaded', function() {
                $('.pisol-aclw-multi-select').each(function() {
                    if (!$(this).data('select2')) {
                        $(this).selectWoo();
                    }
                });
                
                // Also re-initialize WooCommerce enhanced select for product search when fields are loaded
                $(document.body).trigger('wc-enhanced-select-init');
            });
        },
        
        // Initialize time period fields for the Previous Orders By Category condition
        initTimePeriodFields: function() {
            $(document).on('change', '.pisol-aclw-time-period-select', function() {
                var timePeriod = $(this).val();
                if (timePeriod === 'custom_days') {
                    $(this).closest('.pisol-aclw-custom-days-wrapper').find('.pisol-aclw-custom-days').show();
                } else {
                    $(this).closest('.pisol-aclw-custom-days-wrapper').find('.pisol-aclw-custom-days').hide();
                }
            });
            
            // Initialize existing time period selects when loaded via AJAX
            $(document).on('pisol_aclw_condition_fields_loaded', function() {
                $('.pisol-aclw-time-period-select').each(function() {
                    var timePeriod = $(this).val();
                    if (timePeriod === 'custom_days') {
                        $(this).closest('.pisol-aclw-custom-days-wrapper').find('.pisol-aclw-custom-days').show();
                    } else {
                        $(this).closest('.pisol-aclw-custom-days-wrapper').find('.pisol-aclw-custom-days').hide();
                    }
                });
            });
        },
        
        // Initialize taxonomy select fields for the Custom Product Taxonomy condition
        initTaxonomyFields: function() {
            // Initialize on page load and when condition fields are loaded
            $(document).on('pisol_aclw_condition_fields_loaded', function() {
                if ($('.pisol-aclw-taxonomy-select').length) {
                    $('.pisol-aclw-taxonomy-select').each(function() {
                        // Trigger change if value is already set
                        if ($(this).val()) {
                            $(this).trigger('change');
                        }
                    });
                }
            });
        },
        
        // Initialize product meta data fields
        initMetaDataFields: function() {
            // Initialize meta data type fields when loaded via AJAX
            $(document).on('pisol_aclw_condition_fields_loaded', function() {
                $('.pisol-aclw-data-type-select').each(function() {
                    var conditionId = $(this).data('condition-id');
                    var dataType = $(this).val();
                    var valueInput = $('#pisol_aclw_meta_value_' + conditionId);
                    
                    if (dataType === 'number') {
                        valueInput.attr('type', 'number');
                        valueInput.attr('step', 'any');
                    } else {
                        valueInput.attr('type', 'text');
                        valueInput.removeAttr('step');
                    }
                });
            });
        },
        
        // Add a new condition group
        addGroup: function(e) {
            e.preventDefault();
            
            var groupId = new Date().getTime();
            var $groupsList = $('#pisol-aclw-groups-list');
            var html = '';
            
            // Add logic divider if this is not the first group
            if ($groupsList.children('.pisol-aclw-group').length > 0) {
                if (typeof wp !== 'undefined' && wp.template) {
                    var dividerTemplate = wp.template('pisol-aclw-group-logic-divider');
                    html += dividerTemplate({ groupId: groupId });
                } else {
                    html += PISOL_ACLW_Conditions.generateGroupLogicDividerHTML(groupId);
                }
            }
            
            // Add the group
            if (typeof wp !== 'undefined' && wp.template) {
                var groupTemplate = wp.template('pisol-aclw-group');
                html += groupTemplate({ groupId: groupId });
            } else {
                html += PISOL_ACLW_Conditions.generateGroupHTML(groupId);
            }
            
            $groupsList.append(html);
            
            // Add a condition to the new group
            var $newGroup = $groupsList.find('.pisol-aclw-group[data-group-id="' + groupId + '"]');
            $newGroup.find('.pisol-aclw-add-condition-to-group').trigger('click');
        },
        
        // Remove a condition group
        removeGroup: function(e) {
            e.preventDefault();
            
            var $group = $(this).closest('.pisol-aclw-group');
            var $logicDivider = $group.prev('.pisol-aclw-group-logic-divider');
            
            // If this is the first group and there's more than one, remove the next logic divider
            if ($group.prev('.pisol-aclw-group-logic-divider').length === 0 && $group.next('.pisol-aclw-group-logic-divider').length > 0) {
                $group.next('.pisol-aclw-group-logic-divider').remove();
            } else if ($logicDivider.length > 0) {
                // Otherwise remove the previous logic divider
                $logicDivider.remove();
            }
            
            // Remove the group
            $group.remove();
        },
        
        // Add a condition to a specific group
        addConditionToGroup: function(e) {
            e.preventDefault();
            
            var $group = $(this).closest('.pisol-aclw-group');
            var groupId = $group.data('group-id');
            var $conditionsList = $group.find('.pisol-aclw-conditions-list');
            var conditionId = new Date().getTime();
            var html = '';
            
            // Add the condition (without any logic divider)
            if (typeof wp !== 'undefined' && wp.template) {
                var conditionTemplate = wp.template('pisol-aclw-condition');
                html += conditionTemplate({ groupId: groupId, conditionId: conditionId });
            } else {
                html += PISOL_ACLW_Conditions.generateConditionHTML(groupId, conditionId);
            }
            
            $conditionsList.append(html);
        },
        
        // Remove a condition from a group
        removeCondition: function(e) {
            e.preventDefault();
            
            var $condition = $(this).closest('.pisol-aclw-condition');
            
            // Simply remove the condition (no need to handle logic dividers anymore)
            $condition.remove();
        },
        
        // Generate HTML for a new group (fallback without wp.template)
        generateGroupHTML: function(groupId) {
            var html = '<div class="pisol-aclw-group" data-group-id="' + groupId + '">';
            html += '<div class="pisol-aclw-group-header">';
            html += '<div class="pisol-aclw-group-title">Condition Group</div>';
            html += '<div class="pisol-aclw-group-match-type">';
            html += '<select name="pisol_aclw_conditions[' + groupId + '][match_type]" class="pisol-aclw-match-type">';
            html += '<option value="all">All conditions match</option>';
            html += '<option value="any">Any condition matches</option>';
            html += '</select>';
            html += '</div>';
            html += '<div class="pisol-aclw-group-actions">';
            html += '<button type="button" class="button pisol-aclw-add-condition-to-group">Add Condition</button>';
            html += '<button type="button" class="button pisol-aclw-remove-group">Remove Group</button>';
            html += '</div>';
            html += '</div>';
            html += '<div class="pisol-aclw-group-content">';
            html += '<div class="pisol-aclw-conditions-list"></div>';
            html += '</div>';
            html += '</div>';
            
            return html;
        },
        
        // Generate HTML for a group logic divider (fallback without wp.template)
        generateGroupLogicDividerHTML: function(groupId) {
            return '<div class="pisol-aclw-group-logic-divider">' +
                '<select name="pisol_aclw_conditions[' + groupId + '][logic]" class="pisol-aclw-group-logic">' +
                '<option value="AND">AND</option>' +
                '<option value="OR">OR</option>' +
                '</select>' +
                '</div>';
        },
        
        // Generate HTML for a new condition (fallback without wp.template)
        generateConditionHTML: function(groupId, conditionId) {
            var html = '<div class="pisol-aclw-condition" data-condition-id="' + conditionId + '">';
            html += '<div class="pisol-aclw-condition-content">';
            html += '<select name="pisol_aclw_conditions[' + groupId + '][conditions][' + conditionId + '][type]" class="pisol-aclw-condition-type">';
            html += '<option value="">Select Condition</option>';
            
            // Add condition types if available in the localized data
            if (pisol_aclw_conditions && pisol_aclw_conditions.condition_types) {
                for (var type in pisol_aclw_conditions.condition_types) {
                    html += '<option value="' + type + '">' + pisol_aclw_conditions.condition_types[type] + '</option>';
                }
            }
            
            html += '</select>';
            html += '<div class="pisol-aclw-condition-details" style="display:none;">';
            html += '<div class="pisol-aclw-operator-container"></div>';
            html += '<div class="pisol-aclw-value-container"></div>';
            html += '</div>';
            html += '<button type="button" class="button pisol-aclw-remove-condition">Remove</button>';
            html += '</div>';
            html += '</div>';
            
            return html;
        },
        
        // Generate HTML for a condition logic divider (fallback without wp.template)
        generateLogicDividerHTML: function(groupId, conditionId) {
            return '<div class="pisol-aclw-logic-divider">' +
                '<select name="pisol_aclw_conditions[' + groupId + '][conditions][' + conditionId + '][logic]" class="pisol-aclw-logic">' +
                '<option value="AND">AND</option>' +
                '<option value="OR">OR</option>' +
                '</select>' +
                '</div>';
        },
        
        // Handle condition type change to load operators and value fields
        handleConditionTypeChange: function() {
            var $condition = $(this).closest('.pisol-aclw-condition');
            var $group = $(this).closest('.pisol-aclw-group');
            var $details = $condition.find('.pisol-aclw-condition-details');
            var $operatorContainer = $condition.find('.pisol-aclw-operator-container');
            var $valueContainer = $condition.find('.pisol-aclw-value-container');
            var $conditionData = $condition.find('.pisol-aclw-condition-data');
            var conditionType = $(this).val();
            var conditionId = $condition.data('condition-id');
            var groupId = $group.data('group-id');
            
            // Get existing values from data attributes
            var currentOperator = $conditionData.data('condition-operator') || '';
            var currentValue = $conditionData.data('condition-value') || '';
            
            // Hide details if no condition type selected
            if (!conditionType) {
                $details.hide();
                return;
            }
            
            // Show loading indicators
            $operatorContainer.html('<span class="spinner is-active"></span>');
            $valueContainer.html('<span class="spinner is-active"></span>');
            $details.show();
            
            // AJAX request to get operator and value fields
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'pisol_aclw_get_condition_fields',
                    condition_type: conditionType,
                    condition_id: groupId + '_' + conditionId, // Include group ID
                    operator: currentOperator,
                    value: currentValue,
                    nonce: pisol_aclw_conditions.nonce
                },
                success: function(response) {
                    if (response.success) {
                        if (response.data.operator_html) {
                            $operatorContainer.html(response.data.operator_html);
                            
                            // Update the name attribute to include group ID
                            $operatorContainer.find('select').attr('name', 
                                'pisol_aclw_conditions[' + groupId + '][conditions][' + conditionId + '][operator]');
                            
                            // Trigger change event on the operator to initialize any dependent logic
                            var $operator = $operatorContainer.find('.pisol-aclw-operator');
                            if ($operator.length) {
                                $operator.trigger('change');
                            }
                        } else {
                            $operatorContainer.html('');
                        }
                        
                        if (response.data.value_html) {
                            $valueContainer.html(response.data.value_html);
                            
                            // Update the name attribute in any input/select elements
                            $valueContainer.find('select, input').each(function() {
                                var oldName = $(this).attr('name');
                                if (oldName) {
                                    // Handle multiple select fields with [] at the end
                                    var newName;
                                    if (oldName.indexOf('pisol_aclw_temp[value]') === 0) {
                                        // This is our special case for multiple selects
                                        newName = 'pisol_aclw_conditions[' + groupId + '][conditions][' + conditionId + '][value][]';
                                    } else {
                                        // Regular field name replacement
                                        newName = oldName.replace(
                                            /pisol_aclw_conditions\[([^\]]+)\]/,
                                            'pisol_aclw_conditions[' + groupId + '][conditions][' + conditionId + ']'
                                        );
                                    }
                                    $(this).attr('name', newName);
                                }
                            });
                            
                            // Initialize select2 on any new select fields
                            $valueContainer.find('select').each(function() {
                                if ($(this).data('select2')) {
                                    $(this).select2('destroy');
                                }
                                $(this).select2();
                            });
                            
                            // Trigger event for other components to hook into
                            $(document).trigger('pisol_aclw_condition_fields_loaded');
                        } else {
                            $valueContainer.html('');
                        }
                    } else {
                        $operatorContainer.html('<p class="error">' + response.data + '</p>');
                        $valueContainer.html('');
                    }
                },
                error: function() {
                    $operatorContainer.html('<p class="error">Error loading condition fields</p>');
                    $valueContainer.html('');
                }
            });
        },
        
        // Handle taxonomy selection change
        handleTaxonomyChange: function() {
            var taxonomy = $(this).val();
            var conditionId = $(this).data('condition-id');
            var termsContainer = $(this).closest('.pisol-aclw-custom-taxonomy-wrapper').find('.pisol-aclw-terms-selection');
            var termsSelect = termsContainer.find('.pisol-aclw-terms-select');
            
            // Collect the currently selected terms before replacing the options
            var selectedTerms = [];
            termsSelect.find('option:selected').each(function() {
                selectedTerms.push($(this).val());
            });
            
            if (taxonomy) {
                // Show the terms container
                termsContainer.show();
                
                // Show loading indicator
                termsSelect.html('<option value="">Loading...</option>');
                
                // Load terms via AJAX
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'pisol_aclw_get_taxonomy_terms',
                        nonce: pisol_aclw_conditions.nonce,
                        taxonomy: taxonomy,
                        selected_terms: selectedTerms
                    },
                    success: function(response) {
                        if (response.success && response.data.html) {
                            // Replace the select options with the generated HTML from server
                            termsSelect.html(response.data.html);
                            
                            // Initialize SelectWoo if available
                            if ($.fn.selectWoo) {
                                if (termsSelect.data('select2')) {
                                    termsSelect.selectWoo('destroy');
                                }
                                termsSelect.selectWoo();
                            }
                        } else {
                            termsSelect.html('<option value="">No terms found</option>');
                        }
                    },
                    error: function() {
                        termsSelect.html('<option value="">Error loading terms</option>');
                    }
                });
            } else {
                // Hide the terms container if no taxonomy is selected
                termsContainer.hide();
            }
        },
        
        // Handle data type changes to update input type
        handleDataTypeChange: function() {
            var conditionId = $(this).data('condition-id');
            var dataType = $(this).val();
            var valueInput = $('#pisol_aclw_meta_value_' + conditionId);
            
            if (dataType === 'number') {
                valueInput.attr('type', 'number');
                valueInput.attr('step', 'any');
            } else {
                valueInput.attr('type', 'text');
                valueInput.removeAttr('step');
            }
        }
    };
    
    $(document).ready(function() {
        PISOL_ACLW_Conditions.init();
    });
    
})(jQuery);
