import template from './wexo-product-label-product-stream.html.twig';

const { Component } = Shopware;
const { mapState } = Shopware.Component.getComponentHelper();

Component.register('wexo-product-label-product-stream', {
    template,

    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel'
        ]),
    }
});
