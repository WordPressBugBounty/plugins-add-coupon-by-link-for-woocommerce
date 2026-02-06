<?php 

namespace PISOL\ACBLW\ADMIN;

class Promotion{

    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        add_action('add_meta_boxes', [__CLASS__,'add_custom_coupon_meta_box']);
        add_action('add-coupon-by-link-woocommerce_promotion', [__CLASS__,'setting_promotion']);
    }
   
    static function add_custom_coupon_meta_box() {
        add_meta_box(
            'pi_acblw_custom_coupon_meta_box', // ID
            'Custom Coupon Info', // Title
            [__CLASS__, 'custom_coupon_meta_box_callback'], // Callback function
            'shop_coupon', // Post type (WooCommerce coupon)
            'side', // Context (side panel)
            'high' // Priority
        );
    }

    static function setting_promotion(){
        echo '<div class="col-3 col-md-4 col-sm-12">';
        self::custom_coupon_meta_box_callback('shop_coupon');
        echo '</div>';
    }

    static function custom_coupon_meta_box_callback($post){
        ?>
        <div class="pisol-new-promotion-box-promotion-container">
            
            <div class="pisol-new-promotion-box-promotion">
            <div class="pisol-new-promotion-box-icon-container">
                <img class="pisol-new-promotion-box-icon" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>img/pi-web-solution-icon.svg">
            </div>
                <h4 class="mt-3">Get Premium <!--<a href="https://wordpress.org/support/plugin/disable-payment-method-for-woocommerce/reviews/?filter=5" target="_blank" class="pisol-new-promotion-box-promotion-footer-link">Trusted by <b>3000+</b> websites</a>--></h4>
                <div class="mt-3 bg-primary p-2 text-center text-light mb-2 rounded" id="pi-aclw-trusted-websites">Trusted by <b>1500+</b> websites</div>
                <ul class="feature-list">
                    <li class="py-2"><span>✓ Reset coupon usage data at interval</li>
                    <li class="py-2"><span>✓ Schedule coupon by days of the week</li>
                    <li class="py-2"><span>✓ Premium support</li>
                    <li class="py-2"><span>✓ More then 15+ advance conditions</li>
                    <li class="py-2"><span>✓ Add product by coupon</li>
                    <li class="py-2"><span>✓ Auto apply coupon, when condition matches</li>
                </ul>
                <div class="banner-price text-center text-light">
                    💰 <?php echo wp_kses_post(PISOL_ACBLW_PRICE); ?> <small>Billed yearly</small>
                </div>
                <a href="<?php echo esc_url(PISOL_ACBLW_BUY_URL); ?>" target="_blank" class="pisol-new-promotion-box-buy my-4">🔓 Unlock Pro Now – Limited Time Price!</a>
                
                <div class="pisol-new-promotion-box-promotion-footer text-light mt-2">
                    ⭐️⭐️⭐️⭐️⭐️ Rated 4.9/5 – Users love it
                </div>
                
            </div>
        </div>
        <?php
    }

}

Promotion::get_instance();