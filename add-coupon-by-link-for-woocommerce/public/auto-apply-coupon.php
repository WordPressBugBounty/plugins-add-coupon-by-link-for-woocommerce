<?php 

namespace PISOL\ACBLW;

class AutoApplyCoupon{

    static $instance = null;

    static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        //we are shifting from  woocommerce_before_calculate_totals to after, as in before min, max subtotal rule dont work as total are not yet calculated and we are applying coupon before that
        add_action('woocommerce_after_calculate_totals', [$this, 'auto_apply_coupons_to_cart'], PHP_INT_MAX, 1);
    }

    function auto_apply_coupons_to_cart() {
        if ( ! WC()->cart ) {
            return;
        }

        $coupons = get_posts( array(
            'post_type'   => 'shop_coupon',
            'post_status' => 'publish',
            'meta_key'    => 'pisol_acblw_auto_apply_coupon',
            'meta_value'  => '1',
            'fields'      => 'ids', // Only retrieve coupon IDs for efficiency
            'numberposts' => -1, // Get all coupons with auto apply set
        ) );
    
        if ( $coupons ) {
            foreach ( $coupons as $coupon_id ) {
                $coupon_code = get_the_title( $coupon_id );
                if ( ! WC()->cart->has_discount( $coupon_code ) ) {

                    $existing_notices = wc_get_notices();

                    $applied = WC()->cart->apply_coupon( $coupon_code );

                    if ( $applied ) {
                        // translators: %s: coupon code
                        wc_add_notice( sprintf( __( 'Coupon "%s" automatically applied.', 'add-coupon-by-link-woocommerce' ), $coupon_code ) );
                        WC()->cart->calculate_totals(); // Recalculate totals
                    }else{
                        $new_notices = wc_get_notices();
                        wc_clear_notices();
                    }
                }
            }
        }
    }
}

AutoApplyCoupon::get_instance();