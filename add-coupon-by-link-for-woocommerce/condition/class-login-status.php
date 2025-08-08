<?php
namespace PISOL\ACLW\CONDITION;

/**
 * Login Status Condition
 *
 * @package Add_Coupon_By_Link_For_WooCommerce_Pro
 */

defined('ABSPATH') || exit;

/**
 * Class for checking if a customer is logged in or not
 */
class Login_Status extends Base_Condition {
    
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
        return 'login_status';
    }
    
    /**
     * Get condition name
     *
     * @return string
     */
    public function get_name() {
        return __('Customer login status', 'add-coupon-by-link-woocommerce');
    }

    /**
     * Get condition group
     *
     * @return string
     */
    public function get_group() {
        return 'customer';
    }
    
    /**
     * Get available operators
     *
     * @return array
     */
    public function get_operators() {
        return array(
            'is' => __('Is', 'add-coupon-by-link-woocommerce'),
            'is_not' => __('Is not', 'add-coupon-by-link-woocommerce'),
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
        // Default value if none set
        $selected_value = 'logged_in';
        
        if (!empty($current_value)) {
            $selected_value = $current_value;
        }
        
        ob_start();
        ?>
        <div class="pisol-aclw-login-status-wrapper" style="margin-top: 10px;">
            <select 
                name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value]"
                class="regular-text"
                style="width: 300px;"
            >
                <option value="logged_in" <?php selected($selected_value, 'logged_in'); ?>>
                    <?php esc_html_e('Logged in', 'add-coupon-by-link-woocommerce'); ?>
                </option>
                <option value="guest" <?php selected($selected_value, 'guest'); ?>>
                    <?php esc_html_e('Guest (not logged in)', 'add-coupon-by-link-woocommerce'); ?>
                </option>
            </select>
            <p class="description">
                <?php esc_html_e('Select whether customer should be logged in or not for this condition to apply', 'add-coupon-by-link-woocommerce'); ?>
            </p>
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
        // Early return if required data is missing
        if (!$cart || !is_a($cart, 'WC_Cart') || empty($value)) {
            return false;
        }
        
        // Check if user is logged in
        $is_logged_in = is_user_logged_in();
        
        // Determine if the condition matches based on login status and expected value
        $is_value_match = false;
        
        if ($value === 'logged_in' && $is_logged_in) {
            $is_value_match = true;
        } elseif ($value === 'guest' && !$is_logged_in) {
            $is_value_match = true;
        }
        
        // Apply the operator
        switch ($operator) {
            case 'is':
                return $is_value_match;
            case 'is_not':
                return !$is_value_match;
            default:
                return false;
        }
    }
}