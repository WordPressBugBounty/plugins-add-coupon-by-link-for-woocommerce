<?php

namespace PISOL\ACBLW\ADMIN;

class Scheduler{

    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function __construct( ) {
		add_filter( 'woocommerce_coupon_data_tabs', [__CLASS__,'add_shipping_discount_tab']);

        add_action( 'woocommerce_coupon_data_panels', [__CLASS__,'shipping_discount_data_panel'],10 ,2 );

        add_action('woocommerce_coupon_options_save', [__CLASS__, 'save_coupon']);

    }

    static function add_shipping_discount_tab($tabs) {
        $tabs['pisol_aclw_scheduler'] = array(
            'label'    => __( 'Scheduler', 'add-coupon-by-link-woocommerce' ),
            'target'   => 'pisol_aclw_scheduler',
            'class'    => array(),
            'priority' => 40,
        );
        return $tabs;
    }

    static function shipping_discount_data_panel($coupon_id){
        $day_based_scheduling_enabled = get_post_meta($coupon_id, 'pisol_aclw_day_based_scheduling_enabled', true);
        $day_based_scheduling = get_post_meta($coupon_id, 'pisol_aclw_day_based_scheduling', true);
        $warning_msg = get_post_meta($coupon_id, 'pisol_aclw_day_based_scheduling_warning_msg', true);
        $date_warning_msg = get_post_meta($coupon_id, 'pisol_aclw_date_based_scheduling_warning_msg', true);

        $date_schedule_enabled = get_post_meta($coupon_id, 'pisol_aclw_date_based_scheduling_enabled', true);
        $date_schedule = get_post_meta($coupon_id, 'pisol_aclw_date_based_scheduling', true);
        ?>
        <div id="pisol_aclw_scheduler" class="panel woocommerce_options_panel">
            <?php include_once plugin_dir_path( dirname( __FILE__ ) ).'admin/partials/scheduler.php'; ?>
        </div>
        <?php
    }

    static function save_coupon($post_id) {
        $date_based_schedule_enabled = isset($_POST['pisol_aclw_date_based_scheduling_enabled']) ? '1' : '0';

        $date_warning_msg = sanitize_text_field( $_POST['pisol_aclw_date_based_scheduling_warning_msg'] );

        $date_schedule = isset($_POST['pisol_aclw_date_based_scheduling']) && is_array($_POST['pisol_aclw_date_based_scheduling']) ? self::date_schedule_filter( $_POST['pisol_aclw_date_based_scheduling'] ) : [];

        update_post_meta($post_id, 'pisol_aclw_date_based_scheduling_enabled', $date_based_schedule_enabled);
        update_post_meta($post_id, 'pisol_aclw_date_based_scheduling', $date_schedule);
        update_post_meta($post_id, 'pisol_aclw_date_based_scheduling_warning_msg', $date_warning_msg);
    }

    static function date_schedule_filter($ranges){
        if(!is_array($ranges)){
            return [];
        }

        foreach($ranges as $key => $range){
            if(empty($range['from']) || empty($range['to'])){
                $ranges[$key]['from'] = '';
                $ranges[$key]['to'] = '';
            }

            //make sure from is less then to
            if(!empty($range['from']) && !empty($range['to'])){
                if(strtotime($range['from']) > strtotime($range['to'])){
                    //reverse swap them
                    $temp = $range['from'];
                    $ranges[$key]['from'] = $range['to'];
                    $ranges[$key]['to'] = $temp;
                }
            }
            
        }

        return array_values($ranges);
    }

    static function schedule_filtering($schedules){
        if(!is_array($schedules)){
            return [];
        }

        foreach($schedules as $key => $schedule){
            if(empty($schedule['enabled'])){
                $schedules[$key]['from'] = '';
                $schedules[$key]['to'] = '';
            }

            if(!empty($schedule['enabled']) && empty($schedule['from'])){
                $schedules[$key]['from'] = '00:00';
            }

            if(!empty($schedule['enabled']) && ( empty($schedule['to']) || $schedule['to'] == '00:00' )){
                $schedules[$key]['to'] = '23:59';
            }

            //make sure from is less then to
            if(!empty($schedule['enabled']) && !empty($schedule['from']) && !empty($schedule['to'])){
                if(strtotime($schedule['from']) > strtotime($schedule['to'])){
                    //reverse swap them
                    $temp = $schedule['from'];
                    $schedules[$key]['from'] = $schedule['to'];
                    $schedules[$key]['to'] = $temp;
                }
            }
            
        }

        return $schedules;
    }

    static function row_template($count = '{count}', $saved_value = []){
        ?>
        <div class="pi-display-flex mt-2 pisol-date-range">
            <div class="pi-display-flex">
                <span class="mx-2"><?php esc_html_e('from', 'add-coupon-by-link-woocommerce'); ?></span>
                <input type="text" class="pi-date-time" name="pisol_aclw_date_based_scheduling[<?php echo esc_attr($count); ?>][from]" value="<?php echo esc_attr($saved_value['from'] ?? ''); ?>">
                <span class="mx-2"><?php esc_html_e('to', 'add-coupon-by-link-woocommerce'); ?></span>
                <input type="text" class="pi-date-time" name="pisol_aclw_date_based_scheduling[<?php echo esc_attr($count); ?>][to]" value="<?php echo esc_attr($saved_value['to'] ?? ''); ?>">
                <button class="button pisol-date-schedule-remove" type="button"><?php esc_html_e('Remove', 'add-coupon-by-link-woocommerce'); ?></button>
            </div>
        </div>
        <?php
    }

    static function rows($saved_rows){
        foreach($saved_rows as $key => $row){
            self::row($key, $row);
        }
    }

    static function row($key, $row){
        self::row_template($key, $row);
    }
 
}

Scheduler::get_instance();