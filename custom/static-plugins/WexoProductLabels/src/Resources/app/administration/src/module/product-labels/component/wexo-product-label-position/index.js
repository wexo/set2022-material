import template from './wexo-product-label-position.html.twig';

import { LABEL_POSITION_ENUM } from '../../helper/helper';

const { Component } = Shopware;
const { mapState, mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('wexo-product-label-position', {
    template,

    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel'
        ]),

        ...mapPropertyErrors('productLabel', ['position']),

        labelPositions() {
            return [
                {
                    'label': this.$tc('wexoProductLabels.positions.topLeft'),
                    'value': LABEL_POSITION_ENUM.topLeft,
                },
                {
                    'label': this.$tc('wexoProductLabels.positions.topRight'),
                    'value': LABEL_POSITION_ENUM.topRight,
                },
                {
                    'label': this.$tc('wexoProductLabels.positions.bottomLeft'),
                    'value': LABEL_POSITION_ENUM.bottomLeft,
                },
                {
                    'label': this.$tc('wexoProductLabels.positions.bottomRight'),
                    'value': LABEL_POSITION_ENUM.bottomRight,
                },
                {
                    'label': this.$tc('wexoProductLabels.positions.nextToText'),
                    'value': LABEL_POSITION_ENUM.nextToText,
                },
                {
                    'label': this.$tc('wexoProductLabels.positions.center'),
                    'value': LABEL_POSITION_ENUM.center,
                },
            ];
        }
    }
});
