<?php
namespace PISOL\ACLW\CONDITION;

class Coupon_Meta_Box {
    /**
     * The single instance of this class
     *
     * @var Order_Tags
     */
    private static $instance = null;

    /**
     * Main instance
     * 
     * Ensures only one instance is loaded or can be loaded.
     *
     * @return Order_Tags
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        
        // Add meta box for conditions
        add_action('add_meta_boxes', array($this, 'add_conditions_meta_box'));
        
        // Save conditions when post is saved
        add_action('save_post_shop_coupon', array($this, 'save_conditions'), 10, 3);
        
        // Enqueue scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        
    }

    /**
     * Add meta box for tag conditions
     */
    public function add_conditions_meta_box() {
        add_meta_box(
            'pisol_aclw_conditions',
            __('Advance Conditions', 'add-coupon-by-link-woocommerce'),
            array($this, 'render_conditions_meta_box'),
            'shop_coupon',
            'normal',
            'low'
        );
    }

     /**
     * Render the conditions meta box content
     *
     * @param \WP_Post $post The post object.
     */
    public function render_conditions_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('pisol_aclw_save_conditions', 'pisol_aclw_conditions_nonce');

        $conditions = get_post_meta($post->ID, '_pisol_aclw_conditions', true);
        $conditions = $conditions ? $conditions : array();
        
        // Debug - show the stored conditions (remove in production)
        // echo '<pre>' . esc_html(print_r($conditions, true)) . '</pre>';
        
        $condition_types = $this->get_condition_types();
        $pro_conditions = $this->get_pro_conditions();
        $logic_operators = array('AND', 'OR');
        
        // Include the template for conditions form
        include plugin_dir_path(__FILE__) . 'templates/conditions-form.php';
    }

    /**
     * Get all available condition types from condition classes
     *
     * @return array Array of condition types
     */
    private function get_condition_types() {
        $condition_types = array();
        
        // Apply filter to allow condition classes to register themselves
        $condition_types = apply_filters('pisol_aclw_condition_types', $condition_types);
        
        return $condition_types;
    }

    private function get_pro_conditions() {
        $pro_conditions = array();
        
        // Apply filter to allow pro conditions to register themselves
        $pro_conditions = apply_filters('pisol_aclw_pro_conditions', $pro_conditions);
        
        return $pro_conditions;
    }

    /**
     * Save conditions when the post is saved
     *
     * @param int $post_id The post ID.
     * @param \WP_Post $post The post object.
     * @param bool $update Whether this is an existing post being updated.
     */
    public function save_conditions($post_id, $post, $update) {
        // Check if nonce is valid
        if (!isset($_POST['pisol_aclw_conditions_nonce']) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['pisol_aclw_conditions_nonce'])), 'pisol_aclw_save_conditions')) {
            return;
        }

        // Check if user has permission
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Don't save during autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check if correct post type
        if ('shop_coupon' !== $post->post_type) {
            return;
        }

        // Sanitize and save conditions
        if (isset($_POST['pisol_aclw_conditions']) && is_array($_POST['pisol_aclw_conditions'])) {
            // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            $raw_conditions = wp_unslash($_POST['pisol_aclw_conditions']);
            $conditions = $this->sanitize_conditions($raw_conditions);
            
            update_post_meta($post_id, '_pisol_aclw_conditions', $conditions);
        } else {
            delete_post_meta($post_id, '_pisol_aclw_conditions');
        }
    }

    private function sanitize_conditions($conditions) {
        $sanitized = array();
        
        if (!is_array($conditions)) {
            return $sanitized;
        }
        
        
        
        foreach ($conditions as $group_id => $group) {
            // Skip empty groups (no conditions or invalid data)
            if (empty($group['conditions']) || !is_array($group['conditions'])) {
                continue;
            }
            
            // Initialize this group in the sanitized array
            $sanitized[$group_id] = array(
                'match_type' => isset($group['match_type']) && in_array($group['match_type'], array('all', 'any')) 
                    ? sanitize_text_field($group['match_type']) 
                    : 'all',
                'conditions' => array(),
            );
            
            // Add logic operator for group (except first group)
            if (isset($group['logic'])) {
                $sanitized[$group_id]['logic'] = in_array($group['logic'], array('AND', 'OR')) 
                    ? $group['logic'] 
                    : 'AND';
            }
            
            // Process each condition in this group
            foreach ($group['conditions'] as $condition_id => $condition) {
                // Skip empty conditions
                if (empty($condition['type'])) {
                    continue;
                }
            
                
                $sanitized[$group_id]['conditions'][$condition_id] = array(
                    'type' => sanitize_text_field($condition['type']),
                    'operator' => isset($condition['operator']) ? sanitize_text_field($condition['operator']) : '',
                );
                
                // We no longer need logic for individual conditions
                // The group's match_type handles this logic now
                
                // Handle different value types (array for multi-select or string for regular fields)
                if (isset($condition['value'])) {
                    if (is_array($condition['value'])) {
                        // For multi-level arrays (like in Previous_Orders_By_Category)
                        $sanitized_values = $this->sanitize_array_recursive($condition['value']);
                        $sanitized[$group_id]['conditions'][$condition_id]['value'] = $sanitized_values;
                        
                    } else {
                        $sanitized[$group_id]['conditions'][$condition_id]['value'] = sanitize_text_field($condition['value']);
                    }
                } else {
                    $sanitized[$group_id]['conditions'][$condition_id]['value'] = '';
                }
            }
            
            // If no valid conditions in this group after sanitizing, remove the group
            if (empty($sanitized[$group_id]['conditions'])) {
                unset($sanitized[$group_id]);
            }
        }
        
        return $sanitized;
    }

    /**
     * Recursively sanitize an array
     * 
     * @param array $array The array to sanitize
     * @return array The sanitized array
     */
    private function sanitize_array_recursive($array) {
        $sanitized = array();
        
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $sanitized[sanitize_key($key)] = $this->sanitize_array_recursive($value);
            } else {
                $sanitized[sanitize_key($key)] = sanitize_text_field($value);
            }
        }
        
        return $sanitized;
    }

    public function enqueue_scripts($hook) {
        global $post_type;
        
        // Only enqueue on our post type edit screens
        if ('shop_coupon' !== $post_type) {
            return;
        }
        
        // First check if WooCommerce is active before using WC()
        if (!function_exists('WC')) {
            return;
        }
        
        // Enqueue WooCommerce admin styles with correct handle
        wp_enqueue_style('woocommerce_admin_styles');
        
        // Enqueue SelectWoo script without making it a dependency
        wp_enqueue_script('selectWoo');
        
        // Enqueue our admin styles with no Select2 dependency
        wp_enqueue_style(
            'pisol-aclw-admin',
            plugin_dir_url(__FILE__) . 'assets/css/condition.css',
            array(),
            '1.0.0'
        );
        
        wp_enqueue_script(
            'pisol-aclw-conditions',
            plugin_dir_url(__FILE__) . 'assets/js/conditions.js',
            array('jquery', 'jquery-ui-sortable', 'wp-util', 'selectWoo'),
            '1.0.0',
            true
        );
        
        // Localize script with data
        wp_localize_script(
            'pisol-aclw-conditions',
            'pisol_aclw_conditions',
            array(
                'nonce' => wp_create_nonce('pisol_aclw_ajax_nonce'),
                'condition_types' => $this->get_condition_types(),
                'texts' => array(
                    'add_group' => __('Add Condition Group', 'add-coupon-by-link-woocommerce'),
                    'add_rule' => __('Add Condition', 'add-coupon-by-link-woocommerce'),
                    'remove' => __('Remove', 'add-coupon-by-link-woocommerce'),
                    'choose_condition' => __('Select Condition', 'add-coupon-by-link-woocommerce'),
                    'confirm_remove_group' => __('Are you sure you want to remove this condition group?', 'add-coupon-by-link-woocommerce'),
                )
            )
        );
    }

}