<?php 

namespace PISOL\ACBLW\FRONT;
use Automattic\Jetpack\Constants;
class ShippingDiscountManager{
    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        add_filter( 'woocommerce_package_rates', [$this, 'apply_shipping_discount'], PHP_INT_MAX, 2 );
        add_action('woocommerce_after_shipping_rate', [$this, 'shop_original_shipping_costs'], 10, 2);
        add_filter('woocommerce_cart_totals_coupon_html', [$this, 'shipping_discount_coupon'], 10, 2);
    }

    function apply_shipping_discount($rates, $package){

        $applied_coupons = WC()->cart->get_applied_coupons();

        if(empty($applied_coupons)){
            return $rates;
        }

        foreach($applied_coupons as $coupon_code){
            $coupon_code_id = wc_get_coupon_id_by_code($coupon_code);
            $shipping_discount_method = get_post_meta($coupon_code_id, 'pi_acblw_shipping_discount_method', true);
            $all_shipping_discount_type = get_post_meta($coupon_code_id, 'pi_acblw_all_shipping_discount_type', true);
            $all_shipping_discount_amount = (float)get_post_meta($coupon_code_id, 'pi_acblw_all_shipping_discount_amount', true);

            if($shipping_discount_method == 'all'){
                $this->applyDiscount($rates, $coupon_code);
            }
        }

        return $rates;
    }

    function applyDiscount(&$rates,  $coupon_code){
        $coupon_code_id = wc_get_coupon_id_by_code($coupon_code);

        if(empty($coupon_code_id)){
            return;
        }

        $discount_method = get_post_meta($coupon_code_id, 'pi_acblw_shipping_discount_method', true);
        $discount_type = get_post_meta($coupon_code_id, 'pi_acblw_all_shipping_discount_type', true);
        $amount = (float)get_post_meta($coupon_code_id, 'pi_acblw_all_shipping_discount_amount', true);

        if($discount_method == 'all'){
            $this->applyDiscountToAll($rates, $discount_type, $amount, $coupon_code_id, $coupon_code);
        }

        
    }

    function applyDiscountToAll(&$rates, $discount_type, $amount, $coupon_code_id, $coupon_code){

        $excluded_rates = $this->excludedShippingMethods($coupon_code_id);

        foreach ( $rates as $rate_key => $rate ) {
            
            if ( in_array( $rate_key, $excluded_rates ) ) {
                continue;
            }

            $original_cost = $rate->get_cost();

            $rate->__set('original_cost', $original_cost);

            $rate->__set('coupon_code', $coupon_code);

            if ( $discount_type === 'percent' ) {
                // Apply percentage discount
                $discounted_cost = max( $original_cost * ( 1 - $amount / 100 ), 0 );
            } elseif ( $discount_type === 'flat' ) {
                // Apply fixed amount discount
                $discounted_cost = max( $original_cost - $amount, 0 );
            }else{
                $discounted_cost = $amount;
            }
            $rate->set_cost( $discounted_cost );

            $original_taxes = $rate->get_taxes();
            $new_taxes = array();

            if ( ! empty( $original_taxes ) && $original_cost > 0 ) {
                foreach ( $original_taxes as $tax_id => $original_tax_amount ) {
                    // Calculate the tax rate percentage
                    $tax_percentage = $original_tax_amount / $original_cost;
                    
                    // Apply the same tax percentage to the new shipping cost
                    $new_taxes[ $tax_id ] = $discounted_cost * $tax_percentage;
                }
                $rate->set_taxes( $new_taxes );
            }

            $message = $this->get_rate_message($rate);
            $rate->__set('shipping_discount_message', $message);
            $rate->__set('description', $message);

        }
    }

    function excludedShippingMethods($coupon_code_id){
        $zone_exclude_methods = (array)get_post_meta($coupon_code_id, 'pi_acblw_all_excluded_zone_methods', true);
        $excluded_methods = (array)get_post_meta($coupon_code_id, 'pi_acblw_all_excluded_dynamic_methods', true);

        $excluded_methods = array_merge($zone_exclude_methods, $excluded_methods);

        return array_unique($excluded_methods);
    }

    function get_rate_message($rate){
        $discounted_cost = $rate->get_cost();

        $original_cost = $rate->__get('original_cost');
        $coupon_code = $rate->__get('coupon_code');

        $message = '';
        if($original_cost !== "" && $original_cost != $discounted_cost){
            $values = [
                '[original_cost]' => wc_price($original_cost),
                '[discounted_cost]' => wc_price($discounted_cost),
                '[coupon_code]' => '<b>'.$coupon_code.'</b>'
            ];

            $message = __('Original shipping cost: [original_cost] - discounted by coupon [coupon_code] to [discounted_cost]', 'add-coupon-by-link-woocommerce');
            $message = strtr($message, $values);
        }
        return $message;
    }

    function shop_original_shipping_costs($rate, $index){
        $message = $rate->__get('shipping_discount_message');

        if(empty($message)) return;

        echo '<small class="shipping-original-cost">'.wp_kses_post($message).'</small>';
    }

    function shipping_discount_coupon($html, $coupon){
        $amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
        $coupon_code_id = $coupon->get_id();
        $discount_method = get_post_meta($coupon_code_id, 'pi_acblw_shipping_discount_method', true);
        if($amount == 0 && !empty($discount_method)){
            $coupon_html          = ' <a href="' . esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), Constants::is_defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->get_code() ) . '">' . __( '[Remove]', 'add-coupon-by-link-woocommerce' ) . '</a>';
            $html = 'Shipping discount coupon '. $coupon_html;
        }

        return $html;
    }
}
ShippingDiscountManager::get_instance();