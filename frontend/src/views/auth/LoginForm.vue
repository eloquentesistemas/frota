<template>
  <section id="main-section" class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 d-flex align-items-center justify-content-center">
          <div class="card bg-black text-white shadow border-0 rounded border-light p-4 p-lg-4 w-100 fmxw-500">
            <div class="card-header bg-black text-center">
              <div>
                <img class=" img-logo" width="250" src="@/assets/logo-negativo.png">


              </div>


            </div>
            <div class="card-body">
              <div class="mb-3">
                <label>Email</label>
                <input type="text" v-model="email" class="form-control" id="email" placeholder="exemplo@compania.com"
                >
              </div>
              <div class="mb-3">
                <label>Senha</label>
                <input type="password" v-model="senha" class="form-control" placeholder="Senha"
                       id="password">
              </div>
              <div class="form-check">
                <input class="form-check-input" @change="salvarSenha" type="checkbox" value="1" id="save">
                <label class="form-check-label" for="flexCheckDefault">
                  Salvar a senha?
                </label>
              </div>
            </div>
            <div class="card-footer bg-black pt-3 pb-5 h-100">
              <div class="row">
                <div class="col-12 d-grid gap-2">
                  <button @click="login" class="btn btn-lg btn-system ">
                    Entrar
                  </button>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>


import axios from "axios";
import toastr from "toastr/build/toastr.min";
import Middleware from "@/services/Middleware";
import Helpers from "@/services/Helpers";


export default {
  name: "LoginForm",
  computed: {

  },
  data() {
    return {
      appName: process.env.VUE_APP_APPLICATION_NAME,
      email: null,
      senha: null
    }
  }, methods: {
    login: function () {
      let email = document.getElementById('email').value;
      let password = document.getElementById('password').value;
      let save = document.getElementById('save').value;
      this.salvarSenha()
      let data = {
        email: email,
        password: password,
        save: save
      };
      axios.post(process.env.VUE_APP_API_HOST_NAME + '/api/auth/login', data, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("HASH")}`,
          Accept: 'application/json',
          "Content-Type": 'application/json'
        }
      })
          .then(function (response) {

            localStorage.setItem('HASH', response.data.data.token);
            let url = new Middleware().getRegisterLastRouteBeforeLogin()
            if (new Helpers().empty(url)) {
              url = '/pessoas/index'
            }
            location.href = url;

          }).catch(function (error) {
        if (error.request.status == 401) {
          toastr.error("Login ou senha invalidos");
          return false;
        }
        console.log(error)
        toastr.error(error.response.data.message);
      });
    },
    salvarSenha() {
      if (document.getElementById('save').checked) {
        localStorage.setItem('email', this.email);
        localStorage.setItem('senha', this.senha);
      } else {
        localStorage.removeItem('email');
        localStorage.removeItem('senha');
      }

    }
  },
  mounted() {
    let helper = new Helpers();
    if (!helper.empty(localStorage.getItem('email')) && !helper.empty(localStorage.getItem('email'))) {
      this.email = localStorage.getItem('email');
      this.senha = localStorage.getItem('senha');
      document.getElementById('save').checked = true;
    }

  }
}
</script>

<style>
@import "toastr/build/toastr.css";

.img-logo {
  background-color: #000000;
}

</style>
<style scoped>
@media screen and (min-width: 1270px) {
  #main-section{
    /* Estilos para telas com largura máxima de 600px */
    background-size:auto;
    background-image:url('@/assets/img/back-login.png');
    background-color: #000000 !important;
  }
}
@media screen and (max-width: 1270px) {
  html, #main-section{
    margin: 0px !important;
    position: absolute;
    height: 100%;
    width: 100%;
    background-color: #000000 !important;
  }
}

@media screen and (max-width: 1366px) {
  #main-section {
    background-position-x: -300px;


  }
}
</style>