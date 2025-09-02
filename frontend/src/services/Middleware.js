import Helpers from "@/services/Helpers";

export default class Middleware {

    validateHash(to) {
        let helper = new Helpers();
        if (to.meta?.auth && helper.empty(localStorage.getItem("HASH"))) {
            location.href = "/login";
        }

        if (to.name == "LoginForm" && !helper.empty(localStorage.getItem("HASH"))) {
            location.href = '/pessoas/index'
        }
    }

    routeExists(to) {
        if (to.matched.length == 0) {
            location.href = '/404'
        }
    }

    logout(to) {
        if (to.href == '/logout') {
            localStorage.setItem('HASH', undefined);
            location.href = '/login'
            return true;
        }
        return false;

    }

    finishLoading() {
        setTimeout(function () {
            document.getElementsByClassName('loading-page')[0].classList.add('d-none');
        }, 1000)
    }


    setRegisterLastRouteBeforeLogin() {
        if (location.pathname !== "/login" && location.pathname !== "/logout" &&  location.pathname !== "/register") {
            localStorage.setItem('href', location.href);
            console.log(localStorage.getItem('href'))
        }
    }

    getRegisterLastRouteBeforeLogin() {
        return localStorage.getItem('href');
    }

     userPermissions(to) {
       if(!to.meta.auth){
           return false;
       }
       return true;


    }
}