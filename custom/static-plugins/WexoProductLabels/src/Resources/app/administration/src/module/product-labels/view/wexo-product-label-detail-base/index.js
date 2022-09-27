import template from './wexo-product-label-detail-base.html.twig';

const { Component } = Shopware;
const { mapState, mapGetters, mapPropertyErrors } = Shopware.Component.getComponentHelper();

Component.register('wexo-product-label-detail-base', {
    template,

    props: {
        isLoading: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel',
        ]),

        ...mapGetters('wexoProductLabelDetail', [
            'isBadge',
            'isDiscount'
        ]),

        ...mapPropertyErrors('productLabel', ['name', 'type', 'position']),
    }

});
