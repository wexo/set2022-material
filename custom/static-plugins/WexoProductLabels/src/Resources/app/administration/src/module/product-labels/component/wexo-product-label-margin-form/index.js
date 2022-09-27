import template from './wexo-product-label-margin-form.html.twig';
import './wexo-product-label-margin-form.scss';

const { Component } = Shopware;
const { mapState } = Shopware.Component.getComponentHelper();

Component.register('wexo-product-label-margin-form', {
    template,

    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel'
        ]),
    }
})
