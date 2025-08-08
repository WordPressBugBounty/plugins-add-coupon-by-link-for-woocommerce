<?php
namespace PISOL\ACLW\CONDITION;

/**
 * Previous Orders By Category Condition
 *
 * @package Add_Coupon_By_Link_For_WooCommerce_Pro
 */

defined('ABSPATH') || exit;

/**
 * Class for checking if customer has previously ordered products from specific categories
 */
class Previous_Orders_By_Category extends Base_Condition {
    
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
        return 'previous_orders_by_category';
    }
    
    /**
     * Get condition name
     *
     * @return string
     */
    public function get_name() {
        return __('Previous orders by category', 'add-coupon-by-link-woocommerce');
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
        // Default values
        $time_period = 'all_time';
        $custom_days = '';
        $categories = array();
        $quantity = '1';
        
        // Parse current value if exists
        if (!empty($current_value) && is_array($current_value)) {
            $time_period = isset($current_value['time_period']) ? $current_value['time_period'] : 'all_time';
            $custom_days = isset($current_value['custom_days']) ? $current_value['custom_days'] : '';
            $categories = isset($current_value['categories']) && is_array($current_value['categories']) ? $current_value['categories'] : array();
            $quantity = isset($current_value['quantity']) ? $current_value['quantity'] : '1';
        }
        
        // Get product categories
        $product_categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ));
        
        ob_start();
        ?>
        <div class="pisol-aclw-previous-orders-category-wrapper pisol-aclw-custom-days-wrapper" style="display: flex; flex-direction: column; gap: 15px;">
            <!-- Time Period Selection -->
            <div class="pisol-aclw-time-period">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php esc_html_e('Time Period', 'add-coupon-by-link-woocommerce'); ?>
                </label>
                <select 
                    name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][time_period]"
                    class="pisol-aclw-time-period-select"
                    style="width: 300px;"
                    data-condition-id="<?php echo esc_attr($condition_id); ?>"
                >
                    <option value="all_time" <?php selected($time_period, 'all_time'); ?>>
                        <?php esc_html_e('All time', 'add-coupon-by-link-woocommerce'); ?>
                    </option>
                    <option value="today" <?php selected($time_period, 'today'); ?>>
                        <?php esc_html_e('Today', 'add-coupon-by-link-woocommerce'); ?>
                    </option>
                    <option value="current_week" <?php selected($time_period, 'current_week'); ?>>
                        <?php esc_html_e('Current week', 'add-coupon-by-link-woocommerce'); ?>
                    </option>
                    <option value="current_month" <?php selected($time_period, 'current_month'); ?>>
                        <?php esc_html_e('Current month', 'add-coupon-by-link-woocommerce'); ?>
                    </option>
                    <option value="current_year" <?php selected($time_period, 'current_year'); ?>>
                        <?php esc_html_e('Current year', 'add-coupon-by-link-woocommerce'); ?>
                    </option>
                    <option value="custom_days" <?php selected($time_period, 'custom_days'); ?>>
                        <?php esc_html_e('Orders placed in last X days', 'add-coupon-by-link-woocommerce'); ?>
                    </option>
                </select>
            </div>
            
            <!-- Custom Days Field (appears when custom_days is selected) -->
            <div class="pisol-aclw-custom-days" style="<?php echo $time_period === 'custom_days' ? 'display: block;' : 'display: none;'; ?>">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php esc_html_e('Number of days', 'add-coupon-by-link-woocommerce'); ?>
                </label>
                <input 
                    type="number" 
                    name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][custom_days]"
                    value="<?php echo esc_attr($custom_days); ?>"
                    min="1"
                    step="1"
                    class="regular-text"
                    placeholder="<?php esc_attr_e('Days', 'add-coupon-by-link-woocommerce'); ?>"
                    style="width: 300px;"
                >
            </div>
            
            <!-- Product Categories Selection -->
            <div class="pisol-aclw-category-selection">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php esc_html_e('Product Categories', 'add-coupon-by-link-woocommerce'); ?>
                </label>
                <select 
                    name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][categories][]"
                    class="pisol-aclw-multi-select"
                    multiple="multiple"
                    style="width: 300px;"
                    data-condition-id="<?php echo esc_attr($condition_id); ?>"
                >
                    <?php 
                    if (!is_wp_error($product_categories) && !empty($product_categories)) {
                        foreach ($product_categories as $category) : 
                            $is_selected = in_array((string)$category->term_id, $categories);
                        ?>
                            <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo $is_selected ? 'selected="selected"' : ''; ?>><?php echo esc_html($category->name); ?></option>
                        <?php 
                        endforeach;
                    }
                    ?>
                </select>
                <p class="description">
                    <?php esc_html_e('Select categories to check for previous orders', 'add-coupon-by-link-woocommerce'); ?>
                </p>
            </div>
            
            <!-- Quantity Field -->
            <div class="pisol-aclw-quantity">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">
                    <?php esc_html_e('Total quantity ordered', 'add-coupon-by-link-woocommerce'); ?>
                </label>
                <input 
                    type="number" 
                    name="pisol_aclw_conditions[<?php echo esc_attr($condition_id); ?>][value][quantity]"
                    value="<?php echo esc_attr($quantity); ?>"
                    min="1"
                    step="1"
                    class="regular-text"
                    placeholder="<?php esc_attr_e('Quantity', 'add-coupon-by-link-woocommerce'); ?>"
                    style="width: 300px;"
                >
                <p class="description">
                    <?php esc_html_e('Total quantity of products from selected categories', 'add-coupon-by-link-woocommerce'); ?>
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
        if (!$cart || !is_a($cart, 'WC_Cart') || !is_array($value) || 
            !isset($value['time_period']) || !isset($value['categories']) || !isset($value['quantity'])) {
            return false;
        }
        
        // Get current user ID
        $user_id = get_current_user_id();
        if (!$user_id) {
            // Guest user, try to get customer from session
            $customer_email = WC()->session ? WC()->session->get('billing_email') : '';
            if (empty($customer_email)) {
                return false; // No way to identify customer
            }
        }
        
        // Parse values
        $time_period = $value['time_period'];
        $categories = !empty($value['categories']) && is_array($value['categories']) ? $value['categories'] : array();
        $target_quantity = intval($value['quantity']);
        
        // Calculate date range based on selected time period
        $date_query = $this->get_date_query($time_period, isset($value['custom_days']) ? $value['custom_days'] : '');
        
        // Query orders for this customer with the specified date range
        $args = array(
            'status' => array('wc-completed', 'wc-processing'),
            'type' => 'shop_order',
            'limit' => -1,
            'date_query' => $date_query,
        );
        
        // Add customer filter
        if ($user_id) {
            $args['customer_id'] = $user_id;
        } else {
            $args['billing_email'] = $customer_email;
        }
        
        $orders = wc_get_orders($args);
        $total_quantity = 0;
        
        if (!empty($orders)) {
            foreach ($orders as $order) {
                foreach ($order->get_items() as $item) {
                    $product_id = $item->get_product_id();
                    $product_categories = wc_get_product_term_ids($product_id, 'product_cat');
                    
                    // Check if this product belongs to any of the selected categories
                    $category_match = false;
                    foreach ($product_categories as $product_cat) {
                        if (in_array($product_cat, $categories)) {
                            $category_match = true;
                            break;
                        }
                    }
                    
                    if ($category_match) {
                        $total_quantity += $item->get_quantity();
                    }
                }
            }
        }
        
        // Compare the total quantity with the target quantity using the specified operator
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
    
    /**
     * Generate date query array based on time period
     *
     * @param string $time_period Selected time period.
     * @param string $custom_days Number of days for custom period.
     * @return array Date query parameters.
     */
    private function get_date_query($time_period, $custom_days) {
        $date_query = array();
        
        switch ($time_period) {
            case 'today':
                $date_query = array(
                    'after' => date('Y-m-d 00:00:00', current_time('timestamp')),
                    'before' => date('Y-m-d 23:59:59', current_time('timestamp')),
                    'inclusive' => true,
                );
                break;
                
            case 'current_week':
                $start_of_week = get_option('start_of_week', 0);
                $current_day = date('w', current_time('timestamp'));
                $offset = ($current_day - $start_of_week + 7) % 7;
                
                $date_query = array(
                    'after' => date('Y-m-d 00:00:00', strtotime("-{$offset} days", current_time('timestamp'))),
                    'before' => date('Y-m-d 23:59:59', current_time('timestamp')),
                    'inclusive' => true,
                );
                break;
                
            case 'current_month':
                $date_query = array(
                    'after' => date('Y-m-01 00:00:00', current_time('timestamp')),
                    'before' => date('Y-m-d 23:59:59', current_time('timestamp')),
                    'inclusive' => true,
                );
                break;
                
            case 'current_year':
                $date_query = array(
                    'after' => date('Y-01-01 00:00:00', current_time('timestamp')),
                    'before' => date('Y-m-d 23:59:59', current_time('timestamp')),
                    'inclusive' => true,
                );
                break;
                
            case 'custom_days':
                $days = !empty($custom_days) ? intval($custom_days) : 30; // Default to 30 days
                $date_query = array(
                    'after' => date('Y-m-d 00:00:00', strtotime("-{$days} days", current_time('timestamp'))),
                    'before' => date('Y-m-d 23:59:59', current_time('timestamp')),
                    'inclusive' => true,
                );
                break;
                
            case 'all_time':
            default:
                // No date restrictions
                $date_query = array();
                break;
        }
        
        return $date_query;
    }
}
