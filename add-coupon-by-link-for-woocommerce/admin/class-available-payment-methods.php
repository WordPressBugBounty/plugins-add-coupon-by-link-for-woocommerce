<?php

namespace PISOL\ACBLW\ADMIN;

class AvailablePaymentMethods{

    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function __construct( ) {
		add_filter( 'woocommerce_coupon_data_tabs', [$this,'add_coupon_available_payment_methods_tab']);

        add_action( 'woocommerce_coupon_data_panels', [$this,'coupon_available_payment_methods_fields'],10 ,2 );

        add_action('woocommerce_coupon_options_save', [$this, 'save_coupon']);

        add_filter('woocommerce_available_payment_gateways', [$this, 'filterPaymentMethods'], PHP_INT_MAX-20);
    }

    function add_coupon_available_payment_methods_tab($tabs) {
        $tabs['available_payment_methods'] = array(
            'label'    => __( 'Available payment methods', 'add-coupon-by-link-woocommerce' ),
            'target'   => 'available_payment_methods_coupon_data',
            'class'    => array(),
            'priority' => 30,
        );
        return $tabs;
    }

    function coupon_available_payment_methods_fields($coupon_id){
        $payment_gateways = WC()->payment_gateways->get_available_payment_gateways();
        $available_payment_methods = get_post_meta( $coupon_id, 'pi_acblw_available_payment_methods', true );
        $available_payment_methods = is_array($available_payment_methods) ? $available_payment_methods : [];

        $exc_available_payment_methods = get_post_meta( $coupon_id, 'pi_acblw_exc_available_payment_methods', true );
        $exc_available_payment_methods = is_array($exc_available_payment_methods) ? $exc_available_payment_methods : [];

        $options = array();
        foreach ($payment_gateways as $gateway_id => $gateway) {
            $options[$gateway_id] = $gateway->get_title();
        }
        ?>
        <div id="available_payment_methods_coupon_data" class="panel woocommerce_options_panel">
            <?php include_once plugin_dir_path( dirname( __FILE__ ) ).'admin/partials/coupon-available-payment-methods-fields.php'; ?>
        </div>
        <?php
    }

    function save_coupon($post_id) {
        // Check if we have posted data for our custom field
        if (isset($_POST['pi_acblw_available_payment_methods']) && is_array($_POST['pi_acblw_available_payment_methods'])) {
            // Sanitize and decode JSON data
            $available_payment_methods = array_map('sanitize_text_field', $_POST['pi_acblw_available_payment_methods']);
    
            // Update or add custom meta field to the coupon
            update_post_meta($post_id, 'pi_acblw_available_payment_methods',  $available_payment_methods);
        }else{
            update_post_meta($post_id, 'pi_acblw_available_payment_methods', []);
        }

        if (isset($_POST['pi_acblw_exc_available_payment_methods']) && is_array($_POST['pi_acblw_exc_available_payment_methods'])) {
            // Sanitize and decode JSON data
            $exc_available_payment_methods = array_map('sanitize_text_field', $_POST['pi_acblw_exc_available_payment_methods']);
    
            // Update or add custom meta field to the coupon
            update_post_meta($post_id, 'pi_acblw_exc_available_payment_methods',  $exc_available_payment_methods);
        }else{
            update_post_meta($post_id, 'pi_acblw_exc_available_payment_methods', []);
        }
    }

    function filterPaymentMethods($gateways){
        //get applied coupons
        if(!function_exists('WC') || !WC()->cart || !is_object(WC()->cart)){
            return $gateways;
        }

        $applied_coupons = WC()->cart->get_applied_coupons();
        if(empty($applied_coupons)){
            return $gateways;
        }

        foreach($applied_coupons as $coupon_code){
            $coupon = new \WC_Coupon($coupon_code);  // Load the coupon object

            if (!$coupon->is_valid()) {
                continue;
            }
            
            $coupon_id = $coupon->get_id();
            self::filterAllowedGateways($gateways, $coupon_id);
            self::filterExcludedGateways($gateways, $coupon_id);
        }
        
        return $gateways;
    }

    static function filterAllowedGateways(&$gateways, $coupon_id){
        $available_payment_methods = get_post_meta( $coupon_id, 'pi_acblw_available_payment_methods', true );
        $available_payment_methods = is_array($available_payment_methods) ? $available_payment_methods : [];

        if(!empty($available_payment_methods)){
            foreach($gateways as $gateway_id => $gateway){
                if(!in_array($gateway_id, $available_payment_methods)){
                    unset($gateways[$gateway_id]);
                }
            }
        }
    }

    static function filterExcludedGateways(&$gateways, $coupon_id){
        $exc_available_payment_methods = get_post_meta( $coupon_id, 'pi_acblw_exc_available_payment_methods', true );
        $exc_available_payment_methods = is_array($exc_available_payment_methods) ? $exc_available_payment_methods : [];

        if(!empty($exc_available_payment_methods)){
            foreach($exc_available_payment_methods as $gateway_id){
                if(isset($gateways[$gateway_id])){
                    unset($gateways[$gateway_id]);
                }
            }
        }
    }
    
}

AvailablePaymentMethods::get_instance();
