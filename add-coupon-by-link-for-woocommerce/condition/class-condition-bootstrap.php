<?php 
namespace PISOL\ACLW\CONDITION;

if(!defined('ABSPATH')){
    return;
}

class Condition_Bootstrap{
    static $instance = null;

    public static function get_instance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(){
        Coupon_Meta_Box::get_instance();

        //All conditions
        new Billing_Country();
        new Product_Category();
        new Cart_Quantity();
        new Virtual_Product_Quantity();
        new Non_Virtual_Product_Quantity();
        new Cart_Subtotal();
        new Previous_Orders_By_Category();
        new Login_Status();
        new User_Role();
        new Custom_Product_Taxonomy();
        new Product_Meta_Data(); // New condition for product meta data

        Condition_Validation::get_instance();
    }   
}

Condition_Bootstrap::get_instance();