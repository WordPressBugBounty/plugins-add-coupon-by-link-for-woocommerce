<?php
namespace PISOL\ACLW\CONDITION;

/**
 * Custom Product Taxonomy Condition
 *
 * @package Add_Coupon_By_Link_For_WooCommerce_Pro
 */

defined('ABSPATH') || exit;

/**
 * Class for checking if products in cart have specific custom taxonomy terms
 */
class Custom_Product_Taxonomy extends Base_Condition {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Add AJAX handler for getting taxonomy terms
        add_action('wp_ajax_pisol_aclw_get_taxonomy_terms', array($this, 'ajax_get_taxonomy_terms'));
    }
    
    /**
     * Get condition ID
     *
     * @return string
     */
    public function get_id() {
        return 'custom_product_taxonomy';
    }
    
    /**
     * Get condition name
     *
     * @return string
     */
    public function get_name() {
        return __('Custom product taxonomy', 'add-coupon-by-link-woocommerce');
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
            'in' => __('Cart contains product with taxonomy terms', 'add-coupon-by-link-woocommerce'),
            'not_in' => __('Cart does not contain product with taxonomy terms', 'add-coupon-by-link-woocommerce'),
        );
    }
    
    /**
     * Get available product taxonomies
     *
     * @return array
     */
    private function get_product_taxonomies() {
        $product_taxonomies = get_object_taxonomies('product', 'objects');
        $taxonomies = array();
        
        // Filter out WooCommerce default taxonomies if desired
        $exclude_default = array('product_type', 'product_visibility', 'product_shipping_class');
        
        foreach ($product_taxonomies as $taxonomy) {
            // Skip internal taxonomies and optionally default ones
            if (substr($taxonomy->name, 0, 1) === '_' || in_array($taxonomy->name, $exclude_default)) {
                continue;
            }
            
            $taxonomies[$taxonomy->name] = $taxonomy->label;
        }
        
        return $taxonomies;
    }
    
    /**
     * Get terms for a specific taxonomy
     *
     * @param string $taxonomy Taxonomy name
     * @return array
     */
    private function get_taxonomy_terms($taxonomy) {
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ));
        
        $term_options = array();
        
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                $term_options[$term->term_id] = $term->name;
            }
        }
        
        return $term_options;
    }
    
    /**
     * AJAX handler for getting taxonomy terms
     */
    public function ajax_get_taxonomy_terms() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'pisol_aclw_ajax_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed', 'add-coupon-by-link-woocommerce')));
        }
        
        $taxonomy = isset($_POST['taxonomy']) ? sanitize_text_field($_POST['taxonomy']) : '';
        
        if (empty($taxonomy)) {
            wp_send_json_error(array('message' => __('No taxonomy specified', 'add-coupon-by-link-woocommerce')));
        }
        
        // Get selected terms from the request if available
        $selected_terms = isset($_POST['selected_terms']) ? array_map('sanitize_text_field', (array) $_POST['selected_terms']) : array();
        
        $terms = $this->get_taxonomy_terms($taxonomy);
        
        // Generate the HTML for the dropdown with selected options
        $html = '';
        foreach ($terms as $term_id => $term_name) {
            $selected = in_array($term_id, $selected_terms) ? 'selected="selected"' : '';
            $html .= '<option value="' . esc_attr($term_id) . '" ' . $selected . '>' . esc_html($term_name) . '</option>';
        }
        
        wp_send_json_success(array(
            'html' => $html
        ));
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
        $selected_taxonomy = '';
        $selected_terms = array();
        
        // Parse current value
        if (!empty($current_value) && is_array($current_value)) {
            $selected_taxonomy = isset($current_value['taxonomy']) ? $current_value['taxonomy'] : '';
            $selected_terms = isset($current_value['terms']) && is_array($current_value['terms']) ? $current_value['terms'] : array();
        }
        
        // Convert selected terms to strings for consistent comparison
        $selected_terms = array_map('strval', $selected_terms);
        
        // Get taxonomies
        $taxonomies = $this->get_product_taxonomies();
        
        // If a taxonomy is selected, get its terms
        $terms = array();
        if (!empty($selected_taxonomy)) {
            $terms = $this->get_taxonomy_terms($selected_taxonomy);
        }
        
        ob_start();
        ?>
        <div class="pisol-aclw-custom-taxonomy-wrapper" style="margin-top: 10px; display: flex; flex-direction: column; gap: 15px;">
            <!-- Taxonomy Selection -->
            <div class="pisol-aclw-taxonomy-selection">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php esc_html_e('Product Taxonomy', 'add-coupon-by-link-woocommerce'); ?>
                </label>
                <select 
                    name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][taxonomy]"
                    class="pisol-aclw-taxonomy-select"
                    style="width: 300px;"
                    data-condition-id="<?php echo esc_attr($condition_id); ?>"
                >
                    <option value=""><?php esc_html_e('Select a taxonomy', 'add-coupon-by-link-woocommerce'); ?></option>
                    <?php foreach ($taxonomies as $taxonomy_name => $taxonomy_label) : ?>
                        <option value="<?php echo esc_attr($taxonomy_name); ?>" <?php selected($selected_taxonomy, $taxonomy_name); ?>>
                            <?php echo esc_html($taxonomy_label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description">
                    <?php esc_html_e('Select a product taxonomy to check for', 'add-coupon-by-link-woocommerce'); ?>
                </p>
            </div>
            
            <!-- Terms Selection (appears when taxonomy is selected) -->
            <div class="pisol-aclw-terms-selection" style="<?php echo empty($selected_taxonomy) ? 'display: none;' : ''; ?>">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php esc_html_e('Taxonomy Terms', 'add-coupon-by-link-woocommerce'); ?>
                </label>
                <select 
                    name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][terms][]"
                    class="pisol-aclw-terms-select pisol-aclw-multi-select"
                    multiple="multiple"
                    style="width: 300px;"
                    data-condition-id="<?php echo esc_attr($condition_id); ?>"
                >
                    <?php foreach ($terms as $term_id => $term_name) : ?>
                        <?php 
                        // Convert term_id to string for comparison
                        $term_id_str = strval($term_id);
                        $is_selected = in_array($term_id_str, $selected_terms);
                        ?>
                        <option value="<?php echo esc_attr($term_id); ?>" <?php echo $is_selected ? 'selected="selected"' : ''; ?>>
                            <?php echo esc_html($term_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description">
                    <?php esc_html_e('Select one or more terms to check for in cart products', 'add-coupon-by-link-woocommerce'); ?>
                </p>
            </div>
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
        if (!$cart || !is_a($cart, 'WC_Cart') || empty($value) || !is_array($value) || 
            empty($value['taxonomy']) || empty($value['terms'])) {
            return false;
        }
        
        $taxonomy = sanitize_key($value['taxonomy']);
        $term_ids = array_map('intval', (array)$value['terms']);
        
        if (empty($taxonomy) || empty($term_ids)) {
            return false;
        }
        
        $items = $cart->get_cart();
        if (empty($items)) {
            return false;
        }
        
        $found_terms = array();
        
        foreach ($items as $cart_item) {
            $product_id = $cart_item['product_id'];
            $product_terms = wp_get_object_terms($product_id, $taxonomy, array('fields' => 'ids'));
            
            if (!is_wp_error($product_terms) && !empty($product_terms)) {
                // Check for intersection between product terms and selected terms
                foreach ($product_terms as $term_id) {
                    if (in_array($term_id, $term_ids, false)) { // Added false to use loose comparison
                        $found_terms[] = $term_id;
                    }
                }
            }
        }
        
        $contains_term = !empty($found_terms);
        
        switch ($operator) {
            case 'in':
                return $contains_term;
            case 'not_in':
                return !$contains_term;
            default:
                return false;
        }
    }
}