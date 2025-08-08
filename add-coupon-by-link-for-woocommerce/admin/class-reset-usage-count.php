<?php
namespace PISOL\ACBLW\ADMIN;

class Reset_Usage_Count{

    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        add_action( 'woocommerce_coupon_options_usage_limit', [$this,'add_coupon_text_field'], 10,2 );
    }

    function add_coupon_text_field($coupon_id, $coupon) {
        $usage_limit = $coupon->get_meta( 'pisol_aclw_reset_usage_limit' );
        $user_data = $coupon->get_meta( 'pisol_aclw_reset_user_limit' );
        ?>
        <div class="free-version">
        <strong>Reset on start of new year, Reset on start of month, Reset on start of week, Reset on start of day</strong>
        <p class="form-field">
					<label><?php esc_html_e( 'Reset usage limit per coupon', 'add-coupon-by-link-woocommerce' ); ?></label>
					<select style="width: 50%;" name="pisol_aclw_reset_usage_limit">
                        <option value=""><?php esc_html_e( 'Never reset', 'add-coupon-by-link-woocommerce' ); ?></option>
                        <option value="yearly" <?php selected($usage_limit, 'yearly'); ?>><?php esc_html_e( 'Reset on start of new year', 'add-coupon-by-link-woocommerce' ); ?></option>
                        <option value="monthly" <?php selected($usage_limit, 'monthly'); ?>><?php esc_html_e( 'Reset on start of month', 'add-coupon-by-link-woocommerce' ); ?></option>
                        <option value="weekly" <?php selected($usage_limit, 'weekly'); ?>><?php esc_html_e( 'Reset on start of week', 'add-coupon-by-link-woocommerce' ); ?></option>
                        <option value="daily" <?php selected($usage_limit, 'daily'); ?>><?php esc_html_e( 'Reset on start of day', 'add-coupon-by-link-woocommerce' ); ?></option>
					</select>
		</p>
        
        <p class="form-field">
					<label><?php esc_html_e( 'Reset per user limit', 'add-coupon-by-link-woocommerce' ); ?></label>
					<select style="width: 50%;" name="pisol_aclw_reset_user_limit">
                        <option value=""><?php esc_html_e( 'Never reset', 'add-coupon-by-link-woocommerce' ); ?></option>
                        <option value="yearly" <?php selected($user_data, 'yearly'); ?>><?php esc_html_e( 'Reset on start of new year', 'add-coupon-by-link-woocommerce' ); ?></option>
                        <option value="monthly" <?php selected($user_data, 'monthly'); ?>><?php esc_html_e( 'Reset on start of month', 'add-coupon-by-link-woocommerce' ); ?></option>
                        <option value="weekly" <?php selected($user_data, 'weekly'); ?>><?php esc_html_e( 'Reset on start of week', 'add-coupon-by-link-woocommerce' ); ?></option>
                        <option value="daily" <?php selected($user_data, 'daily'); ?>><?php esc_html_e( 'Reset on start of day', 'add-coupon-by-link-woocommerce' ); ?></option>
					</select>
		</p>
        </div>
        <?php
    }
}

Reset_Usage_Count::get_instance();