<?php
namespace PISOL\ACLW\CONDITION;

/**
 * Billing Country Condition
 *
 * @package Auto_Assign_Order_Tags_For_WooCommerce
 */

defined('ABSPATH') || exit;

/**
 * Class for billing country condition
 */
class Billing_Country extends Base_Condition {
    
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
        return 'billing_country';
    }
    
    /**
     * Get condition name
     *
     * @return string
     */
    public function get_name() {
        return __('Billing Country', 'add-coupon-by-link-woocommerce');
    }

    public function get_group() {
        return 'location';
    }
    
    /**
     * Get available operators
     *
     * @return array
     */
    public function get_operators() {
        return array(
            'in' => __('Is any of', 'add-coupon-by-link-woocommerce'),
            'not_in' => __('Is not any of', 'add-coupon-by-link-woocommerce'),
        );
    }
    
    /**
     * Get HTML for value field
     *
     * @param string $html Current HTML.
     * @param string $condition_id Condition ID.
     * @param string $unused_param Unused parameter.
     * @param string $current_value Current value.
     * @return string
     */
    public function get_value_field($html, $condition_id, $unused_param, $current_value) {
        $countries = WC()->countries->get_countries();
        
        ob_start();
        ?>
        <div class="pisol-aclw-country-value">
            <select 
                name="pisol_aclw_temp[value][]"
                class="pisol-aclw-multi-select"
                multiple="multiple"
                style="width: 300px;"
                data-condition-id="<?php echo esc_attr($condition_id); ?>"
            >
                <?php 
                $current_values = !empty($current_value) ? $current_value : array();
                
                foreach ($countries as $code => $name) : 
                ?>
                    <option value="<?php echo esc_attr($code); ?>" <?php echo in_array($code, $current_values) ? 'selected="selected"' : ''; ?>><?php echo esc_html($name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Check if condition is met
     *
     * @param mixed $order WC_Order object.
     * @param string $operator Operator.
     * @param mixed $value Value to compare against.
     * @return bool
     */
    public function is_match($return, $cart, $operator, $value) {
        if (!$cart || !is_a($cart, 'WC_Cart')) {
            return false;
        }
        
        $customer = WC()->customer;
        if (!$customer) {
            return false;
        }
        
        $billing_country = $customer->get_billing_country();
        
        // Always handle values as comma-separated list
        $countries = !empty($value) && is_array($value) ? $value : array();
        
        switch ($operator) {
            case 'in':
                return in_array($billing_country, $countries);
            case 'not_in':
                return !in_array($billing_country, $countries);
            default:
                return false;
        }
    }
}
