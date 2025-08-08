<?php

namespace PISOL\ACBLW\FRONT;

class Scheduler{

    static $instance = null;

    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    
    public function __construct( ) {
        add_action( 'woocommerce_coupon_is_valid', [__CLASS__, 'is_valid'], 10, 2 );
    }

    static function is_valid($valid, $coupon){
        /**
         * if some other rule has disabled the coupon, return false 
         */
        if(!$valid) return $valid;

        $scheduler = self::schedulingType($coupon);
        
        if(!$scheduler) return $valid;

        if($scheduler == 'date'){
            $valid = self::date_validation($valid, $coupon);
        }

        return $valid;
    }

    static function schedulingType($coupon){
        $date_based_scheduling_enabled = $coupon->get_meta('pisol_aclw_date_based_scheduling_enabled');

        if(!empty($date_based_scheduling_enabled)){
            return 'date';
        }

        return false;
    }

    static function date_validation($valid, $coupon){

        $date_based_scheduling_enabled = $coupon->get_meta('pisol_aclw_date_based_scheduling_enabled');
        $date_schedules = $coupon->get_meta('pisol_aclw_date_based_scheduling');
        $warning_msg = $coupon->get_meta('pisol_aclw_date_based_scheduling_warning_msg');

        if(!empty($date_based_scheduling_enabled)){
            $today_date = current_time('Y-m-d');
            $todays_date_time = strtotime(current_time('Y-m-d H:i'));

            if(!is_array($date_schedules) || empty($date_schedules)){
                if($warning_msg) wc_add_notice($warning_msg, 'error');
                return false;
            }

            foreach($date_schedules as $schedule){
                if(empty($schedule['from']) || empty($schedule['to'])){
                    continue;
                }

                if(strtotime($schedule['from']) <= $todays_date_time && strtotime($schedule['to']) >= $todays_date_time){
                    return true;
                }
            }

            if($warning_msg) wc_add_notice($warning_msg, 'error');
            
            return false;
        }

        return $valid;
    }
}

Scheduler::get_instance();