<?php

woocommerce_wp_select(
    array(
        'id'                => 'pi_acblw_available_payment_methods',
        'name'              => 'pi_acblw_available_payment_methods[]',
        'label'             => __('Payment method allowed for coupon', 'add-coupon-by-link-woocommerce'),
        'description'       => __('Only Following payment method will be allowed when this coupon is applied, and other payment methods will not be available. If left blank then all the payment method will be available', 'add-coupon-by-link-woocommerce'),
        'desc_tip'          => true,
        'value'             => $available_payment_methods,
        'options'           => $options,
        'custom_attributes' => array(
            'multiple' => 'multiple',
        ),
        'class'             => 'wc-enhanced-select',
    )
);

woocommerce_wp_select(
    array(
        'id'                => 'pi_acblw_exc_available_payment_methods',
        'name'              => 'pi_acblw_exc_available_payment_methods[]',
        'label'             => __('Not allowed payment method for coupon', 'add-coupon-by-link-woocommerce'),
        'description'       => __('Following payment method will not be allowed when this coupon is applied, and other payment methods will be available. If left blank then all the payment method will be available', 'add-coupon-by-link-woocommerce'),
        'desc_tip'          => true,
        'value'             => $exc_available_payment_methods,
        'options'           => $options,
        'custom_attributes' => array(
            'multiple' => 'multiple',
        ),
        'class'             => 'wc-enhanced-select',
    )
);