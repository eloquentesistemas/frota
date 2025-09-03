<template>

  <layout-page>


    <div class="row">
      <div class="col-12 ps-4 pt-3 ">
        <div class="float-start">
          <h5>Usuários</h5>
        </div>
        <div class="w-50">
          <div class="input-group input-group-merge search-bar">
              <span class="input-group-text" id="topbar-addon">
                <svg class="icon icon-xs" x-description="Heroicon name: solid/search" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd"></path>
                </svg>
              </span>
            <input id="search" class="form-control" @change="list()" placeholder="Digite sua pesquisa"
                   type="text" v-model="search">
          </div>

        </div>

        <div class="float-end">
          <button-widget cor="azul" href="/users/create" tamanho="M">
            Adicionar
          </button-widget>
        </div>
      </div>

    </div>

  <div class="row">
    <div class="col-12">
      <div class="table-responsive table-overflow ">
        <table class="table table">
          <thead>
          <tr>
            <th>Ações</th>
            <th></th>

          </tr>
          </thead>
          <tbody>
          <tr v-for="row in rows" :key="row.id">
            <td>
              <div class="dropdown">
                <a class="btn btn-primary btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                   aria-expanded="false">
                  Ações
                </a>

                <ul class="dropdown-menu">
                  <li>
                    <router-link class="dropdown-item" :to="'/users/edit/'+row.id">
                      <i class="bi bi-pencil-square"></i>
                      Editar
                    </router-link>
                  </li>
                  <li>
                        <span class="dropdown-item cursor-pointer" @click="deleteRow(row.id)">
                           <i class="bi bi-trash2-fill"></i>
                          Apagar
                        </span>
                  </li>

                </ul>
              </div>


            </td>
            <td>
              <div class="col-12">
                <strong>Nome:</strong> {{ row.name }}
              </div>
              <div class="col-12">
                <strong>E-mail:</strong> {{ row.email }}
              </div>

            </td>



          </tr>
          <tr v-if="rows==null ">
            <td colspan="2" >
              <div class="row">
                <div class="col-12 d-flex justify-content-center">
                  <div class="loader"></div>
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="rows==[] ">
            <td colspan="2" class="text-center"> Não há dados</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>




  </layout-page>
</template>
<script>
import LayoutPage from "@/components/page/layoutPage.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import RequestHelper from "@/services/RequestHelper";
import toastr from "toastr/build/toastr.min";
import UserService from "@/services/user.service";


export default {
  name: "IndexUsers",
  components: {ButtonWidget, LayoutPage},
  data() {
    return {
      rows: null,
      search: null
    }
  },
  methods: {
    async list() {

      let userService = new UserService(process.env.VUE_APP_API_HOST_NAME);
      let dataRow = await userService.list(this.search);
      if (dataRow.data.data.length > 0) {
        this.rows = dataRow.data.data;

      }


    },
    async deleteRow(id) {
      let requestHelper = new RequestHelper();
      let dataRow = await requestHelper.deleteAuth(process.env.VUE_APP_API_HOST_NAME + '/api/users/' + id);
      if (dataRow.data.success) {
        this.list();
        toastr.success('Apagado com sucesso');
      } else {
        toastr.error('Houve um problema ao apagar');
      }
    }

  },
  created() {
    this.list();

  }
}

</script>

<style scoped>
@import "toastr/build/toastr.css";
@import "bootstrap-icons/font/bootstrap-icons.min.css";

.table-overflow{
  overflow-x: hidden;
  overflow-y: scroll;
  display: flex;
}
</style>