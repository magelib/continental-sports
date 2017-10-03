/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function(targetModule){

        var reloadPrice = targetModule.prototype._reloadPrice;
        var reloadPriceWrapper = wrapper.wrap(reloadPrice, function(original){
            //do extra stuff
            alert("reloadPrice Called here...");
            //call original method
            var result = original();

            //do extra stuff
            var simpleSku = this.options.spConfig.skus[this.simpleProduct];

            if(simpleSku != '') {
                $('div.product-info-main .sku .value').html(simpleSku);
            } else {
                alert("set sku to master product");
            }


            //return original value
            return result;
        });

        targetModule.prototype._reloadPrice = reloadPriceWrapper;
        return targetModule;
    };
});
