<?php
namespace PISOL\ACLW\CONDITION;

/**
 * Cart Subtotal Condition
 *
 * @package Add_Coupon_By_Link_For_WooCommerce_Pro
 */

defined('ABSPATH') || exit;

/**
 * Class for cart subtotal condition
 */
class Cart_Subtotal extends Base_Condition {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get condition ID
     *
     * @return string
     */
    public function get_id() {
        return 'cart_subtotal';
    }
    
    /**
     * Get condition name
     *
     * @return string
     */
    public function get_name() {
        return __('Cart subtotal', 'add-coupon-by-link-woocommerce');
    }
    
    public function get_group() {
        return 'cart';
    }

    /**
     * Get available operators
     *
     * @return array
     */
    public function get_operators() {
        return array(
            'eq' => __('Equal to', 'add-coupon-by-link-woocommerce'),
            'neq' => __('Not equal to', 'add-coupon-by-link-woocommerce'),
            'gt' => __('Greater than', 'add-coupon-by-link-woocommerce'),
            'gte' => __('Greater than or equal to', 'add-coupon-by-link-woocommerce'),
            'lt' => __('Less than', 'add-coupon-by-link-woocommerce'),
            'lte' => __('Less than or equal to', 'add-coupon-by-link-woocommerce'),
        );
    }
    
    /**
     * Get HTML for value field
     *
     * @param string $html Current HTML.
     * @param string $condition_id Condition ID.
     * @param string $unused_param Unused parameter.
     * @param mixed $current_value Current value.
     * @return string
     */
    public function get_value_field($html, $condition_id, $unused_param, $current_value) {
        ob_start();
        ?>
        <div class="pisol-aclw-price-input">
            <input 
                type="number" 
                name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value]"
                value="<?php echo esc_attr($current_value); ?>"
                min="0.01"
                step="0.01"
                class="regular-text"
                placeholder="<?php echo esc_attr(sprintf(__('Amount (%s)', 'add-coupon-by-link-woocommerce'), get_woocommerce_currency_symbol())); ?>"
            >
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Check if condition is met
     *
     * @param mixed $return Return value.
     * @param mixed $cart WC_Cart object.
     * @param string $operator Operator.
     * @param mixed $value Value to compare against.
     * @return bool
     */
    public function is_match($return, $cart, $operator, $value) {
        if (!$cart || !is_a($cart, 'WC_Cart') || empty($value)) {
            return false;
        }
        
        $target_subtotal = floatval($value);
        $cart_subtotal = floatval($cart->get_subtotal());
        
        switch ($operator) {
            case 'eq':
                return $cart_subtotal == $target_subtotal;
            case 'neq':
                return $cart_subtotal != $target_subtotal;
            case 'gt':
                return $cart_subtotal > $target_subtotal;
            case 'gte':
                return $cart_subtotal >= $target_subtotal;
            case 'lt':
                return $cart_subtotal < $target_subtotal;
            case 'lte':
                return $cart_subtotal <= $target_subtotal;
            default:
                return false;
        }
    }
}
