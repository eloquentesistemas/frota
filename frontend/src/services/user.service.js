import RequestHelper from "@/services/RequestHelper";
import Helpers from "@/services/Helpers";


export  default class UserService {
    constructor() {

    }

    async list(search=null) {

        let dataRequest = {};
        let requestHelper = new RequestHelper();
        let helpers = new Helpers();

        if (!helpers.empty(search)) {
            dataRequest = {
                search: search
            };
        }

        let dataRow = await requestHelper.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/users', dataRequest);
        return dataRow;
    }
    async me(){
        let requestHelper = new RequestHelper();
        return await requestHelper.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/auth/me', {view: true});

    }
}