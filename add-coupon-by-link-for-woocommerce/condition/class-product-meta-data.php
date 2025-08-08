<?php
namespace PISOL\ACLW\CONDITION;

/**
 * Product Meta Data Condition
 *
 * @package Add_Coupon_By_Link_For_WooCommerce_Pro
 */

defined('ABSPATH') || exit;

/**
 * Class for checking product meta data in cart
 */
class Product_Meta_Data extends Base_Condition {
    
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
        return 'product_meta_data';
    }
    
    /**
     * Get condition name
     *
     * @return string
     */
    public function get_name() {
        return __('Product Meta Data', 'add-coupon-by-link-woocommerce');
    }

    /**
     * Get condition group
     *
     * @return string
     */
    public function get_group() {
        return 'product';
    }
    
    /**
     * Get available operators
     *
     * @return array
     */
    public function get_operators() {
        return array(
            'exists' => __('Meta key exists', 'add-coupon-by-link-woocommerce'),
            'not_exists' => __('Meta key does not exist', 'add-coupon-by-link-woocommerce'),
            'equals' => __('Meta value equals', 'add-coupon-by-link-woocommerce'),
            'not_equals' => __('Meta value does not equal', 'add-coupon-by-link-woocommerce'),
            'greater' => __('Meta value is greater than (numeric)', 'add-coupon-by-link-woocommerce'),
            'less' => __('Meta value is less than (numeric)', 'add-coupon-by-link-woocommerce'),
            'contains' => __('Meta value contains (text)', 'add-coupon-by-link-woocommerce'),
            'not_contains' => __('Meta value does not contain (text)', 'add-coupon-by-link-woocommerce'),
            'starts_with' => __('Meta value starts with (text)', 'add-coupon-by-link-woocommerce'),
            'ends_with' => __('Meta value ends with (text)', 'add-coupon-by-link-woocommerce'),
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
        // Set default values
        $meta_key = '';
        $meta_value = '';
        $data_type = 'text';
        
        // Parse current value
        if (!empty($current_value) && is_array($current_value)) {
            $meta_key = isset($current_value['key']) ? $current_value['key'] : '';
            $meta_value = isset($current_value['value']) ? $current_value['value'] : '';
            $data_type = isset($current_value['type']) ? $current_value['type'] : 'text';
        }
        
        ob_start();
        ?>
        <div class="pisol-aclw-meta-data-wrapper" style="margin-top: 10px; display: flex; flex-direction: column; gap: 15px;">
            <!-- Meta Key Input -->
            <div class="pisol-aclw-meta-key">
                <label for="pisol_aclw_meta_key_<?php echo esc_attr($condition_id); ?>" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php esc_html_e('Meta Key', 'add-coupon-by-link-woocommerce'); ?>
                </label>
                <input 
                    type="text" 
                    id="pisol_aclw_meta_key_<?php echo esc_attr($condition_id); ?>"
                    name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][key]"
                    value="<?php echo esc_attr($meta_key); ?>"
                    style="width: 300px;"
                    placeholder="<?php esc_attr_e('Enter product meta key', 'add-coupon-by-link-woocommerce'); ?>"
                />
                <p class="description">
                    <?php esc_html_e('The meta key to check for in cart products', 'add-coupon-by-link-woocommerce'); ?>
                </p>
            </div>
            
            <!-- Data Type Selection -->
            <div class="pisol-aclw-data-type">
                <label for="pisol_aclw_data_type_<?php echo esc_attr($condition_id); ?>" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php esc_html_e('Data Type', 'add-coupon-by-link-woocommerce'); ?>
                </label>
                <select 
                    id="pisol_aclw_data_type_<?php echo esc_attr($condition_id); ?>"
                    name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][type]"
                    style="width: 300px;"
                    class="pisol-aclw-data-type-select"
                    data-condition-id="<?php echo esc_attr($condition_id); ?>"
                >
                    <option value="text" <?php selected($data_type, 'text'); ?>><?php esc_html_e('Text', 'add-coupon-by-link-woocommerce'); ?></option>
                    <option value="number" <?php selected($data_type, 'number'); ?>><?php esc_html_e('Number', 'add-coupon-by-link-woocommerce'); ?></option>
                </select>
                <p class="description">
                    <?php esc_html_e('Select the data type of the meta value', 'add-coupon-by-link-woocommerce'); ?>
                </p>
            </div>
            
            <!-- Meta Value Input -->
            <div class="pisol-aclw-meta-value">
                <label for="pisol_aclw_meta_value_<?php echo esc_attr($condition_id); ?>" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php esc_html_e('Meta Value', 'add-coupon-by-link-woocommerce'); ?>
                </label>
                <input 
                    type="<?php echo $data_type === 'number' ? 'number' : 'text'; ?>"
                    id="pisol_aclw_meta_value_<?php echo esc_attr($condition_id); ?>"
                    name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][value]"
                    value="<?php echo esc_attr($meta_value); ?>"
                    style="width: 300px;"
                    placeholder="<?php esc_attr_e('Enter value to match', 'add-coupon-by-link-woocommerce'); ?>"
                    <?php echo $data_type === 'number' ? 'step="any"' : ''; ?>
                />
                <p class="description">
                    <?php esc_html_e('The value to check against (not required for exists/not exists operators)', 'add-coupon-by-link-woocommerce'); ?>
                </p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Check if condition is met
     *
     * @param mixed $return Current return value.
     * @param mixed $cart WC_Cart object.
     * @param string $operator Operator.
     * @param mixed $value Value to compare against.
     * @return bool
     */
    public function is_match($return, $cart, $operator, $value) {
        // Early return if required data is missing
        if (!$cart || !is_a($cart, 'WC_Cart') || empty($value) || !is_array($value) || empty($value['key'])) {
            return false;
        }
        
        $meta_key = sanitize_key($value['key']);
        $meta_value = isset($value['value']) ? $value['value'] : '';
        $data_type = isset($value['type']) ? $value['type'] : 'text';
        
        if (empty($meta_key)) {
            return false;
        }
        
        $items = $cart->get_cart();
        if (empty($items)) {
            return false;
        }
        
        $found_match = false;
        
        foreach ($items as $cart_item) {
            $product_id = $cart_item['product_id'];
            $product = wc_get_product($product_id);
            
            if (!$product) {
                continue;
            }
            
            // Get meta data value
            $product_meta_value = $product->get_meta($meta_key);
            $meta_exists = ($product_meta_value !== '');
            
            // Check existence operators first
            if ($operator === 'exists') {
                if ($meta_exists) {
                    $found_match = true;
                    break;
                }
                continue;
            } elseif ($operator === 'not_exists') {
                if (!$meta_exists) {
                    $found_match = true;
                    break;
                }
                continue;
            }
            
            // Skip comparison if meta doesn't exist
            if (!$meta_exists) {
                continue;
            }
            
            // Convert to appropriate type for comparison
            if ($data_type === 'number') {
                $product_meta_value = (float) $product_meta_value;
                $compare_value = (float) $meta_value;
            } else {
                $product_meta_value = (string) $product_meta_value;
                $compare_value = (string) $meta_value;
            }
            
            // Check value based on operator
            switch ($operator) {
                case 'equals':
                    if ($product_meta_value == $compare_value) { // Non-strict comparison for flexibility
                        $found_match = true;
                    }
                    break;
                    
                case 'not_equals':
                    if ($product_meta_value != $compare_value) { // Non-strict comparison for flexibility
                        $found_match = true;
                    }
                    break;
                    
                case 'greater':
                    if ($product_meta_value > $compare_value) {
                        $found_match = true;
                    }
                    break;
                    
                case 'less':
                    if ($product_meta_value < $compare_value) {
                        $found_match = true;
                    }
                    break;
                    
                case 'contains':
                    if (stripos($product_meta_value, $compare_value) !== false) {
                        $found_match = true;
                    }
                    break;
                    
                case 'not_contains':
                    if (stripos($product_meta_value, $compare_value) === false) {
                        $found_match = true;
                    }
                    break;
                    
                case 'starts_with':
                    if (stripos($product_meta_value, $compare_value) === 0) {
                        $found_match = true;
                    }
                    break;
                    
                case 'ends_with':
                    $position = stripos($product_meta_value, $compare_value);
                    if ($position !== false && $position === (strlen($product_meta_value) - strlen($compare_value))) {
                        $found_match = true;
                    }
                    break;
            }
            
            // If we found a match, we can stop checking other cart items
            if ($found_match) {
                break;
            }
        }
        
        return $found_match;
    }
}