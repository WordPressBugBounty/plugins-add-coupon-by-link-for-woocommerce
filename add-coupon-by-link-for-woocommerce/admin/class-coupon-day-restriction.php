<?php 
namespace PISOL\ACBLW\ADMIN;

class CouponDayRestriction {
    static $instance = null;

    static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct()
    {
        add_action( 'woocommerce_coupon_options_usage_restriction', [ $this, 'add_day_restriction_field' ],20 );

        add_action( 'woocommerce_coupon_options_save', [ $this, 'save_day_restriction_field' ] );

        add_action( 'woocommerce_coupon_is_valid', [ $this, 'validate_coupon_day_restriction' ], 10, 2 );
    }

    public function add_day_restriction_field( $coupon_id ) {
        // Get previously saved day restriction, if any
        $selected_day = get_post_meta( $coupon_id, '_acblw_coupon_restricted_day', true );
        
        // Day options for selection
        $days = [ 
            '0' => __( 'Sunday', 'add-coupon-by-link-woocommerce' ),
            '1' => __( 'Monday', 'add-coupon-by-link-woocommerce' ), 
            '2' => __( 'Tuesday', 'add-coupon-by-link-woocommerce' ), 
            '3' => __( 'Wednesday', 'add-coupon-by-link-woocommerce' ), 
            '4' => __( 'Thursday', 'add-coupon-by-link-woocommerce' ), 
            '5' => __( 'Friday', 'add-coupon-by-link-woocommerce' ), 
            '6' => __( 'Saturday', 'add-coupon-by-link-woocommerce' )
        ];

        echo '<div class="options_group">';
        //var_dump($selected_day);
        woocommerce_wp_select( [
            'id'          => '_acblw_coupon_restricted_day',
            'name'          => '_acblw_coupon_restricted_day[]',
            'label'       => __( 'Day Restriction', 'add-coupon-by-link-woocommerce' ),
            'description' => __( 'Select the day of the week this coupon can be used.', 'add-coupon-by-link-woocommerce' ),
            'value'       => $selected_day,
            'desc_tip'          => true,
            'options'     => $days,
            'class' => 'wc-enhanced-select',
            'custom_attributes' => array('multiple' => 'multiple')
        ] );
        echo '</div>';
    }

    public function save_day_restriction_field( $coupon_id ) {
        $days = isset( $_POST['_acblw_coupon_restricted_day'] ) ? array_map( 'sanitize_text_field', $_POST['_acblw_coupon_restricted_day'] ) : [];
        update_post_meta( $coupon_id, '_acblw_coupon_restricted_day', $days );
    }

    public function validate_coupon_day_restriction( $valid, $coupon ) {
        // Retrieve the restricted days from coupon metadata
        $restricted_days = get_post_meta( $coupon->get_id(), '_acblw_coupon_restricted_day', true );
    
        // Skip validation if no day restrictions are set
        if ( empty( $restricted_days ) ) {
            return $valid;
        }
    
        // Get the current day as an integer (0 = Sunday, 6 = Saturday)
        $current_day = (int) date( 'w' ); 
    
        // Define the day labels
        $days = [ 
            '0' => __( 'Sunday', 'add-coupon-by-link-woocommerce' ),
            '1' => __( 'Monday', 'add-coupon-by-link-woocommerce' ), 
            '2' => __( 'Tuesday', 'add-coupon-by-link-woocommerce' ), 
            '3' => __( 'Wednesday', 'add-coupon-by-link-woocommerce' ), 
            '4' => __( 'Thursday', 'add-coupon-by-link-woocommerce' ), 
            '5' => __( 'Friday', 'add-coupon-by-link-woocommerce' ), 
            '6' => __( 'Saturday', 'add-coupon-by-link-woocommerce' )
        ];
    
        // Check if today's integer representation is in the restricted days array
        if ( !in_array( $current_day, $restricted_days ) ) {
            // Convert integer days to readable day names
            $allowed_days = array_map( function( $day ) use ( $days ) {
                return $days[$day];
            }, $restricted_days );
    
            // Create a comma-separated list of valid days
            $allowed_days_list = implode( ', ', $allowed_days );
    
            /* translators: %s: human-readable, comma-separated list of days on which the coupon is valid (e.g., Monday, Tuesday). */
            wc_add_notice( sprintf( __( 'This coupon is only valid on %s.', 'add-coupon-by-link-woocommerce' ), $allowed_days_list ), 'error' );
            return false;
        }
    
        return $valid;
    }
    
}

CouponDayRestriction::instance();