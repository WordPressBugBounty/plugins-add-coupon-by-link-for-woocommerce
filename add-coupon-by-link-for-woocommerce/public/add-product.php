<?php

class pisol_acblw_add_coupon_product{
    public $cart_items = array();

    static $instance = null;

    static function get_instance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        add_action('pisol_acblw_add_product', [$this, 'addProducts']);
    }

    function addProducts($coupon_code){
        /**
         * this is needed else it will lead to loop
         */
        if ( did_action( 'pisol_acblw_add_product' ) > 1 ) {
            return; // Skip further execution
        }

        $coupon = new WC_Coupon( $coupon_code );

        if(is_object($coupon) && $coupon->get_id() != 0){
            $product_ids = $coupon->get_meta( 'pisol_auto_add_products' );
            $this->addProductsToCart($product_ids);
        }
    }

    function addProductsToCart($product_ids){
        if(empty($product_ids) || !is_array($product_ids)) return;

        foreach($product_ids as $product_id){
            if(!$this->is_product_in_cart($product_id)){
                WC()->cart->add_to_cart( $product_id ); 
            }
        }
    }

    function is_product_in_cart($product_id){
        if(empty($this->cart_items)){
            $this->cart_items = WC()->cart->get_cart();
        }

        foreach ( $this->cart_items as $cart_item_key => $product ) {
            if($product_id == $product['product_id'] || $product_id == $product['variation_id']){
                return true;
            }
        }
        return false;
    }
    
}

pisol_acblw_add_coupon_product::get_instance();

