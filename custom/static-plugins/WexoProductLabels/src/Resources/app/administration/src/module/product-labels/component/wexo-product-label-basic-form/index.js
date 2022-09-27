import template from './wexo-product-label-basic-form.html.twig';

const { Component } = Shopware;
const { mapState, mapPropertyErrors, mapGetters } = Shopware.Component.getComponentHelper();

Component.register('wexo-product-label-basic-form', {
    template,

    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel'
        ]),

        ...mapGetters('wexoProductLabelDetail', [
            'isBadge',
            'isDiscount',
            'isImage'
        ]),

        ...mapPropertyErrors('productLabel', ['name', 'type', 'position']),
    }
})
