<?php
namespace PISOL\ACLW\CONDITION;

class Condition_Validation {
    private static $instance = null;

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_filter( 'woocommerce_coupon_is_valid', array( __CLASS__, 'validate_condition' ), 11, 3 );
    }

    static function validate_condition($valid = false, $coupon = object, $discounts = null) {

        $coupon_id = method_exists($coupon, 'get_id') ? $coupon->get_id() : $coupon->id;

        // Validation logic here
        if( self::check_conditions($coupon_id) ) {
            $valid = true;
        } else {
            $valid = false;
        }

        return $valid; 
    }

    static function check_conditions($coupon_id){
        $conditions = get_post_meta($coupon_id, '_pisol_aclw_conditions', true);

        if(empty($conditions) || !is_array($conditions)){
            return true;
        }

        $groups = $conditions;

        $group_results = array();
        $group_index = 0;
        $cart = WC()->cart; // Get the current cart object

        if (is_null($cart)) {
            return false; // No cart available, return false
        }

        // Loop through each condition group and evaluate it
        foreach ($groups as $group_id => $group) {
            $result = self::check_condition_group($group, $cart);
            if ($result !== null) {
                $group_results[$group_id] = $result;
                $groups[$group_id]['result'] = $result; // Store the result in the group for debugging
                $group_index++;
            }
        }
        
        // If no valid groups were processed, return false
        if (empty($group_results)) {
            return false;
        }
        
        // Evaluate multiple groups with their logic operators
        return self::evaluate_group_relationships($groups);
    }

    static function check_condition_group($group, $cart) {
        // Skip if not a valid group
        if (!isset($group['conditions']) || !is_array($group['conditions']) || empty($group['conditions'])) {
            return null;
        }
        
        // Get the match type (all or any)
        $match_type = isset($group['match_type']) ? $group['match_type'] : 'all';
        
        // Track results for conditions in this group
        $condition_results = array();
        
        // Process each condition in this group
        foreach ($group['conditions'] as $condition) {
            // Skip invalid conditions
            if (!isset($condition['type']) || empty($condition['type'])) {
                continue;
            }
            
            // Evaluate the condition
            $eval_result = self::evaluate_condition($condition, $cart);

            if($eval_result === null) {
                continue; // Skip if evaluation failed
            }

            /**
             * this improves the performance as we dont have to check other conditions
             */
            if($match_type === 'all' && $eval_result === false) {
                return false; // If match_type is 'all' and any condition fails, return false
            }

            $condition_results[] = $eval_result;
        }
        
        // If no valid conditions in this group, return null
        if (empty($condition_results)) {
            return null;
        }
        
        // Determine if this group matches based on its match_type
        if ($match_type === 'all') {
            // All conditions must match
            return !in_array(false, $condition_results, true);
        } else {
            // Any condition can match
            return in_array(true, $condition_results, true);
        }
    }

    static function evaluate_condition($rule, $cart) {
        $type = sanitize_text_field($rule['type'] ?? '');
        $operator = sanitize_text_field($rule['operator'] ?? '');
        $value = $rule['value'] ?? '';
        
        // Default result is false
        $result = false;
        
        /**
         * Filter to evaluate a condition
         * 
         * @param bool $result Default result (false)
         * @param \WC_Order $order The order object
         * @param string $operator The comparison operator
         * @param mixed $value The value to compare against
         * @return bool Whether the condition is met
         */
        return apply_filters(
            "pisol_aclw_{$type}_is_match",
            $result,
            $cart,
            $operator,
            $value
        );
    }

    static function evaluate_group_relationships($groups_results) {
        // Get indexed array version
        $items = array_values($groups_results);
    
        // Step 1: resolve all ANDs
        for ($i = 0; $i < count($items) - 1; $i++) {
            if (isset($items[$i + 1]['logic']) && strtoupper($items[$i + 1]['logic']) === 'AND') {
                $items[$i + 1]['result'] = $items[$i]['result'] && $items[$i + 1]['result'];
                $items[$i]['result'] = null; // mark as merged
            }
        }
    
        // Step 2: resolve all ORs
        $result = false;
        foreach ($items as $item) {
            if ($item['result'] !== null) {
                $result = $result || $item['result'];
            }
        }
    
        return $result;
    }

}