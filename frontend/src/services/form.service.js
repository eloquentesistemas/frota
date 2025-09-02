import RequestHelper from "@/services/RequestHelper";



export  default class FormService {
    constructor() {

    }

    async list(hash) {

        let dataRequest = {};
        let requestHelper = new RequestHelper();
        let dataRow = await requestHelper.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/form/'+hash, dataRequest);
        return dataRow;
    }

    async store(dataRequest,hash) {

        let requestHelper = new RequestHelper();

        let dataRow = await requestHelper.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/form/'+hash, dataRequest);
        return dataRow;
    }
    async view(pedido_propostas_id) {

        let dataRequest = {};
        let requestHelper = new RequestHelper();
        let dataRow = await requestHelper.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/formView/'+pedido_propostas_id, dataRequest);
        return dataRow;
    }

}