<?php
namespace PISOL\ACLW\CONDITION;

/**
 * Base Condition class that all conditions will extend
 *
 * @package Auto_Assign_Order_Tags_For_WooCommerce
 */

defined('ABSPATH') || exit;

/**
 * Abstract class for conditions
 */
abstract class Base_Condition {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Register this condition type
        add_filter('pisol_aclw_condition_types', array($this, 'register_condition_type'));
        
        // Add AJAX handler for getting condition fields
        add_action('wp_ajax_pisol_aclw_get_condition_fields', array($this, 'ajax_get_condition_fields'));
        
        // Provide operators for this condition
        add_filter('pisol_aclw_' . $this->get_id() . '_operators', array($this, 'get_operators'));
        
        add_filter('pisol_aclw_' . $this->get_id() . '_group', array($this, 'get_group'));
        
        // Provide value field for this condition
        add_filter('pisol_aclw_' . $this->get_id() . '_value_field', array($this, 'get_value_field'), 10, 4);

        add_filter('pisol_aclw_' . $this->get_id() . '_is_match', array($this, 'is_match'), 10, 4);
    }
    
    /**
     * Get condition ID (for internal use)
     *
     * @return string
     */
    abstract public function get_id();
    
    /**
     * Get condition name (for display)
     *
     * @return string
     */
    abstract public function get_name();
    
    /**
     * Get available operators
     *
     * @return array
     */
    abstract public function get_operators();

    abstract public function get_group();
    
    /**
     * Get HTML for value field
     *
     * @param string $html Current HTML.
     * @param string $condition_id Condition ID.
     * @param string $unused_param Unused parameter.
     * @param string $current_value Current value.
     * @return string
     */
    abstract public function get_value_field($html, $condition_id, $unused_param, $current_value);
    
    /**
     * Check if condition is met
     *
     * @param mixed $order WC_Order object.
     * @param string $operator Operator.
     * @param mixed $value Value to compare against.
     * @return bool
     */
    abstract public function is_match($return, $order, $operator, $value);
    
    /**
     * Register this condition type
     *
     * @param array $condition_types Existing condition types.
     * @return array
     */
    public function register_condition_type($condition_types) {
        $condition_types[$this->get_id()] = $this->get_name();
        return $condition_types;
    }
    
    /**
     * AJAX handler for getting condition fields
     */
    public function ajax_get_condition_fields() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'pisol_aclw_ajax_nonce')) {
            wp_send_json_error(__('Security check failed', 'add-coupon-by-link-woocommerce'));
        }
        
        $condition_type = isset($_POST['condition_type']) ? sanitize_text_field(wp_unslash($_POST['condition_type'])) : '';
        $condition_id = isset($_POST['condition_id']) ? sanitize_text_field(wp_unslash($_POST['condition_id'])) : '';
        $current_operator = isset($_POST['operator']) ? sanitize_text_field(wp_unslash($_POST['operator'])) : '';
        $raw_value = '';
        $current_value = '';
        
        if (isset($_POST['value'])) {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            $unsanitized_value = wp_unslash($_POST['value']);
            if (is_array($unsanitized_value)) {
                $raw_value = array_map(function($item) {
                    return is_array($item) ? 
                        array_map('sanitize_text_field', $item) : 
                        sanitize_text_field($item);
                }, $unsanitized_value);
            } else {
                $raw_value = sanitize_text_field($unsanitized_value);
            }
            $current_value = $raw_value;
        }
        
        if (empty($condition_type) || empty($condition_id)) {
            wp_send_json_error(__('Missing required parameters', 'add-coupon-by-link-woocommerce'));
        }
        
        // Get operators for this condition
        $operators = apply_filters("pisol_aclw_{$condition_type}_operators", array());
        
        // Get operator HTML - we use a simple ID here, the JS will update the complex name
        $operator_html = '';
        if (!empty($operators)) {
            ob_start();
            ?>
            <select name="operator" class="pisol-aclw-operator">
                <?php foreach ($operators as $op_key => $op_label) : ?>
                    <option value="<?php echo esc_attr($op_key); ?>" <?php selected($current_operator, $op_key); ?>><?php echo esc_html($op_label); ?></option>
                <?php endforeach; ?>
            </select>
            <?php
            $operator_html = ob_get_clean();
        }
        
        // Get value field HTML - using a temporary field name, which JS will update
        $value_html = apply_filters("pisol_aclw_{$condition_type}_value_field", '', $condition_id, '', $current_value);
        
        wp_send_json_success(array(
            'operator_html' => $operator_html,
            'value_html' => $value_html
        ));
    }
}
