import RequestHelper from "@/services/RequestHelper";
import Helpers from "@/services/Helpers";

export default class PagamentosService {
    async list(search,conta_id) {
        let dataRequest = {conta_id:conta_id};
        let requestHelper = new RequestHelper();
        let helpers = new Helpers();

        if (!helpers.empty(search)) {
            dataRequest = {
                search: search,
                conta_id:conta_id
            };
        }

        return await requestHelper.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pagamentos', dataRequest);

    }

    async delete(id) {
        let requestHelper = new RequestHelper();
        return await requestHelper.deleteAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pagamentos/' + id);

    }

    async update(dataForm,id) {
        let request =  new RequestHelper();
        return await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pagamentos/'+id,dataForm);
    }

    async store(dataForm) {
        if (!dataForm.parent_id) {
            delete dataForm.parent_id
        }
        let request = new RequestHelper();
        return await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pagamentos', dataForm);
    }

    async view(id) {
        let request = new RequestHelper();
        return await request.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pagamentos/' + id, {});
    }
    async detalhes(conta_id) {
        let request = new RequestHelper();
        return await request.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pagamentos/detalhes/' + conta_id, {});
    }

}
