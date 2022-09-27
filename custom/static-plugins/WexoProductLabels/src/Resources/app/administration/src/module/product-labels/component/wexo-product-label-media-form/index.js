import template from './wexo-product-label-media-form.html.twig';
import './wexo-product-label-media-form.scss';

const { Component, Utils } = Shopware;
const { mapState } = Shopware.Component.getComponentHelper();

Component.register('wexo-product-label-media-form', {
    template,

    data() {
        return {
            mediaModalIsOpen: false,
        }
    },

    watch: {
        'productLabel.media'(value) {
            this.productLabel.mediaId = value ? value.id : null;
        }
    },

    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel'
        ]),

        uploadTag() {
            const id = this.productLabel.id || Utils.createId();
            return `wexo-product-label-${id}`;
        }
    },

    methods: {
        onOpenMediaModal() {
            this.mediaModalIsOpen = true;
        },

        onCloseModal() {
            this.mediaModalIsOpen = false;
        },

        onImageRemove() {
            this.productLabel.media = null;
        },

        onSelectionChanges(select) {
            this.productLabel.media = select[0];
        },

        onFileUpload(item) {
            this.productLabel.mediaId = item.targetId;
        }
    }
})
