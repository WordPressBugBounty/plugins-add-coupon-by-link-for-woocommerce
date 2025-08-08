<?php
namespace PISOL\ACLW\FRONT;

use Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema;

class Cart_Block{

    private $extend;

    protected static $instance = null;

    static $IDENTIFIER = 'pisol_aclw';


    public static function get_instance( ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

    function __construct(){
        add_action('init', [$this, 'register_cart_block']);

        add_action('woocommerce_blocks_loaded', [$this, 'register_block']);

        add_action('woocommerce_blocks_loaded', [$this, 'add_data']);
    }

    function register_cart_block(){
        register_block_type('aclw/cart');
    }

    /**
     * This is needed else the block will not work in the Block based Wordpress themes
     */
    function register_block(){
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cart-block-integration.php';
        add_action(
            'woocommerce_blocks_checkout_block_registration',
            function( $integration_registry ) {
                $integration_registry->register( new Cart_Block_Integration() );
            }
        );
    }

    function add_data(){
        woocommerce_store_api_register_endpoint_data(
            array(
                'endpoint' => CartSchema::IDENTIFIER,
                'namespace' => self::$IDENTIFIER,
                'data_callback' => [__CLASS__, 'data'],
                'schema_type' => ARRAY_A,
                )
        );
    }

    static function data(){
        $reload = WC()->session->get('aclw_reload_cart', false);
        $data = [];
        if ( $reload ) {
            WC()->session->set('aclw_reload_cart', false);
            $data['reload'] = true;
        }
        return $data;
    }
    
}

Cart_Block::get_instance();