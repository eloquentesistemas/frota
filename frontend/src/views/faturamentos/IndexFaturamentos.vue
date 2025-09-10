<template>
    <layout-page>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 ps-4 pt-3 ">
                        <div class="float-start">
                            <h5> Cargas</h5>
                        </div>
                        <div class="w-50">
                            <input id="search" class="form-control" @change="list()" placeholder="Digite sua pesquisa"
                                   type="text" v-model="search">
                        </div>

                        <div class="float-end">
                            <button-widget cor="azul" href="/faturamentos/create" tamanho="M">
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
                                <button class="btn btn-system btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ações
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <router-link class="dropdown-item" :to="'/faturamentos/edit/'+row.id">
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
                        
                <td>                 <div class="col-12"> <strong>Motorista  : </strong>{{row.pessoa_motorista_id}}</div>
                 <div class="col-12"> <strong>Veiculo  : </strong>{{row.veiculo_id}}</div>
                 <div class="col-12"> <strong>Data de embarque  : </strong>{{row.data_embarque}}</div>
                 <div class="col-12"> <strong>Origem cidade  : </strong>{{row.origem_cidade_id}}</div>
</td>
                <td>                 <div class="col-12"> <strong>Destino cidade  : </strong>{{row.destino_cidade_id}}</div>
                 <div class="col-12"> <strong>Cliente  : </strong>{{row.pessoa_cliente_id}}</div>
                 <div class="col-12"> <strong>Valor Total  : </strong>R$ {{row.valor_total}}</div>

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
import faturamentoService from "@/services/faturamento.service";

export default {
    name: "IndexFaturamentos",
    components: {ButtonWidget, LayoutPage},
    data() {
        return {
            rows: null,
            search: null
        }
    },
    methods: {
        async list() {

            let faturamentosService = new faturamentoService();
           let dataRow = await faturamentosService.list(this.search);
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
            let faturamentosService = new faturamentoService();
            let dataRow = await faturamentosService.delete(id);
           if(dataRow.data.success){
                this.list();
                toastr.success('Apagado com sucesso');
            }else{
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
</style>
