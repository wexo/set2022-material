import template from './wexo-product-label-condition-form.html.twig';

const { Component } = Shopware;
const { mapState } = Shopware.Component.getComponentHelper();

Component.register('wexo-product-label-condition-form', {
    template,

    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel'
        ]),

    }
});
