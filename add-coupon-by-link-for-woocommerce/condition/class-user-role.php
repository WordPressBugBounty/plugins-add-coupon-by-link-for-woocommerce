<?php
namespace PISOL\ACLW\CONDITION;

/**
 * User Role Condition
 *
 * @package Add_Coupon_By_Link_For_WooCommerce_Pro
 */

defined('ABSPATH') || exit;

/**
 * Class for checking user role
 */
class User_Role extends Base_Condition {
    
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
        return 'user_role';
    }
    
    /**
     * Get condition name
     *
     * @return string
     */
    public function get_name() {
        return __('User role', 'add-coupon-by-link-woocommerce');
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
            'in' => __('Is any of', 'add-coupon-by-link-woocommerce'),
            'not_in' => __('Is none of', 'add-coupon-by-link-woocommerce'),
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
        // Get all available user roles
        $wp_roles = wp_roles();
        $roles = $wp_roles->get_names();
        
        // Add guest role option
        $roles = array('guest' => __('Guest (not logged in)', 'add-coupon-by-link-woocommerce')) + $roles;
        
        // Parse current value
        $current_values = !empty($current_value) ? (is_array($current_value) ? $current_value : array($current_value)) : array();
        
        ob_start();
        ?>
        <div class="pisol-aclw-user-role-wrapper" style="margin-top: 10px;">
            <select 
                name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][]"
                class="pisol-aclw-multi-select"
                multiple="multiple"
                style="width: 300px;"
                data-condition-id="<?php echo esc_attr($condition_id); ?>"
            >
                <?php foreach ($roles as $role_id => $role_name) : ?>
                    <option value="<?php echo esc_attr($role_id); ?>" <?php echo in_array($role_id, $current_values) ? 'selected="selected"' : ''; ?>>
                        <?php echo esc_html($role_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description">
                <?php esc_html_e('Select user roles to include or exclude for this condition', 'add-coupon-by-link-woocommerce'); ?>
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
        
        // Ensure value is an array
        $selected_roles = is_array($value) ? $value : array($value);
        
        // Check if guest role is selected
        $guest_selected = in_array('guest', $selected_roles);
        
        // If user is not logged in, they're a guest
        if (!is_user_logged_in()) {
            // If operator is 'in' and guest is selected, or
            // if operator is 'not_in' and guest is not selected, return true
            return ($operator === 'in' && $guest_selected) || 
                   ($operator === 'not_in' && !$guest_selected);
        }
        
        // If we get here, the user is logged in, so not a guest
        // Get current user
        $user = wp_get_current_user();
        $user_roles = (array) $user->roles;
        
        // Check for role intersection (excluding the guest role)
        $has_matching_role = false;
        foreach ($user_roles as $role) {
            if (in_array($role, $selected_roles)) {
                $has_matching_role = true;
                break;
            }
        }
        
        switch ($operator) {
            case 'in':
                return $has_matching_role;
            case 'not_in':
                return !$has_matching_role;
            default:
                return false;
        }
    }
}