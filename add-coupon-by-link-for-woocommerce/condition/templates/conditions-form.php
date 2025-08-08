<?php
/**
 * Template for tag conditions form
 *
 * @package Coupon_Meta_Box 
 */

defined('ABSPATH') || exit;
?>

<div class="pisol-aclw-conditions-container">
    <p class="description">
        <?php esc_html_e('Add condition, this will decide when this coupon will be valid', 'add-coupon-by-link-woocommerce'); ?>
    </p>
    
    <div id="pisol-aclw-groups-list">
        <?php 
        if (!empty($conditions)) :
            $first_group = true;
            foreach ($conditions as $group_id => $group) : 
                $match_type = isset($group['match_type']) ? $group['match_type'] : 'all';
                $group_logic = isset($group['logic']) ? $group['logic'] : 'AND';
                $group_conditions = isset($group['conditions']) ? $group['conditions'] : array();
                ?>
                
                <?php if (!$first_group) : ?>
                <div class="pisol-aclw-group-logic-divider">
                    <select name="pisol_aclw_conditions[<?php echo esc_attr($group_id); ?>][logic]" class="pisol-aclw-group-logic">
                        <option value="AND" <?php selected($group_logic, 'AND'); ?>><?php esc_html_e('AND', 'add-coupon-by-link-woocommerce'); ?></option>
                        <option value="OR" <?php selected($group_logic, 'OR'); ?>><?php esc_html_e('OR', 'add-coupon-by-link-woocommerce'); ?></option>
                    </select>
                </div>
                <?php endif; ?>
                
                <div class="pisol-aclw-group" data-group-id="<?php echo esc_attr($group_id); ?>">
                    <div class="pisol-aclw-group-header">
                        <div class="pisol-aclw-group-title">
                            <?php esc_html_e('Condition Group', 'add-coupon-by-link-woocommerce'); ?>
                        </div>
                        <div class="pisol-aclw-group-match-type">
                            <select name="pisol_aclw_conditions[<?php echo esc_attr($group_id); ?>][match_type]" class="pisol-aclw-match-type">
                                <option value="all" <?php selected($match_type, 'all'); ?>><?php esc_html_e('All conditions match', 'add-coupon-by-link-woocommerce'); ?></option>
                                <option value="any" <?php selected($match_type, 'any'); ?>><?php esc_html_e('Any condition matches', 'add-coupon-by-link-woocommerce'); ?></option>
                            </select>
                        </div>
                        <div class="pisol-aclw-group-actions">
                            <button type="button" class="button pisol-aclw-add-condition-to-group"><?php esc_html_e('Add Condition', 'add-coupon-by-link-woocommerce'); ?></button>
                            <button type="button" class="button pisol-aclw-remove-group"><?php esc_html_e('Remove Group', 'add-coupon-by-link-woocommerce'); ?></button>
                        </div>
                    </div>
                    
                    <div class="pisol-aclw-group-content">
                        <div class="pisol-aclw-conditions-list">
                            <?php 
                            if (!empty($group_conditions)) :
                                foreach ($group_conditions as $condition_id => $condition) : 
                                    $type = isset($condition['type']) ? $condition['type'] : '';
                                    $operator = isset($condition['operator']) ? $condition['operator'] : '';
                                    $value = isset($condition['value']) ? $condition['value'] : '';
                                    ?>
                                    
                                    <div class="pisol-aclw-condition" data-condition-id="<?php echo esc_attr($condition_id); ?>">
                                        <div class="pisol-aclw-condition-content">
                                            <select name="pisol_aclw_conditions[<?php echo esc_attr($group_id); ?>][conditions][<?php echo esc_attr($condition_id); ?>][type]" class="pisol-aclw-condition-type">
                                                <option value=""><?php esc_html_e('Select Condition', 'add-coupon-by-link-woocommerce'); ?></option>
                                                <?php 
                                                $grouped_conditions = array();
                                                foreach ($condition_types as $condition_key => $condition_label) {
                                                    $group_name = apply_filters("pisol_aclw_{$condition_key}_group", '');
                                                    if (!isset($grouped_conditions[$group_name])) {
                                                        $grouped_conditions[$group_name] = array();
                                                    }
                                                    $grouped_conditions[$group_name][$condition_key] = $condition_label;
                                                }
                                                
                                                foreach ($grouped_conditions as $group_name => $group_conditions) :
                                                    if (!empty($group_name)) : 
                                                ?>
                                                    <optgroup label="<?php echo esc_attr(ucfirst($group_name)); ?>">
                                                        <?php foreach ($group_conditions as $condition_key => $condition_label) : 
                                                            $disabled = in_array($condition_key, $pro_conditions) ? 'disabled' : ''; 
                                                            ?>
                                                            <option value="<?php echo esc_attr($condition_key); ?>" <?php selected($type, $condition_key); ?>  <?php echo esc_attr($disabled); ?> ><?php echo esc_html($condition_label); ?></option>
                                                        <?php endforeach; ?>
                                                    </optgroup>
                                                <?php 
                                                    else :
                                                        foreach ($group_conditions as $condition_key => $condition_label) : 
                                                ?>
                                                            <option value="<?php echo esc_attr($condition_key); ?>" <?php selected($type, $condition_key); ?>><?php echo esc_html($condition_label); ?></option>
                                                <?php
                                                        endforeach;
                                                    endif;
                                                endforeach;
                                                ?>
                                            </select>
                                            
                                            <div class="pisol-aclw-condition-data" 
                                                data-condition-type="<?php echo esc_attr($type); ?>"
                                                data-condition-operator="<?php echo esc_attr($operator); ?>"
                                                data-condition-value="<?php echo is_array($value) ? esc_attr(wp_json_encode($value)) : esc_attr($value); ?>">
                                            </div>
                                            
                                            <div class="pisol-aclw-condition-details" style="<?php echo empty($type) ? 'display:none;' : ''; ?>">
                                                <div class="pisol-aclw-operator-container">
                                                    <?php if (!empty($type)) : ?>
                                                        <?php 
                                                        $operators = apply_filters("pisol_aclw_{$type}_operators", array());
                                                        if (!empty($operators)) : 
                                                        ?>
                                                        <select name="pisol_aclw_conditions[<?php echo esc_attr($group_id); ?>][conditions][<?php echo esc_attr($condition_id); ?>][operator]" class="pisol-aclw-operator">
                                                            <?php foreach ($operators as $op_key => $op_label) : 
                                                                $disabled = in_array($condition_key, $pro_conditions) ? 'disabled' : ''; 
                                                                ?>
                                                                <option value="<?php echo esc_attr($op_key); ?>" <?php selected($operator, $op_key); ?>  <?php echo esc_attr($disabled); ?> ><?php echo esc_html($op_label); ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="pisol-aclw-value-container">
                                                    <?php if (!empty($type)) : ?>
                                                        <?php 
                                                        $value_field = apply_filters("pisol_aclw_{$type}_value_field", '', $group_id . '_' . $condition_id, '', $value);
                                                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Values are escaped in the respective filter callbacks
                                                        echo $value_field;
                                                        ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <button type="button" class="button pisol-aclw-remove-condition"><?php esc_html_e('Remove', 'add-coupon-by-link-woocommerce'); ?></button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php $first_group = false; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="pisol-aclw-actions">
        <button type="button" class="button button-primary pisol-aclw-add-group"><?php esc_html_e('Add Condition Group', 'add-coupon-by-link-woocommerce'); ?></button>
    </div>
    
    <!-- Templates for JavaScript -->
    <script type="text/template" id="tmpl-pisol-aclw-group">
        <div class="pisol-aclw-group" data-group-id="{{ data.groupId }}">
            <div class="pisol-aclw-group-header">
                <div class="pisol-aclw-group-title">
                    <?php esc_html_e('Condition Group', 'add-coupon-by-link-woocommerce'); ?>
                </div>
                <div class="pisol-aclw-group-match-type">
                    <select name="pisol_aclw_conditions[{{ data.groupId }}][match_type]" class="pisol-aclw-match-type">
                        <option value="all"><?php esc_html_e('All conditions match', 'add-coupon-by-link-woocommerce'); ?></option>
                        <option value="any"><?php esc_html_e('Any condition matches', 'add-coupon-by-link-woocommerce'); ?></option>
                    </select>
                </div>
                <div class="pisol-aclw-group-actions">
                    <button type="button" class="button pisol-aclw-add-condition-to-group"><?php esc_html_e('Add Condition', 'add-coupon-by-link-woocommerce'); ?></button>
                    <button type="button" class="button pisol-aclw-remove-group"><?php esc_html_e('Remove Group', 'add-coupon-by-link-woocommerce'); ?></button>
                </div>
            </div>
            <div class="pisol-aclw-group-content">
                <div class="pisol-aclw-conditions-list"></div>
            </div>
        </div>
    </script>
    
    <script type="text/template" id="tmpl-pisol-aclw-group-logic-divider">
        <div class="pisol-aclw-group-logic-divider">
            <select name="pisol_aclw_conditions[{{ data.groupId }}][logic]" class="pisol-aclw-group-logic">
                <option value="AND"><?php esc_html_e('AND', 'add-coupon-by-link-woocommerce'); ?></option>
                <option value="OR"><?php esc_html_e('OR', 'add-coupon-by-link-woocommerce'); ?></option>
            </select>
        </div>
    </script>
    
    <script type="text/template" id="tmpl-pisol-aclw-condition">
        <div class="pisol-aclw-condition" data-condition-id="{{ data.conditionId }}">
            <div class="pisol-aclw-condition-content">
                <select name="pisol_aclw_conditions[{{ data.groupId }}][conditions][{{ data.conditionId }}][type]" class="pisol-aclw-condition-type">
                    <option value=""><?php esc_html_e('Select Condition', 'add-coupon-by-link-woocommerce'); ?></option>
                    <?php
                    $grouped_conditions = array();
                    foreach ($condition_types as $condition_key => $condition_label) {
                        $group_name = apply_filters("pisol_aclw_{$condition_key}_group", '');
                        if (!isset($grouped_conditions[$group_name])) {
                            $grouped_conditions[$group_name] = array();
                        }
                        $grouped_conditions[$group_name][$condition_key] = $condition_label;
                    }
                    
                    foreach ($grouped_conditions as $group_name => $group_conditions) :
                        if (!empty($group_name)) : 
                    ?>
                        <optgroup label="<?php echo esc_attr(ucfirst($group_name)); ?>">
                            <?php foreach ($group_conditions as $condition_key => $condition_label) : 
                                $disabled = in_array($condition_key, $pro_conditions) ? 'disabled' : ''; 
                                ?>
                                <option value="<?php echo esc_attr($condition_key); ?>"  <?php echo esc_attr($disabled); ?> ><?php echo esc_html($condition_label); ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php 
                        else :
                            foreach ($group_conditions as $condition_key => $condition_label) : 
                                $disabled = in_array($condition_key, $pro_conditions) ? 'disabled' : ''; 
                    ?>
                                <option value="<?php echo esc_attr($condition_key); ?>" <?php echo esc_attr($disabled); ?> ><?php echo esc_html($condition_label); ?></option>
                    <?php
                            endforeach;
                        endif;
                    endforeach;
                    ?>
                </select>
                
                <div class="pisol-aclw-condition-details" style="display:none;">
                    <div class="pisol-aclw-operator-container"></div>
                    <div class="pisol-aclw-value-container"></div>
                </div>
                
                <button type="button" class="button pisol-aclw-remove-condition"><?php esc_html_e('Remove', 'add-coupon-by-link-woocommerce'); ?></button>
            </div>
        </div>
    </script>
</div>
