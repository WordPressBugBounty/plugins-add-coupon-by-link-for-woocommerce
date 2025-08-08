<?php

namespace PISOL\ACBLW\ADMIN;

class ShippingDiscount{

    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function __construct( ) {
		add_filter( 'woocommerce_coupon_data_tabs', [$this,'add_shipping_discount_tab']);

        add_action( 'woocommerce_coupon_data_panels', [$this,'shipping_discount_data_panel'],10 ,2 );

        add_action('woocommerce_coupon_options_save', [$this, 'save_coupon']);

    }

    function add_shipping_discount_tab($tabs) {
        $tabs['shipping_discount'] = array(
            'label'    => __( 'Shipping discount', 'add-coupon-by-link-woocommerce' ),
            'target'   => 'shipping_discount_tab_data',
            'class'    => array(),
            'priority' => 40,
        );
        return $tabs;
    }

    function shipping_discount_data_panel($coupon_id){
        $shipping_discount_method = get_post_meta($coupon_id, 'pi_acblw_shipping_discount_method', true);
        $all_shipping_discount_type = get_post_meta($coupon_id, 'pi_acblw_all_shipping_discount_type', true);
        $all_shipping_discount_amount = get_post_meta($coupon_id, 'pi_acblw_all_shipping_discount_amount', true);   
        $all_excluded_zone_methods = (array)get_post_meta($coupon_id, 'pi_acblw_all_excluded_zone_methods', true);
        $all_excluded_dynamic_methods = (array)get_post_meta($coupon_id, 'pi_acblw_all_excluded_dynamic_methods', true);
        $all_excluded_dynamic_methods = implode(', ', $all_excluded_dynamic_methods);
        $shipping_methods_list = $this->get_woocommerce_shipping_methods_list();
        ?>
        <div id="shipping_discount_tab_data" class="panel woocommerce_options_panel">
            <?php include_once plugin_dir_path( dirname( __FILE__ ) ).'admin/partials/shipping-discount-fields.php'; ?>
        </div>
        <?php
    }

    function get_woocommerce_shipping_methods_list() {
        $shipping_methods_list = array();
    
        // Get all shipping zones
        $zones = \WC_Shipping_Zones::get_zones();
        
        // Include the "Locations not covered by other zones" zone
        $zones[0] = array('zone_name' => 'Rest of the World', 'zone_id' => 0, 'shipping_methods' => \WC_Shipping_Zones::get_zone(0)->get_shipping_methods());
    
        foreach ($zones as $zone) {
            $zone_name = $zone['zone_name'];
            $zone_id = $zone['zone_id'];
            $shipping_methods = $zone['shipping_methods'];
    
            foreach ($shipping_methods as $instance_id => $method) {
                $system_value = $method->get_rate_id();
                $display_name = $zone_name . ': ' . $method->get_title(). " ({$system_value})";
                $shipping_methods_list[$system_value] = $display_name;
            }
        }
    
        return $shipping_methods_list;
    }
    

    function save_coupon($post_id) {
        if (isset($_POST['pi_acblw_shipping_discount_method'])) {
            update_post_meta($post_id, 'pi_acblw_shipping_discount_method', sanitize_text_field($_POST['pi_acblw_shipping_discount_method']));
        }else{
            update_post_meta($post_id, 'pi_acblw_shipping_discount_method', '');
        }

        if (isset($_POST['pi_acblw_all_shipping_discount_type'])) {
            update_post_meta($post_id, 'pi_acblw_all_shipping_discount_type', sanitize_text_field($_POST['pi_acblw_all_shipping_discount_type']));
        }else{
            update_post_meta($post_id, 'pi_acblw_all_shipping_discount_type', '');
        }

        if (isset($_POST['pi_acblw_all_shipping_discount_amount'])) {
            update_post_meta($post_id, 'pi_acblw_all_shipping_discount_amount', sanitize_text_field($_POST['pi_acblw_all_shipping_discount_amount']));
        }else{
            update_post_meta($post_id, 'pi_acblw_all_shipping_discount_amount', '');
        }

        if (isset($_POST['pi_acblw_all_excluded_dynamic_methods'])) {
            $dynamic_methods = explode(',', sanitize_text_field($_POST['pi_acblw_all_excluded_dynamic_methods']));
            $dynamic_methods = array_map('trim', $dynamic_methods);
            update_post_meta($post_id, 'pi_acblw_all_excluded_dynamic_methods', $dynamic_methods);
        }else{
            update_post_meta($post_id, 'pi_acblw_all_excluded_dynamic_methods', []);
        }

        if (isset($_POST['pi_acblw_all_excluded_zone_methods']) && is_array($_POST['pi_acblw_all_excluded_zone_methods'])) {
            // Sanitize and decode JSON data
            $all_excluded_zone_methods = array_map('sanitize_text_field', $_POST['pi_acblw_all_excluded_zone_methods']);
    
            // Update or add custom meta field to the coupon
            update_post_meta($post_id, 'pi_acblw_all_excluded_zone_methods',  $all_excluded_zone_methods);
        }else{
            update_post_meta($post_id, 'pi_acblw_all_excluded_zone_methods', []);
        }
    }

    
}

ShippingDiscount::get_instance();
