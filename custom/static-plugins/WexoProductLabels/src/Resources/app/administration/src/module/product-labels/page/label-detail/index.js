import template from "./label-detail.html.twig";

import wexoProductLabelDetailState from './state';

const {Component, Mixin, Data: {Criteria}} = Shopware;
const {mapPageErrors, mapState, mapGetters} = Shopware.Component.getComponentHelper();

Component.register("wexo-product-label-detail", {
    template,

    inject: [
        'repositoryFactory',
    ],

    mixins: [
        Mixin.getByName('notification')
    ],

    props: {
        productLabelId: {
            type: String,
            required: false,
            default: null
        }
    },

    data() {
        return {
            isLoading: false,
            isSaveSuccessful: false,
        };
    },

    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel',
        ]),

        productLabelRepository() {
            return this.repositoryFactory.create('custom_labels');
        },

        productLabelCriteria() {
            const criteria = new Criteria();
            criteria.addAssociation('productStreams');

            return criteria;
        }
    },

    beforeCreate() {
        Shopware.State.registerModule('wexoProductLabelDetail', wexoProductLabelDetailState);
    },

    created() {
        this.createdComponent();
    },

    beforeDestroy() {
        Shopware.State.unregisterModule('wexoProductLabelDetail');
    },

    watch: {
        productLabelId() {
            this.createdComponent();
        }
    },

    methods: {
        createdComponent() {
            this.isLoading = true;

            this.initState();
        },

        initState() {
            if (this.productLabelId) {
                return this.loadState();
            }

            // When no product label id exists, create a new product label
            this.createState();
        },

        loadState() {
            return Shopware.State.dispatch('wexoProductLabelDetail/loadProductLabel', {
                repository: this.productLabelRepository,
                apiContext: Shopware.Context.api,
                id: this.productLabelId,
                criteria: this.productLabelCriteria,
            }).catch((error) => {
                console.log('Error', error);
            }).finally(() => {
                this.isLoading = false;
            });
        },

        createState() {
            // Create empty label
            Shopware.State.commit('wexoProductLabelDetail/setProductLabel', this.productLabelRepository.create());

            // Fill default values
            this.productLabel.active = true;

            this.isLoading = false;
        },

        onSave() {
            this.isLoading = true;
            this.isSaveSuccessful = false;

            return this.productLabelRepository.save(this.productLabel, Shopware.Context.api).then(() => {
                this.isSaveSuccessful = true;
            }).catch(err => {
                this.createNotificationError({
                    title: this.$tc('global.default.error'),
                    message: this.$tc('global.notification.notificationSaveErrorMessage', 0, {entityName: "Custom Product Label"})
                });
            }).finally(() => {
                this.isLoading = false;
            });
        },

        onCancel() {
            this.$router.push({ name: 'wexo.product.label.list'});
        },

        saveFinish() {
            this.isSaveSuccessful = false;

            if (!this.productLabelId) {
                this.$router.push({ name: 'wexo.product.label.detail', params: { id: this.productLabel.id } });
            }
        },
    },
});
