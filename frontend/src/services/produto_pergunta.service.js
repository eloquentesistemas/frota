import RequestHelper from "@/services/RequestHelper";
import Helpers from "@/services/Helpers";

export default class ProdutoPerguntasService {
    async list(search) {
        let dataRequest = {};
        let requestHelper = new RequestHelper();
        let helpers = new Helpers();

        if (!helpers.empty(search)) {
            dataRequest = {
                search: search
            };
        }

        return await requestHelper.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/produto_perguntas', dataRequest);

    }

    async delete(id) {
        let requestHelper = new RequestHelper();
        return await requestHelper.deleteAuth(process.env.VUE_APP_API_HOST_NAME + '/api/produto_perguntas/' + id);

    }

    async update(dataForm,id) {
        let request =  new RequestHelper();
        return await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/produto_perguntas/'+id,dataForm);
    }

    async store(dataForm) {
        if (!dataForm.parent_id) {
            delete dataForm.parent_id
        }
        let request = new RequestHelper();
        return await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/produto_perguntas', dataForm);
    }

    async view(id) {
        let request = await new RequestHelper();
        return await request.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/produto_perguntas/' + id, {});
    }

    async findOptions(pergunta_id) {
        let request = await new RequestHelper();
        return await request.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/produto_perguntas/show/options/' + pergunta_id, {});
    }


}
