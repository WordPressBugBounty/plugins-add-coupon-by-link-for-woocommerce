<?php
namespace PISOL\ACLW\ADMIN;

class Auto_Apply_Coupon_Metabox {

    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        // Instead of registering as a metabox, we'll hook into a filter before the Advance Conditions metabox
        add_action('edit_form_after_editor', array($this, 'render_auto_apply_section'));
        add_action('woocommerce_coupon_options_save', array($this, 'save_auto_apply_option'), 10, 2);
        add_action('admin_head', array($this, 'add_custom_styles'));
    }

    /**
     * Add custom styles for the Auto Apply Coupon section
     */
    public function add_custom_styles() {
        $screen = get_current_screen();
        if (!$screen || 'shop_coupon' !== $screen->id) {
            return;
        }
        ?>
        <style>
            #pisol_acblw_auto_apply_section {
                background-color: #f7f7f7;
                padding: 15px 12px;
                margin: 10px 0;
                border-left: 4px solid #2271b1;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }
            
            #pisol_acblw_auto_apply_section h3 {
                margin-top: 0;
                padding-bottom: 8px;
                border-bottom: 1px solid #ddd;
            }
            
            #pisol_acblw_auto_apply_section .auto-apply-option {
                margin-top: 10px;
            }
            
            #pisol_acblw_auto_apply_section label {
                font-weight: bold;
                margin-bottom: 5px;
                display: inline-block;
            }
        </style>
        <?php
    }

    /**
     * Render the Auto Apply Coupon section
     */
    public function render_auto_apply_section($post) {
        // Only render on shop_coupon post type
        if (!$post || 'shop_coupon' !== $post->post_type) {
            return;
        }

        $coupon_id = $post->ID;
        $is_auto_apply = get_post_meta($coupon_id, 'pisol_acblw_auto_apply_coupon', true);
        ?>
        <div id="pisol_acblw_auto_apply_section">
            <h3><?php esc_html_e('Auto Apply Coupon', 'add-coupon-by-link-woocommerce'); ?></h3>
            <div class="auto-apply-option">
                <input type="checkbox" id="pisol_acblw_auto_apply_coupon" name="pisol_acblw_auto_apply_coupon" value="1" <?php checked($is_auto_apply, '1'); ?> />
                <label for="pisol_acblw_auto_apply_coupon">
                    <?php esc_html_e('Automatically apply this coupon when customer satisfies all the coupon conditions', 'add-coupon-by-link-woocommerce'); ?>
                </label>
            </div>
        </div>
        <?php
    }

    /**
     * Save the Auto Apply Coupon option
     */
    public function save_auto_apply_option($post_id, $coupon) {
        $id = $coupon->get_id();
        $auto_apply_coupon = isset($_POST['pisol_acblw_auto_apply_coupon']) ? '1' : '0';
        update_post_meta($id, 'pisol_acblw_auto_apply_coupon', $auto_apply_coupon);
    }
}

Auto_Apply_Coupon_Metabox::get_instance(); // New Auto Apply Coupon metabox