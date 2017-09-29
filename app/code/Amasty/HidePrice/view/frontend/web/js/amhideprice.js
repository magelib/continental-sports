define([
    "jquery",
    "jquery/ui"
], function ($) {
    $.widget('mage.amhideprice', {
        options: {},
        _create: function () {
            if (this.element) {
                var parent = this.element.parents(this.options['parent']);
                if (!parent) {
                    return;
                }
                var button = parent.find(this.options['button']);
                if (button && button[0]) {
                    button[0].outerHTML = this.options['html'];
                }
                if (this.options['hide_compare'] === '1') {
                    parent.find('a.tocompare').remove();
                }
                if (this.options['hide_wishlist'] === '1') {
                    parent.find('a.towishlist').remove();
                }
            }
        }
    });

    return $.mage.amhideprice;
});
