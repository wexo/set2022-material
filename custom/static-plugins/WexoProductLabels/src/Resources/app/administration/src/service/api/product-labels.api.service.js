const ApiService = Shopware.Classes.ApiService;

/**
 * Gateway for the API end point "document"
 * @class
 * @extends ApiService
 */
export default class ProductLabelsApiService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'wexo/product-labels') {
        super(httpClient, loginService, apiEndpoint);
        this.name = 'productLabelsService';
    }

    /**
     * Update labels
     *
     * @param {Object} [additionalParams = {}]
     * @param {Object} [additionalHeaders = {}]
     * @returns {Promise<T>}
     */
    updateLabels(additionalParams = {}, additionalHeaders = {}) {
        const params = additionalParams;
        const headers = this.getBasicHeaders(additionalHeaders);

        return this.httpClient
            .post(`${this.getApiBasePath()}/run-schedule`, {
                params,
                headers
            })
            .then((response) => {
                return ApiService.handleResponse(response);
            });
    }
}
