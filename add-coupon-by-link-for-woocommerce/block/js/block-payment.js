/**
 * this reads the hook of set-active-payment-method and then update the payment method in the backend
 */

wp.hooks.addAction('experimental__woocommerce_blocks-checkout-render-checkout-form', 'woocommerce-block-checkout', () => {
    // Subscribe to changes in the PAYMENT_STORE_KEY
    const unsubscribe = wp.data.subscribe(() => {
        const paymentStore = wp.data.select(wc.wcBlocksData.PAYMENT_STORE_KEY);
        
        if (paymentStore) {
            const payment_method = paymentStore.getActivePaymentMethod();
            if (payment_method) {
                //console.log('payment_method', payment_method);

                // Trigger your custom action with the payment method data
                var data = { 'value': payment_method };
                wp.hooks.doAction('pisol_initial_checkout_load', data);

                // Unsubscribe after getting the payment method to prevent multiple calls
                unsubscribe();
            }
        }
    });
});

wp.hooks.addAction('experimental__woocommerce_blocks-checkout-set-active-payment-method', 'woocommerce-block-checkout', (data) => {
    pisol_set_payment_method(data.value);
}); 

wp.hooks.addAction('pisol_initial_checkout_load', 'woocommerce-block-checkout', (data) => {
    pisol_set_payment_method(data.value);
});

function pisol_set_payment_method(payment_method){
    if (typeof payment_method == 'undefined') return;
    if (payment_method == '') return;
    var data = { payment_method: payment_method };
    document.body.classList.add('pi-acblw-processing');
    wc.blocksCheckout.extensionCartUpdate({
        namespace: 'pisol_acblw_set_payment_method',
        data: data
    }).then(() => {
        document.body.classList.remove('pi-acblw-processing');
    });
}