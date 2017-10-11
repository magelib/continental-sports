define([
    'jquery',
    'underscore',
    'ko',
    'uiComponent',
    'Magento_Ui/js/form/element/ui-select',
    'uiLayout'
], function ($, _, ko, Component, uiSelect, uiLayout) {
    'use strict';
    
    var SearchableAttribute = function (attribute, weight) {
        this.attribute = ko.observable(attribute);
        this.weight = ko.observable(weight);
        this.guid = guid();
        this.isSubscribed = false;
        
        this.subscribe = function (fnc) {
            if (this.isSubscribed) {
                return
            }
            
            this.attribute.subscribe(fnc);
            this.weight.subscribe(fnc);
            this.isSubscribed = true;
        }.bind(this);
        
        function guid() {
            function s4() {
                return Math.floor((1 + Math.random()) * 0x10000)
                    .toString(16)
                    .substring(1);
            }
            
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
        }
    };
    
    return Component.extend({
        defaults: {
            template:     'Mirasvit_Search/component/attributes',
            attributes:   [],
            weights:      [],
            weightSource: [],
            instances:    {},
            
            links:   {
                index: '${ $.provider }:${ $.dataScope }'
            },
            exports: {
                index: '${ $.provider }:${ $.dataScope }'
            },
            listens: {
                index: 'handleIndexChange'
            }
        },
        
        initialize: function () {
            this._super();
            
            _.bindAll(this, 'handleAdd', 'handleDelete', 'synchronize');
            
            _.each(this.index().attributes, function (weight, attribute) {
                this.weights.push(new SearchableAttribute(attribute, weight));
            }.bind(this));
            
            for (var i = 1; i <= 10; i++) {
                this.weightSource.push({
                    label: i,
                    value: i
                })
            }
            
            this.handleIndexChange();
            
            return this;
        },
        
        initObservable: function () {
            this._super();
            
            this.index = ko.observable();
            this.weights = ko.observableArray();
            this.attributes = ko.observableArray();
            
            this.weights.subscribe(function (items) {
                items.forEach(function (item) {
                    item.subscribe(this.synchronize);
                }.bind(this));
                
                this.synchronize();
            }.bind(this));
            
            return this;
        },
        
        handleAdd: function () {
            this.weights.push(new SearchableAttribute('', 1));
        },
        
        handleDelete: function ($data) {
            this.weights.remove($data)
        },
        
        handleIndexChange: function () {
            var attributes = this.instances[this.index()['identifier']];
            
            if (attributes) {
                this.attributes([]);
                
                ko.utils.objectForEach(attributes, function (value, label) {
                    this.attributes.push({
                        label: label,
                        value: value
                    })
                }.bind(this));
            }
        },
        
        synchronize: function () {
            var attributes = {};
            _.each(this.weights(), function (item) {
                attributes[item.attribute()] = item.weight()
            });
            
            var index = this.index();
            index.attributes = attributes;
            this.index(index);
        },
        
        attributesSelect: function ($data) {
            var config = {
                'Magento_Ui/js/core/app': {
                    'components': {}
                }
            };
            
            config['Magento_Ui/js/core/app']['components'][$data.guid] = {
                component:     'Magento_Ui/js/form/element/ui-select',
                template:      'ui/form/field',
                elementTmpl:   'ui/grid/filters/elements/ui-select',
                componentType: 'field',
                formElement:   'select',
                labelVisible:  false,
                filterOptions: true,
                showCheckbox:  false,
                disableLabel:  true,
                multiple:      false,
                options:       this.attributes(),
                value:         $data.attribute
            };
            
            return config;
        }
        
    })
});
