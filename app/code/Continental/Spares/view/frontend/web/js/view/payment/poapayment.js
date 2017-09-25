define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'poapayment',
                component: 'Continental_Spares/js/view/payment/method-renderer/poapayment-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);