import template from './wexo-product-label-saleschannel.html.twig';

const {Component, Context} = Shopware;
const {mapState} = Component.getComponentHelper()
const {EntityCollection, Criteria} = Shopware.Data;

Component.register('wexo-product-label-saleschannel', {
    template,
    inject: ['repositoryFactory'],
    data() {
        return {
            salesChannels: null
        }
    },
    computed: {
        ...mapState('wexoProductLabelDetail', [
            'productLabel'
        ]),
        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },
    },
    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.salesChannels = new EntityCollection(
                this.salesChannelRepository.route,
                this.salesChannelRepository.entityName,
                Context.api,
            );

            const salesChannelIds = this.productLabel.salesChannelIds || [];
            if (salesChannelIds.length < 1) {
                return Promise.resolve();
            }

            const criteria = new Criteria();
            criteria.setIds(salesChannelIds);

            return this.salesChannelRepository.search(criteria, Context.api).then((salesChannels) => {
                this.salesChannels = salesChannels;
            });
        },
        setSalesChannelIds(salesChannels) {
            if(salesChannels.length < 1){
                this.productLabel.salesChannelIds = null;
            } else {
                this.productLabel.salesChannelIds = salesChannels.getIds();
            }
            this.salesChannels = salesChannels;
        },
    }
});
