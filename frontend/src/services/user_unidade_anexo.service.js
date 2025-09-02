import RequestHelper from "@/services/RequestHelper";
import Helpers from "@/services/Helpers";

export default class UserUnidadeAnexosService {
    async list(search,user_unidade_id) {
        let dataRequest = {
            user_unidade_id:user_unidade_id
        };
        let requestHelper = new RequestHelper();
        let helpers = new Helpers();

        if (!helpers.empty(search)) {
            dataRequest = {
                search: search,
                user_unidade_id:user_unidade_id
            };
        }

        return await requestHelper.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/user_unidade_anexos', dataRequest);

    }

    async delete(id) {
        let requestHelper = new RequestHelper();
        return await requestHelper.deleteAuth(process.env.VUE_APP_API_HOST_NAME + '/api/user_unidade_anexos/' + id);

    }

    async update(dataForm,id) {
        let request =  new RequestHelper();
        return await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/user_unidade_anexos/'+id,dataForm);
    }

    async store(dataForm) {
        if (!dataForm.parent_id) {
            delete dataForm.parent_id
        }
        let request = new RequestHelper();
        return await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/user_unidade_anexos', dataForm);
    }

    async view(id) {
        let request = new RequestHelper();
        return await request.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/user_unidade_anexos/' + id, {});
    }

}
