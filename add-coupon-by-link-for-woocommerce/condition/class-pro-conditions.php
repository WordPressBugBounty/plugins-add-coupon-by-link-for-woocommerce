<?php
namespace PISOL\ACLW\CONDITION;

defined('ABSPATH') || exit;


class Pro_Conditions {

    private static $instance = null;

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    
    private static $pro_conditions = array(
        'cart_weight' => [
            'name' => 'Cart weight 🔒',
            'group' => 'cart',
        ], 
        'coupon_applied' => [
            'name' => 'Coupon applied 🔒',
            'group' => 'cart',
        ],
        'shipping_country' => [
            'name' => 'Shipping country 🔒',
            'group' => 'location',
        ],
        'user_shipping_zone' => [
            'name' => 'User shipping zone 🔒',
            'group' => 'location',
        ],
        'product_quantity' => [
            'name' => 'Specific product quantity 🔒',
            'group' => 'product',
        ],
        'order_count' => [
            'name' => 'Order count 🔒',
            'group' => 'customer',
        ],
        'amount_spent' => [
            'name' => 'Total customer spent amount 🔒',
            'group' => 'customer',
        ],
    );
    
    public function __construct() {
        add_filter('pisol_aclw_condition_types', array($this, 'mark_pro_conditions'), 20);

        add_filter('pisol_aclw_pro_conditions', [$this, 'pro_conditions'], 20);

        foreach(self::$pro_conditions as $condition_id => $condition) {
            add_filter('pisol_aclw_' . $condition_id . '_group', function($group) use ($condition) {
                return $condition['group'];
            });
        }
    }
    
    public function mark_pro_conditions($condition_types) {
        $val = array_map(function($condition) {
            return $condition['name'];
        }, self::$pro_conditions);
        $condition_types = array_merge($condition_types, $val);
        return $condition_types;
    }

    public function pro_conditions($val = array()) {
        return array_merge($val, array_keys(self::$pro_conditions));
    }
    
   
}

Pro_Conditions::get_instance();