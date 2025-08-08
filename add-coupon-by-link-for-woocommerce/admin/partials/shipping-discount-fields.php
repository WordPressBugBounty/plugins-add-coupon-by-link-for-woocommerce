<?php

woocommerce_wp_select(
    array(
        'id'                => 'pi_acblw_shipping_discount_method',
        'name'              => 'pi_acblw_shipping_discount_method',
        'label'             => __('Give shipping discount', 'add-coupon-by-link-woocommerce'),
        'description'       => __('Give shipping discount', 'add-coupon-by-link-woocommerce'),
        'desc_tip'          => true,
        'value'             => $shipping_discount_method,
        'options'           => [
            '' => __('No shipping discount', 'add-coupon-by-link-woocommerce'),
            'all' => __('All shipping methods', 'add-coupon-by-link-woocommerce'),
        ],
        'class'             => 'wc-enhanced-select',
    )
);

echo '<div id="all-shipping-method-discounted">';

woocommerce_wp_select(
    array(
        'id'                => 'pi_acblw_all_shipping_discount_type',
        'name'              => 'pi_acblw_all_shipping_discount_type',
        'label'             => __('Discount type', 'add-coupon-by-link-woocommerce'),
        'description'       => __('You can give Percentage discount, fixed amount discount or change the value of the shipping', 'add-coupon-by-link-woocommerce'),
        'desc_tip'          => true,
        'value'             => $all_shipping_discount_type,
        'options'           => [
            'percent' => __('Percentage', 'add-coupon-by-link-woocommerce'),
            'flat' => __('Flat discount', 'add-coupon-by-link-woocommerce'),
            'overwrite' => __('Overwrite value', 'add-coupon-by-link-woocommerce'),
        ],
        'class'             => 'wc-enhanced-select',
    )
);

woocommerce_wp_text_input(
    array(
        'id'                => 'pi_acblw_all_shipping_discount_amount',
        'name'              => 'pi_acblw_all_shipping_discount_amount',
        'label'             => __('Shipping discount amount', 'add-coupon-by-link-woocommerce'),
        'description'       => __('Shipping discount amount', 'add-coupon-by-link-woocommerce'),
        'desc_tip'          => true,
        'value'             => $all_shipping_discount_amount,
        'class'             => 'wc_input_price',
    )
);

echo '<div class="exclude-shipping-methods">';
woocommerce_wp_select(
    array(
        'id'                => 'pi_acblw_all_excluded_zone_methods',
        'name'              => 'pi_acblw_all_excluded_zone_methods[]',
        'label'             => __('Excludes shipping methods', 'add-coupon-by-link-woocommerce'),
        'description'       => __('Excluded shipping method from the discount', 'add-coupon-by-link-woocommerce'),
        'desc_tip'          => true,
        'value'             => $all_excluded_zone_methods,
        'options'           => $shipping_methods_list,
        'custom_attributes' => array(
            'multiple' => 'multiple',
        ),
        'class'             => 'wc-enhanced-select',
    )
);

woocommerce_wp_text_input(
    array(
        'id'                => 'pi_acblw_all_excluded_dynamic_methods',
        'name'              => 'pi_acblw_all_excluded_dynamic_methods',
        'label'             => __('Exclude dynamic shipping method', 'add-coupon-by-link-woocommerce'),
        'description'       => __('Using this you can exclude the 3rd party shipping methods that are not part of any zone', 'add-coupon-by-link-woocommerce'),
        'desc_tip'          => true,
        'value'             => $all_excluded_dynamic_methods,
    )
);

echo '</div>';
echo '</div>';
