import RequestHelper from "@/services/RequestHelper";

export default class PainelService {
    constructor(host) {
        this.host = host;
    }

    async contatos() {
        let request = new RequestHelper();
        let response = await request.postAuth(this.host + '/api/painel/contatos');
        return response.data.contatos;
    }

    async acessos() {
        let request = new RequestHelper();
        let response = await request.postAuth(this.host + '/api/painel/acessos');
        return response.data.acessos;
    }

    async naoResolvido() {
        let request = new RequestHelper();
        let response = await request.postAuth(this.host + '/api/painel/naoFinalizados');
        return response.data.naoFinalizados;
    }
    async list() {
        let request = new RequestHelper();
        let response = await request.postAuth(this.host + '/api/painel/list');
        return response.data.visitas;
    }

}