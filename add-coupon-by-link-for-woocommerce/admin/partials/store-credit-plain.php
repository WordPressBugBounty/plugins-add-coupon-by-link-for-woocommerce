<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

echo "= " . esc_html( wp_strip_all_tags( $email_heading ) ) . " =\n\n";


echo esc_html( wp_strip_all_tags($content) ) . "\n\n";

echo esc_html( wp_strip_all_tags( $coupon_code ) ) . "\n\n";

if ( ! empty( $desc ) ) { 
    echo esc_html( wp_strip_all_tags( $desc ) ); 
}

