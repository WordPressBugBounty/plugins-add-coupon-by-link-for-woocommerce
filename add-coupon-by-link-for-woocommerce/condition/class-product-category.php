<?php
namespace PISOL\ACLW\CONDITION;

/**
 * Product Category Condition
 *
 * @package Auto_Assign_Order_Tags_For_WooCommerce
 */

defined('ABSPATH') || exit;

/**
 * Class for product category condition
 */
class Product_Category extends Base_Condition {
    
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
        return 'product_category';
    }
    
    /**
     * Get condition name
     *
     * @return string
     */
    public function get_name() {
        return __('Product category', 'auto-assign-order-tags-for-woocommerce');
    }

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
            'in' => __('Cart contains product from categories', 'auto-assign-order-tags-for-woocommerce'),
            'not_in' => __('Cart does not contain product from categories', 'auto-assign-order-tags-for-woocommerce'),
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
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ));
        
        ob_start();
        ?>
        <div class="pisol-aclw-category-value">
            <select 
                name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][]"
                class="pisol-aclw-multi-select"
                multiple="multiple"
                style="width: 300px;"
                data-condition-id="<?php echo esc_attr($condition_id); ?>"
            >
                <?php 
                $current_values = !empty($current_value) ? $current_value : array();
                
                if (!is_wp_error($categories) && !empty($categories)) {
                    foreach ($categories as $category) : 
                    ?>
                        <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo in_array((string)$category->term_id, $current_values) ? 'selected="selected"' : ''; ?>><?php echo esc_html($category->name); ?></option>
                    <?php 
                    endforeach;
                }
                ?>
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
        if (!$cart || !is_a($cart, 'WC_Cart') || empty($value)) {
            return false;
        }
        
        $items = $cart->get_cart();
        if (empty($items)) {
            return false;
        }
        
        // Always handle values as comma-separated list
        $category_ids = !empty($value) && is_array($value) ? $value : array();
        
        $found_categories = array();
        
        foreach ($items as $cart_item_key => $item) {
            $product_id = $item['product_id'];
            $product_categories = wc_get_product_term_ids($product_id, 'product_cat');
            
            // Check each product category against our list of categories
            foreach ($product_categories as $product_cat) {
                if (in_array($product_cat, $category_ids)) {
                    $found_categories[] = $product_cat;
                }
            }
        }
        
        $contains_category = !empty($found_categories);
        
        switch ($operator) {
            case 'in':
                return $contains_category;
            case 'not_in':
                return !$contains_category;
            default:
                return false;
        }
    }
}
