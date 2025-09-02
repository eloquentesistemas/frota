import RequestHelper from "@/services/RequestHelper";
import Helpers from "@/services/Helpers";

export default class PneusVeiculosService {
    async list(search) {
        let dataRequest = {};
        let requestHelper = new RequestHelper();
        let helpers = new Helpers();

        if (!helpers.empty(search)) {
            dataRequest = {
                search: search
            };
        }

        return await requestHelper.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pneus_veiculos', dataRequest);

    }

    async delete(id) {
        let requestHelper = new RequestHelper();
        return await requestHelper.deleteAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pneus_veiculos/' + id);

    }

    async update(dataForm,id) {
        let request =  new RequestHelper();
        return await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pneus_veiculos/'+id,dataForm);
    }

    async store(dataForm) {
        if (!dataForm.parent_id) {
            delete dataForm.parent_id
        }
        let request = new RequestHelper();
        return await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pneus_veiculos', dataForm);
    }

    async view(id) {
        let request = new RequestHelper();
        return await request.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/pneus_veiculos/' + id, {});
    }

}
