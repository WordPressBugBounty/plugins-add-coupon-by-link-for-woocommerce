<?php
namespace PISOL\ACLW\ADMIN;

if (!defined('ABSPATH')) {
    exit;
}

class Coupon_Taxonomy {
    
    private static $instance = null;

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('init', array($this, 'register_taxonomy'));
        add_filter('manage_edit-shop_coupon_columns', array($this, 'add_column_header'));
        add_action('manage_shop_coupon_posts_custom_column', array($this, 'add_column_content'), 10, 2);
    }

    public function register_taxonomy() {
        $labels = array(
            'name'              => _x('Coupon Groups', 'taxonomy general name', 'add-coupon-by-link-woocommerce'),
            'singular_name'     => _x('Coupon Group', 'taxonomy singular name', 'add-coupon-by-link-woocommerce'),
            'search_items'      => __('Search Coupon Groups', 'add-coupon-by-link-woocommerce'),
            'all_items'         => __('All Coupon Groups', 'add-coupon-by-link-woocommerce'),
            'parent_item'       => __('Parent Coupon Group', 'add-coupon-by-link-woocommerce'),
            'parent_item_colon' => __('Parent Coupon Group:', 'add-coupon-by-link-woocommerce'),
            'edit_item'         => __('Edit Coupon Group', 'add-coupon-by-link-woocommerce'),
            'update_item'       => __('Update Coupon Group', 'add-coupon-by-link-woocommerce'),
            'add_new_item'      => __('Add New Coupon Group', 'add-coupon-by-link-woocommerce'),
            'new_item_name'     => __('New Coupon Group Name', 'add-coupon-by-link-woocommerce'),
            'menu_name'         => __('Coupon Groups', 'add-coupon-by-link-woocommerce'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => false,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'coupon_group'),
            'show_in_rest'      => true
        );

        register_taxonomy('pisol_coupon_group', array('shop_coupon'), $args);
    }

    public function add_column_header($columns) {
        $columns['pisol_coupon_group'] = __('Coupon Groups', 'add-coupon-by-link-woocommerce');
        return $columns;
    }

    public function add_column_content($column, $post_id) {
        if ($column === 'pisol_coupon_group') {
            $terms = get_the_terms($post_id, 'pisol_coupon_group');
            if ($terms && !is_wp_error($terms)) {
                $term_links = array();
                foreach ($terms as $term) {
                    $term_links[] = sprintf(
                        '<a href="%s">%s</a>',
                        esc_url(add_query_arg(array('post_type' => 'shop_coupon', 'pisol_coupon_group' => $term->slug), 'edit.php')),
                        esc_html($term->name)
                    );
                }
                echo implode(', ', $term_links);
            } else {
                echo '<span aria-hidden="true">—</span>';
            }
        }
    }
}

Coupon_Taxonomy::get_instance();