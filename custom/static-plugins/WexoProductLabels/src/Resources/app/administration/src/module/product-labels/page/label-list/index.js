import template from "./label-list.html.twig";
import './label-list.scss';

const { Component, Mixin, Data: { Criteria } } = Shopware;

const loginService = Shopware.Service('loginService');

Component.register("wexo-product-label-list", {
    template,

    inject: [
        'repositoryFactory',
        'productLabelsService'
    ],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('listing'),
    ],

    data() {
        return {
            productLabels: null,
            isLoading: false,
            updateLabelsLoading: false,
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        productLabelColumns() {
            return [
                {
                    property: 'name',
                    label: this.$t('wexoProductLabels.list.column.name'),
                    inlineEdit: 'string',
                    routerLink: 'wexo.product.label.detail',
                    primary: true,
                    allowResize: true
                }, {
                    property: 'type',
                    label: this.$t('wexoProductLabels.list.column.type'),
                    allowResize: true
                }, {
                    property: 'active',
                    dataIndex: 'active',
                    label: this.$t('wexoProductLabels.list.column.active'),
                    inlineEdit: 'boolean',
                    align: 'center',
                    allowResize: true
                },
            ];
        },

        productLabelCriteria() {
            return new Criteria();
        },

        productLabelRepository() {
            return this.repositoryFactory.create('custom_labels');
        }
    },

    watch: {
        productLabelCriteria: {
            handler() {
                this.getList();
            },
            deep: true
        }
    },

    methods: {
        getList() {
            this.isLoading = true;

            return this.productLabelRepository.search(this.productLabelCriteria, Shopware.Context.api)
                .then((response) => {
                    this.productLabels = response;
                }).catch(error => {
                    this.createNotificationError({
                        title: 'An error occoured',
                        message: error
                    });
                });
        },

        updateLabels() {
            this.updateLabelsLoading = true;

            this.productLabelsService.updateLabels()
                .then(() => {
                    this.createNotificationSuccess({
                        title: 'Label update has started',
                        message: 'Your products labels are getting updated and will be visible soon'
                    });
                })
                .catch((error) => {
                    this.createNotificationError({
                        title: 'An error occoured',
                        message: error
                    });
                })
                .finally(() => {
                    this.updateLabelsLoading = false;
                });
        }
    },
});

