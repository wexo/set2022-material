import './component/wexo-product-label-shape';
import './component/wexo-product-label-position';
import './component/wexo-product-label-type';
import './component/wexo-product-label-product-stream';
import './component/wexo-product-label-margin-form';
import './component/wexo-product-label-media-form';
import './component/wexo-product-label-condition-form';
import './component/wexo-product-label-basic-form';
import './component/wexo-product-label-saleschannel';

import './view/wexo-product-label-detail-base';

import "./page/label-list";
import "./page/label-detail";

import enGB from "./snippet/en-GB.json";
import daDK from "./snippet/da-DK.json";
import deDE from "./snippet/de-DE.json";

Shopware.Module.register("wexo-product-label", {
    type: "plugin",
    name: "product-labels",
    title: "wexoProductLabels.general.mainMenuItemGeneral",
    description: "wexoProductLabels.general.descriptionTextModule",
    color: '#FFD700',
    icon: 'default-object-marketing',
    entity: 'custom_labels',

    snippets: {
        'en-GB': enGB,
        'da-DK': daDK,
        'de-DE': deDE
    },

    routes: {
        list: {
            component: "wexo-product-label-list",
            path: "list"
        },
        create: {
            component: 'wexo-product-label-detail',
            path: 'create',
            redirect: {
                name: 'wexo.product.label.create.base'
            },
            children: {
                base: {
                    component: 'wexo-product-label-detail-base',
                    path: 'base',
                    meta: {
                        parentPath: 'wexo.product.label.list',
                    }
                }
            }
        },
        detail: {
            component: 'wexo-product-label-detail',
            path: 'detail/:id?',
            props: {
                default: (route) => ({productLabelId: route.params.id})
            },
            redirect: {
                name: 'wexo.product.label.detail.base'
            },
            children: {
                base: {
                    component: 'wexo-product-label-detail-base',
                    path: 'base',
                },
            }
        }
    },

    navigation: [{
        label: "wexoProductLabels.general.mainMenuItemGeneral",
        path: "wexo.product.label.list",
        position: 1000,
        parent: "sw-marketing",
    }]
});
