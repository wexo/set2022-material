import template from './wexo-product-label-shape.html.twig';

import { LABEL_SHAPE_ENUM } from '../../helper/helper';

const { Component } = Shopware;
const { mapState } = Shopware.Component.getComponentHelper();

Component.register('wexo-product-label-shape', {
    template,

    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel'
        ]),

        shapes() {
            return [
                {
                    'label': this.$tc('wexoProductLabels.shape.normal'),
                    'value': LABEL_SHAPE_ENUM.normal,
                },
                {
                    'label': this.$tc('wexoProductLabels.shape.round'),
                    'value': LABEL_SHAPE_ENUM.round,
                },
                {
                    'label': this.$tc('wexoProductLabels.shape.circle'),
                    'value': LABEL_SHAPE_ENUM.circle
                }
            ];
        }
    }
});
