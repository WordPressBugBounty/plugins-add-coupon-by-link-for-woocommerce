<?php

class pisol_acblw_option{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'default';

    private $tab_name = "Basic setting";

    private $setting_key = 'acblw_basic_settting';

    public $tab;
    
    private $pro_version = false;

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        add_action('init', array($this,'init'));
        
        
    }    

    function init(){
        $this->settings = array(

            array('field'=>'pi_acblw_enable_url_coupon', 'label'=>__('Activate URL Coupon Feature','add-coupon-by-link-woocommerce'),'type'=>'switch', 'default'=>1, 'desc' => __('Enable this setting to allow coupons to be applied via URL links.','add-coupon-by-link-woocommerce')),

            array('field'=>'acblw_coupons_key','desc'=>__('Set the query string key to apply coupon via URL <br>(e.g., ?apply_coupon=CODE). Avoid spaces.','add-coupon-by-link-woocommerce'), 'label'=>__('Coupon URL Parameter','add-coupon-by-link-woocommerce'),'type'=>'text', 'default' => 'apply_coupon'),

            array('field'=>'acblw_coupon_added_to_session','desc'=>__('This appears when a conditional coupon is stored and waiting for its conditions to be met.','add-coupon-by-link-woocommerce'), 'label'=>__('Message: Coupon Saved in Session','add-coupon-by-link-woocommerce'),'type'=>'text', 'default' => __('Coupon saved in your session, it will be applied once coupon condition satisfied','add-coupon-by-link-woocommerce')),

            array('field'=>'acblw_before_coupon_applied','desc'=>__('Shown if coupon conditions aren\'t fulfilled. Explain what’s needed to apply it.','add-coupon-by-link-woocommerce'), 'label'=>__('Message: Conditions Not Yet Met','add-coupon-by-link-woocommerce'),'type'=>'text', 'default' => __('Coupon will be applied once its conditions are satisfied','add-coupon-by-link-woocommerce')),

            array('field'=>'pi_acblw_hide_coupon_cart','desc'=>'', 'label'=>__('Hide Cart Page Coupon Box','add-coupon-by-link-woocommerce'),'type'=>'switch', 'default'=>0, 'desc' => __('Remove the coupon input field from the cart page.','add-coupon-by-link-woocommerce')),

            array('field'=>'pi_acblw_hide_coupon_checkout','desc'=>'', 'label'=>__('Hide Checkout Page Coupon Box','add-coupon-by-link-woocommerce'),'type'=>'switch', 'default'=>0, 'desc' => __('Remove the coupon input field from the checkout page.','add-coupon-by-link-woocommerce')),

        );
        $this->register_settings();
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            pisol_class_form_acblw::register_setting( $this->setting_key, $setting);
        }
    
    }

    function tab(){
        $this->tab_name = __('Basic setting','add-coupon-by-link-woocommerce');
        ?>
        <li class="nav-item mb-0">
        <a class="nav-link text-light <?php echo esc_attr($this->active_tab == $this->this_tab ? 'active' : ''); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ) ); ?>">
            <img class="pi-icon" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>img/setting.svg"> <?php echo esc_html( $this->tab_name ); ?> 
        </a>
        </li>
        <?php
    }

    function tab_content(){
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_acblw($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="my-3 btn btn-primary btn-md" value="Save Option" />
        </form>
       <?php
    }

    
}

new pisol_acblw_option($this->plugin_name);