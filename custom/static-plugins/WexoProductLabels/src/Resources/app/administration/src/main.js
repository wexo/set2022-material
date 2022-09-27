import ProductLabelsService from './service/api/product-labels.api.service';

import './module/product-labels';

const { Application, Locale } = Shopware;

Application.addServiceProvider('productLabelsService', () => {
    const serviceContainer = Application.getContainer('service');
    const initContainer = Application.getContainer('init');

    return new ProductLabelsService(initContainer.httpClient, serviceContainer.loginService);
});
