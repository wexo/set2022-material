import template from './wexo-product-label-type.html.twig';

import { LABEL_TYPE_ENUM } from '../../helper/helper';

const { Component } = Shopware;
const { mapState, mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('wexo-product-label-type', {
    template,

    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel'
        ]),

        ...mapPropertyErrors('productLabel', ['type']),

        labelTypes() {
            return [
                {
                    'label': this.$tc('wexoProductLabels.type.badge'),
                    'value': LABEL_TYPE_ENUM.badge,
                },
                {
                    'label': this.$tc('wexoProductLabels.type.image'),
                    'value': LABEL_TYPE_ENUM.image,
                },
                {
                    'label': this.$tc('wexoProductLabels.type.discount'),
                    'value': LABEL_TYPE_ENUM.discount,
                },
            ];
        }
    }
});
