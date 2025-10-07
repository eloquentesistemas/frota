<template>
  <layout-page>
    <div class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-12 ps-4 pt-3 ">
            <div class="float-start">
              <h5>{{ label }}</h5>
            </div>
            <div class="w-50">
              <input id="search" class="form-control" @change="list()" placeholder="Digite sua pesquisa"
                     type="text" v-model="search">
            </div>

            <div class="float-end">
              <button-widget cor="azul" :href="'/contas/create?tipo='+tipo" tamanho="M">
                Adicionar
              </button-widget>
            </div>
          </div>

        </div>
        <table class="table">
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
                <button class="btn btn-system btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                  Ações
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <router-link class="dropdown-item" :to="'/contas/edit/'+row.id+'?tipo='+tipo">
                      <i class="bi bi-pencil-square"></i>
                      Editar
                    </router-link>
                  </li>
                  <li>
                    <router-link class="dropdown-item" :to="'/pagamentos/index/'+row.id+'?tipo='+tipo">
                      <i class="bi bi-pencil-square"></i>
                      Realizar Pagamento
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
              <div class="col-12"><strong>Data Ocorrido : </strong>{{ row.data_ocorrido }}</div>
              <div class="col-12"><strong>Nome : </strong>{{ row.nome }}</div>
              <div class="col-12"><strong>Natureza Financeira : </strong>{{ row.natureza_financeira_id }}</div>
              <div class="col-12"><strong>Valor Total : </strong>R${{ String(row.valor).replace('.', ',') }}</div>
              <div class="col-12"><strong>Total Pago : </strong>R$ {{ String(row.total_pago).replace('.', ',') }}</div>
              <div class="col-12"><strong>Estado : </strong>{{ row.status_pagamento }}</div>
            </td>
            <td>
              <div class="col-12"><strong>Parcelas : </strong>{{ row.parcelas }}</div>
              <div class="col-12"><strong>Detalhes : </strong>{{ String(row.descritivo).length < 30 ? row.descritivo : String(row.descritivo).substring(0,30)+'...'  }}</div>
            </td>

          </tr>
          <tr v-if="rows==null ">
            <td colspan="2">
              <div class="row">
                <div class="col-12 d-flex justify-content-center">
                  <div class="loader"></div>
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="rows===false ">
            <td colspan="2" class="text-center"> Não há dados</td>
          </tr>
          </tbody>
        </table>

      </div>
    </div>


  </layout-page>
</template>
<script>
import LayoutPage from "@/components/page/layoutPage.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import toastr from "toastr/build/toastr.min";
import Helpers from "@/services/Helpers";
import contaService from "@/services/conta.service";

export default {
  name: "IndexContas",
  components: {ButtonWidget, LayoutPage},
  data() {
    return {
      rows: null,
      search: null,
      label: '',
      tipo: ''
    }
  },
  methods: {
    async list() {
      let contasService = new contaService();
      let dataRow = await contasService.list(this.search, {tipo: this.$route.query.tipo});
      let helpers = new Helpers();

      if (dataRow.data.data.length > 0) {
        this.rows = dataRow.data.data;

      } else if (!helpers.empty(dataRow.response?.data)) {
        toastr.error('Houve um problema');
      } else {
        this.rows = false;
      }


    },
    async deleteRow(id) {
      let contasService = new contaService();
      let dataRow = await contasService.delete(id);
      if (dataRow.data.success) {
        this.list();
        toastr.success('Apagado com sucesso');
      } else {
        toastr.error('Houve um problema ao apagar');
      }
    },
    seTipo() {
      this.tipo = this.$route.query.tipo;
      if (this.$route.query.tipo === 'receber') {
        this.label = 'Recebimentos'
      } else if (this.$route.query.tipo === 'pagar') {
        this.label = 'Despesas'

      }
    }

  },

  created() {
    this.seTipo();
    this.list();


  }
}

</script>

<style scoped>
@import "toastr/build/toastr.css";
@import "bootstrap-icons/font/bootstrap-icons.min.css";
</style>
