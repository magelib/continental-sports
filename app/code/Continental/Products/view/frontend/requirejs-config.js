var config = {
    config: {
        mixins: {
            'Magento_ConfigurableProduct/js/configurable': {
                'Continental_Products/js/model/skuswitch': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'Continental_Products/js/model/swatch-skuswitch': true
            }
        }
    }
};
