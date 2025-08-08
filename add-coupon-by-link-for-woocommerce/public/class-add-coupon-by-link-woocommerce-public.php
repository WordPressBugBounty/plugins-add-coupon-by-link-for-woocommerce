<?php
class Add_Coupon_By_Link_Woocommerce_Public {

	
	private $plugin_name;

	
	private $version;

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	
	public function enqueue_styles() {


		

	}

	
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/add-coupon-by-link-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		
	}

}
