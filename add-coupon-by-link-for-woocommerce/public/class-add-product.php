<?php 
namespace PISOL\ACLW\FRONT;

class Add_Product{
    static $instance = null;

    public static function get_instance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(){
        // Hook to coupon applied event
        add_action('woocommerce_applied_coupon', array($this, 'process_coupon_products'), 10, 1);
        
        // Hook to coupon removed event
        add_action('woocommerce_removed_coupon', array($this, 'remove_coupon_products'), 10, 1);

        add_filter('woocommerce_before_calculate_totals', array($this, 'apply_custom_price'), 99);
        
        // Prevent quantity changes for coupon-added products
        add_filter('woocommerce_cart_item_quantity', array($this, 'disable_quantity_changes'), 10, 3);
        
        // Prevent quantity updates via AJAX for coupon-added products
        add_filter('woocommerce_cart_item_quantity_input_args', array($this, 'set_quantity_input_args'), 10, 2);
        
        
        add_filter('woocommerce_store_api_product_quantity_editable', array($this, 'disable_block_quantity_editing'), 10, 3);
    }

    /**
     * Process products associated with a coupon when it's applied
     * 
     * @param string $coupon_code The coupon code that was applied
     */
    public function process_coupon_products($coupon_code) {
        // Get coupon ID from code
        $coupon_id = wc_get_coupon_id_by_code($coupon_code);
        if (!$coupon_id) return;
        
        // Get the products associated with this coupon
        $coupon_products = get_post_meta($coupon_id, 'pi_acblw_products', true);
        if (empty($coupon_products) || !is_array($coupon_products)) return;
        
        // Add each product to cart
        foreach ($coupon_products as $product_data) {
            $this->add_product_to_cart($product_data, $coupon_code, $coupon_id);
            WC()->session->set('aclw_reload_cart', true);
        }
    }
    
    /**
     * Add a product to cart with the specified discount
     * 
     * @param array $product_data Product data from the coupon
     * @param string $coupon_code The coupon code
     * @param int $coupon_id The coupon ID
     */
    public function add_product_to_cart($product_data, $coupon_code, $coupon_id) {
        if (empty($product_data['product_id'])) return;
        
        $product_id = absint($product_data['product_id']);
        $quantity = isset($product_data['qty']) ? absint($product_data['qty']) : 1;
        $discount_type = isset($product_data['discount_type']) ? sanitize_text_field($product_data['discount_type']) : 'free';
        $discount_amount = isset($product_data['discount_amount']) ? floatval($product_data['discount_amount']) : 0;
        
        // Check if product exists
        $product = wc_get_product($product_id);
        if (!$product || !$product->is_purchasable()) return;
        
        // Calculate the final price based on discount type
        $original_price = $product->get_price();
        $final_price = $this->calculate_discounted_price($original_price, $discount_type, $discount_amount);
        
        // Custom cart item data to identify this item was added by the coupon
        $cart_item_data = array(
            'add_product_added_by_coupon' => true,
            'add_product_coupon_code' => $coupon_code,
            'add_product_coupon_id' => $coupon_id,
            'add_product_discount_type' => $discount_type,
            'add_product_discount_amount' => $discount_amount,
            'add_product_original_price' => $original_price,
            'add_product_final_price' => $final_price
        );
        
        // Add product to cart with our custom data
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, 0, array(), $cart_item_data);
        
       
    }
    
    /**
     * Calculate the discounted price based on discount type
     * 
     * @param float $original_price The original product price
     * @param string $discount_type The type of discount (free, percentage_discount, fixed_price_discount)
     * @param float $discount_amount The discount amount
     * @return float The final price after discount
     */
    private function calculate_discounted_price($original_price, $discount_type, $discount_amount) {
        switch ($discount_type) {
            case 'no-discount':
                return $original_price;

            case 'change-price':
                return $discount_amount;

            case 'percentage_discount':
                if ($discount_amount > 0 && $discount_amount <= 100) {
                    return $original_price - ($original_price * ($discount_amount / 100));
                }
                return $original_price;
                
            case 'fixed_price_discount':
                if ($discount_amount > 0) {
                    $discounted_price = $original_price - $discount_amount;
                    return $discounted_price > 0 ? $discounted_price : 0;
                }
                return $original_price;
                
            default:
                return $original_price;
        }
    }
    
    
    
    /**
     * Apply custom price to cart items added by coupons
     * 
     * @param object $cart The WooCommerce cart object
     */
    public function apply_custom_price($cart) {
        // Only modify price once
        static $run = false;
        if ($run) return;
        $run = true;
        
        // Loop through cart items
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            if (isset($cart_item['add_product_added_by_coupon']) && $cart_item['add_product_added_by_coupon']) {
                // Set the custom price
                $cart_item['data']->set_price($cart_item['add_product_final_price']);
            }
        }
    }
    
    /**
     * Remove products that were added by a specific coupon when that coupon is removed
     * 
     * @param string $coupon_code The coupon code being removed
     */
    public function remove_coupon_products($coupon_code) {
        if (empty($coupon_code)) return;
        
        // Get cart contents
        $cart = WC()->cart;
        $cart_contents = $cart->get_cart();
        
        // Loop through cart items and remove any added by this coupon
        foreach ($cart_contents as $cart_item_key => $cart_item) {
            if (isset($cart_item['add_product_added_by_coupon']) && 
                isset($cart_item['add_product_coupon_code']) && 
                $cart_item['add_product_coupon_code'] === $coupon_code) {
                
                // Remove the item from cart
                $cart->remove_cart_item($cart_item_key);
            }
        }
    }
    
    /**
     * Disable quantity changes for products added by coupons
     * 
     * @param string $product_quantity HTML for the quantity input
     * @param string $cart_item_key The cart item key
     * @param array $cart_item The cart item data
     * @return string Modified HTML for quantity input
     */
    public function disable_quantity_changes($product_quantity, $cart_item_key, $cart_item) {
        // Check if this product was added by a coupon
        if (isset($cart_item['add_product_added_by_coupon']) && $cart_item['add_product_added_by_coupon']) {
            // Get the quantity
            $quantity = $cart_item['quantity'];
            
            // Return a disabled input with the fixed quantity
            return sprintf(
                '<div class="quantity"><input type="number" class="input-text qty text" value="%d" readonly disabled /></div>
                <input type="hidden" name="cart[%s][qty]" value="%d" />',
                $quantity,
                $cart_item_key,
                $quantity
            );
        }
        
        return $product_quantity;
    }
    
    /**
     * Modify quantity input args to make inputs for coupon-added products readonly
     * 
     * @param array $args Input arguments
     * @param array $cart_item Cart item data
     * @return array Modified input arguments
     */
    public function set_quantity_input_args($args, $cart_item) {
        if (isset($cart_item['add_product_added_by_coupon']) && $cart_item['add_product_added_by_coupon']) {
            $args['readonly'] = true;
            $args['min_value'] = $cart_item['quantity'];
            $args['max_value'] = $cart_item['quantity'];
            $args['input_value'] = $cart_item['quantity'];
        }
        
        return $args;
    }
    
    /**
     * Disable quantity editing for block-based cart items added by coupons
     * 
     * @param bool $editable Whether the quantity is editable
     * @param WC_Product $product The product object
     * @param array $cart_item Cart item data
     * @return bool Modified editable status
     */
    public function disable_block_quantity_editing($editable, $product, $cart_item) {
        if (isset($cart_item['add_product_added_by_coupon']) && $cart_item['add_product_added_by_coupon']) {
            return false;
        }
        
        return $editable;
    }
}

Add_Product::get_instance();