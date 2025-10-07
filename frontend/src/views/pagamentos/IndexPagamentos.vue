<template>
    <layout-page>
        <div class="row">
            <div class="col-12">
                <div class="row">
                  <div class="col-12">
                  </div>
                  <div class="col-12">
                    <h4>Tela de Pagamento</h4>
                  </div>
                    <div class="col-12 ps-4 pt-3 ">
                        <div class="float-start">
                           <label class="text-success">    {{this.nomeConta}} R$  {{this.valorConta}} </label>
                        </div>
                        <div class="w-50">
                            <input id="search" class="form-control" @change="list()" placeholder="Digite sua pesquisa"
                                   type="text" v-model="search">
                        </div>

                        <div class="float-end">
                            <button-widget cor="azul" :href="urlCreate" tamanho="M">
                                Adicionar
                            </button-widget>

                          <button-widget class="ms-2" cor="azul" :href="urlVoltar" tamanho="M">
                            Voltar
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
                                <button class="btn btn-system btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ações
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <router-link class="dropdown-item" :to="'/pagamentos/edit/'+row.id+'?tipo='+tipo">
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
                        
                <td>                 <div class="col-12"> <strong>Data Ocorrido  : </strong>{{row.data_ocorrido}}</div>
                 <div class="col-12"> <strong>Valor  : </strong>R$ {{String(row.valor).replace('.',',')}}</div>
                 <div class="col-12"> <strong>Parcela  : </strong>{{row.parcela}}</div>

</td>
                <td></td>

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
                    <tr v-if="rows===false ">
                        <td colspan="2" class="text-center"> Não há dados</td>
                    </tr>
                    </tbody>
                  <tfoot>
                  <tr>
                    <td>Linhas: {{detalhes?.data?.rows}}</td>
                    <td>Valor pago: R${{detalhes?.data?.valorPago}}</td>
                    <td>Estado: {{detalhes?.data?.status}}</td>
                    <td>Valor Restante:  R$ {{detalhes?.data?.valorRestante}}</td>
                  </tr>
                  </tfoot>
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
import pagamentoService from "@/services/pagamento.service";
import contaService from "@/services/conta.service";

export default {
    name: "IndexPagamentos",
    components: {ButtonWidget, LayoutPage},
    data() {
        return {
            rows: null,
            search: null,
          urlCreate:null,
          urlVoltar:null,
          nomeConta:null,
          valorConta:null,
          detalhes:null,
          tipo:null,
        }
    },
    methods: {
        async list(conta_id) {

            let pagamentosService = new pagamentoService();
           let dataRow = await pagamentosService.list(this.search,conta_id);
            let helpers = new Helpers();

            if (dataRow.data.data.length > 0) {
                this.rows = dataRow.data.data;

            } else if (!helpers.empty(dataRow.response?.data)) {
                toastr.error('Houve um problema');
            }else{
                this.rows = false;
            }


        },
        async deleteRow(id){
            let pagamentosService = new pagamentoService();
            let dataRow = await pagamentosService.delete(id);
           if(dataRow.data.success){
             this.list(this.$route.params.conta_id);
                toastr.success('Apagado com sucesso');
            }else{
                toastr.error('Houve um problema ao apagar');
            }
        },
        async setURL(){
          this.urlCreate  = '/pagamentos/create/'+ this.$route.params.conta_id+'?tipo='+this.tipo;
          this.urlVoltar = '/contas/index?tipo='+this.tipo
        },
      async setTotalizadores(){
        let contasService = new contaService();
        let response = await contasService.view(this.$route.params.conta_id);
        this.nomeConta  = response.data.nome;
        this.valorConta  = response.data.valor;
        this.valorConta = this.valorConta.replace('.',',')
      }


    },
    async mounted() {
        this.tipo = await this.$route.query.tipo;
      await this.setURL();
      await this.list(this.$route.params.conta_id);
      await  this.setTotalizadores()
      let pagamentosService = await new pagamentoService();
      this.detalhes = await pagamentosService.detalhes(this.$route.params.conta_id);

    }
}

</script>

<style scoped>
@import "toastr/build/toastr.css";
@import "bootstrap-icons/font/bootstrap-icons.min.css";
</style>
