define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var campaignProducts = config.selectedProducts.split(','),
            gridJsObject = window[config.gridJsObjectName];

        /**
         * Show selected product when edit form in associated product grid
         */
        $('in_campaign_products').value = campaignProducts.join(',');

        /**
         * Register Campaign Product
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerCampaignProduct(grid, element, checked) {
            element.value = Number(element.value);

            if (checked) {
                campaignProducts.push(element.value);
            } else {
                campaignProducts.splice(campaignProducts.indexOf(element.value), 1);
            }

            $('in_campaign_products').value = campaignProducts.join(',');
            grid.reloadParams = {
                'selected_products[]': campaignProducts
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

        gridJsObject.rowClickCallback = campaignProductRowClick;
        gridJsObject.checkboxCheckCallback = registerCampaignProduct;
    };
});
