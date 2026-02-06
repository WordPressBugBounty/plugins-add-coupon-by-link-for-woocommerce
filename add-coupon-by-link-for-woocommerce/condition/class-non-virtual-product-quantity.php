<?php
namespace PISOL\ACLW\CONDITION;

/**
 * Total Non Virtual Product Quantity in Cart Condition
 *
 * @package Add_Coupon_By_Link_For_WooCommerce_Pro
 */

defined('ABSPATH') || exit;

/**
 * Class for total non virtual product quantity condition
 */
class Non_Virtual_Product_Quantity extends Base_Condition {
    
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
        return 'non_virtual_product_quantity';
    }
    
    /**
     * Get condition name
     *
     * @return string
     */
    public function get_name() {
        return __('Non virtual product quantity', 'add-coupon-by-link-woocommerce');
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
        <div class="pisol-aclw-quantity-input">
            <input 
                type="number" 
                name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value]"
                value="<?php echo esc_attr($current_value); ?>"
                min="1"
                step="1"
                class="regular-text"
                placeholder="<?php esc_attr_e('Quantity', 'add-coupon-by-link-woocommerce'); ?>"
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
        
        $target_quantity = intval($value);
        $total_quantity = 0;
        
        // Count all non virtual items in the cart
        foreach ($cart->get_cart() as $cart_item) {
            if(isset($cart_item['data']) && !$cart_item['data']->is_virtual()){
                $total_quantity += $cart_item['quantity'];
            }
        }
        
        switch ($operator) {
            case 'eq':
                return $total_quantity == $target_quantity;
            case 'neq':
                return $total_quantity != $target_quantity;
            case 'gt':
                return $total_quantity > $target_quantity;
            case 'gte':
                return $total_quantity >= $target_quantity;
            case 'lt':
                return $total_quantity < $target_quantity;
            case 'lte':
                return $total_quantity <= $target_quantity;
            default:
                return false;
        }
    }
}
