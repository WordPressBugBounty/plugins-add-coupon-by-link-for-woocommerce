<?php

namespace PISOL\ACLW\CONDITION;

class Common{
    static function get_guest_email() {
        $email = '';

        if (!empty($_POST['billing_email'])) {
            $email = sanitize_email(wp_unslash($_POST['billing_email']));
            if (!empty($email)) {
                return $email;
            }
        }

        if (function_exists('WC') && WC()->customer) {
            $cust_email = WC()->customer->get_billing_email();
            if (!empty($cust_email)) {
                return sanitize_email($cust_email);
            }
        }

        if (function_exists('WC') && WC()->session) {
            $sess_email = WC()->session->get('billing_email');
            if (!empty($sess_email)) {
                return sanitize_email($sess_email);
            }

            $sess_customer = WC()->session->get('customer');
            if (!empty($sess_customer) && is_array($sess_customer) && !empty($sess_customer['billing_email'])) {
                return sanitize_email($sess_customer['billing_email']);
            }
        }

        return '';
    }
}