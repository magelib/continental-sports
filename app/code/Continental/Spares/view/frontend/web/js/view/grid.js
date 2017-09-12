define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'Continental_Spares/js/view/grid/price'
    ],
    function ($, ko, component, priceRender) {
        "use strict";

        return component.extend({
            items: ko.observableArray([]),
            columns: ko.observableArray([]),
            defaults: {
                template: 'Continental_Spares/grid',
            },
            initialize: function () {
                this._super();
                this._render();
            },
            _render: function() {
                this._prepareColumns();
                this._prepareItems();
            },
            _prepareItems: function () {
                var items = [
                    {name: "Well-Travelled Kitten", sales: 352, price: 75.95},
                    {name: "Speedy Coyote", sales: 89, price: 190.00},
                    {name: "Furious Lizard", sales: 152, price: 25.00},
                    {name: "Indifferent Monkey", sales: 1, price: 99.95},
                    {name: "Brooding Dragon", sales: 0, price: 6350},
                ];

                this.addItems(items);
            },

            _prepareColumns: function () {
                this.addColumn({headerText: "Item Name", rowText: "name", renderer: ''});
                this.addColumn({headerText: "Sales Count", rowText: "sales", renderer: ''});
                this.addColumn({headerText: "Price", rowText: "price", renderer: priceRender()});
            },
            addItem: function (item) {
                item.columns = this.columns;
                this.items.push(item);
            },
            addItems: function (items) {
                for (var i in items) {
                    this.addItem(items[i]);
                }
            },
            addColumn: function (column) {
                this.columns.push(column);
            }
        });
    }
);