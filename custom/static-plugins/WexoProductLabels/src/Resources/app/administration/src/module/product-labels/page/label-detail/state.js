import { LABEL_TYPE_ENUM } from '../../helper/helper';

export default {
    namespaced: true,

    state() {
        return {
            productLabel: {},
        };
    },

    mutations: {
        setProductLabel(state, productLabel) {
            state.productLabel = productLabel;
        },
    },

    actions: {
        loadProductLabel({ commit }, { repository, id, apiContext, criteria }) {
            return repository.get(id, apiContext, criteria).then((productLabel) => {
                commit('setProductLabel', productLabel);
            });
        }
    },

    getters: {
        isBadge(state) {
            return state.productLabel.type === LABEL_TYPE_ENUM.badge;
        },

        isDiscount(state) {
            return state.productLabel.type === LABEL_TYPE_ENUM.discount;
        },

        isImage(state) {
            return state.productLabel.type === LABEL_TYPE_ENUM.image;
        }
    }
};
