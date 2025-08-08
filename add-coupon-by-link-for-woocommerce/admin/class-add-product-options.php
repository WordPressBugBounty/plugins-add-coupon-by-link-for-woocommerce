<?php

namespace PISOL\ACLW\ADMIN;

class Add_Product_Options{

    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function __construct( ) {
		add_filter( 'woocommerce_coupon_data_tabs', [$this,'add_product_tab'], 9);

        add_action( 'woocommerce_coupon_data_panels', [$this,'add_product_fields'],10 ,2 );

        add_action('woocommerce_coupon_options_save', [$this, 'save_coupon']);

        add_action( 'wp_ajax_pi_aclw_search_simple_products', [$this, 'search_products'] );
    }

    function add_product_tab($tabs) {
        $tabs['add_products'] = array(
            'label'    => __( 'Add Products', 'add-coupon-by-link-woocommerce' ),
            'target'   => 'add_products_coupon_data',
            'class'    => array(),
            'priority' => 10,
        );
        return $tabs;
    }

    function add_product_fields($coupon_id){
        $products = get_post_meta( $coupon_id, 'pi_acblw_products', true );
        ?>
        <div id="add_products_coupon_data" class="panel woocommerce_options_panel" style="padding:10px">
            <?php include_once PISOL_ACLW_FOLDER_PATH.'admin/partials/coupon-add-products-fields.php'; ?>
        </div>
        <?php
    }    
    
    function save_coupon($post_id) {
        if(isset($_POST['products']) && is_array($_POST['products'])) {
            $products = [];
            foreach ($_POST['products'] as $product) {
                if (isset($product['product_id']) && !empty($product['product_id'])) {
                    $products[] = [
                        'product_id' => sanitize_text_field($product['product_id']),
                        'qty' => isset($product['qty']) ? absint($product['qty']) : 1,
                        'discount_type' => sanitize_text_field($product['discount_type']),
                        'discount_amount' => isset($product['discount_amount']) ? sanitize_text_field($product['discount_amount']) : '',
                    ];
                }
            }
            update_post_meta($post_id, 'pi_acblw_products', $products);
        } else {
            delete_post_meta($post_id, 'pi_acblw_products');
        }
        
    }   

    function template($index = '{{index}}', $values = []){
        ?>
        <div class="pi-aclw-add-products-row">
            <select name="products[<?php echo esc_attr($index); ?>][product_id]" class="pi-acblw-search-product" style="width: 100%;" data-placeholder="Search for a product">

                <?php
                if ( ! empty( $values['product_id'] ) ) {
                    $product = wc_get_product( $values['product_id'] );
                    if ( $product ) {
                        echo '<option value="' . esc_attr( $product->get_id() ) . '" selected>' . esc_html( $product->get_name() . ' (#' . $product->get_id() . ')' ) . '</option>';
                    }
                }
                ?>

            </select>

            <input type="number" name="products[<?php echo esc_attr($index); ?>][qty]" placeholder="Quantity" min="1" value="<?php echo esc_attr( $values['qty'] ?? '' ); ?>" />

            <select name="products[<?php echo esc_attr($index); ?>][discount_type]" class="pi-aclw-discount-type">
                <option value="no-discount" <?php selected( $values['discount_type'] ?? '', 'no-discount' ); ?>><?php esc_html_e( 'No Discount', 'add-coupon-by-link-woocommerce' ); ?></option>
                <option value="change-price" <?php selected( $values['discount_type'] ?? '', 'change-price' ); ?>><?php esc_html_e( 'Change Price', 'add-coupon-by-link-woocommerce' ); ?></option>
                <option value="percentage_discount" <?php selected( $values['discount_type'] ?? '', 'percentage_discount' ); ?>><?php esc_html_e( 'Percentage discount', 'add-coupon-by-link-woocommerce' ); ?></option>
                <option value="fixed_price_discount" <?php selected( $values['discount_type'] ?? '', 'fixed_price_discount' ); ?>><?php esc_html_e( 'Fixed Price Discount', 'add-coupon-by-link-woocommerce' ); ?></option>
            </select>

            <div>
            <input type="number" name="products[<?php echo esc_attr($index); ?>][discount_amount]" placeholder="Amount" value="<?php echo esc_attr( $values['discount_amount'] ?? '' ); ?>" class="pi-aclw-product-discount-amount"/>
            </div>

            <button type="button" class="button remove-product-button"><?php esc_html_e( 'Remove', 'add-coupon-by-link-woocommerce' ); ?></button>
        </div>
        <?php
    }

    function search_products() {
        if ( ! current_user_can( 'edit_products' ) ) {
            wp_send_json( [] );
        }

        $term = isset( $_GET['term'] ) ? wc_clean( wp_unslash( $_GET['term'] ) ) : '';

        $query = new \WP_Query([
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => 25,
            's'              => $term,
            'tax_query'      => [
                [
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => [ 'simple' ],
                ],
            ],
        ]);

        $results = [];
        foreach ( $query->posts as $post ) {
            $results[ $post->ID ] = rawurldecode( $post->post_title );
        }

        wp_send_json( $results );
    }

}

Add_Product_Options::get_instance();    