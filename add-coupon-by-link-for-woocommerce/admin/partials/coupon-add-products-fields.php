
<button id="pi-aclw-add-product-button" type="button" class="button add-product-button">
    <?php esc_html_e( 'Add Product', 'add-coupon-by-link-woocommerce' ); ?>
</button>
<p>This feature lets you add product to user cart when they apply this coupon. The products listed in the table below will be automatically added to their cart in the specified quantities and price overrides.</p>
<p>The Add Products feature can also be combined with other features like Advanced Conditions and Auto Apply to make products appear in the customer's cart automatically once certain conditions are satisfied.</p>

<script type="html/javascript" id="add-product-fields-template">
    <?php echo $this->template(); ?>
</script>

<div id="add-product-fields-container">
    <?php 
    foreach($products as $index => $product) {
        echo $this->template($index, $product);
    }   
    ?>
</div>
<div id="pi-aclw-add-product-pro-message" style="display:none; margin-top:10px; padding:10px; background-color: rgb(166 30 105 / 1); box-shadow: 0px 4px 10px rgba(166, 30, 105, 0.3); border:1px solid #eee; color:#fff; text-align:center;">You can add unlimited product in pro version <a href="<?php echo esc_url( PISOL_ACBLW_BUY_URL ); ?>" style="color:#ccc;">Buy Pro</a></div>