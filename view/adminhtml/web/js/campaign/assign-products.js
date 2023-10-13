define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedProducts = config.selectedProducts,
            campaignProducts = $H(selectedProducts),
            gridJsObject = window[config.gridJsObjectName];

        /**
         * Show selected product when edit form in associated product grid
         */
        $('in_campaign_products').value = Object.toJSON(campaignProducts);
        /**
         * Register Campaign Product
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerCampaignProduct(grid, element, checked) {

            if (checked) {
                campaignProducts.set(element.value, element.value);
            } else {
                campaignProducts.unset(element.value);
            }

           // console.log('campaignProducts 111', campaignProducts.keys())

            $('in_campaign_products').value = Object.toJSON(campaignProducts);
            grid.reloadParams = {
                'selected_products[]': campaignProducts.keys()
            };
        }

        /**
         * Click on product row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function campaignProductRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                isInput = Event.element(event).tagName === 'INPUT',
                checked = false,
                checkbox = null;

            if (trElement) {
                checkbox = Element.getElementsBySelector(trElement, 'input');

                if (checkbox[0]) {
                    checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                }
            }
        }

        /**
         * Initialize campaign product row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function campaignProductRowInit(grid, row) {
            var checkbox = $(row).getElementsByClassName('checkbox')[0];

            if (checkbox) {
                campaignProducts.set(checkbox.value, checkbox.value);
            } else {
                campaignProducts.unset(checkbox.value);
            }

            console.log('campaignProducts 222', campaignProducts.keys())
        }

        gridJsObject.rowClickCallback = campaignProductRowClick;
        gridJsObject.initRowCallback = campaignProductRowInit;
        gridJsObject.checkboxCheckCallback = registerCampaignProduct;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                campaignProductRowInit(gridJsObject, row);
            });
        }
    };
});
