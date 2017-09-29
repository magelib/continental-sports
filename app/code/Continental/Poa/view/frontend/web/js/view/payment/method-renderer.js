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
                type: 'poa',
                component: 'Continental_Poa/js/view/payment/method-renderer/poa'
            }
        );
        return Component.extend({});
    }
);