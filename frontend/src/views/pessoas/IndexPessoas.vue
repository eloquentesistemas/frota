<template>
    <layout-page>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 ps-4 pt-3 ">
                        <div class="float-start">
                            <h5> Pessoas</h5>
                        </div>


                        <div class="float-end">
                            <button-widget cor="azul" href="/pessoas/create" tamanho="M">
                                Adicionar
                            </button-widget>
                        </div>
                    </div>
                  <div class="col-6 pt-2">
                    <input id="search" class="form-control" @change="list()" placeholder="Digite sua pesquisa"
                           type="text" v-model="search">
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
                                        <router-link class="dropdown-item" :to="'/pessoas/edit/'+row.id">
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
                        
                <td>                 <div class="col-12"> <strong>Nome  : </strong>{{row.nome}}</div>
                 <div class="col-12"> <strong>CPF CNPJ  : </strong>{{row.cpf_cnpj}}</div>
                 <div class="col-12"> <strong>Tipo  : </strong>{{row.tipo}}</div>
                 <div class="col-12"> <strong>Telefone  : </strong>{{row.telefone}}</div>
                 <div class="col-12"> <strong>Número Cnh  : </strong>{{row.numero_cnh}}</div>
</td>
                <td>                 <div class="col-12"> <strong>Categoria Cnh  : </strong>{{row.categoria_cnh}}</div>
                 <div class="col-12"> <strong>Vencimento CNH  : </strong>{{row.vencimento_cnh}}</div>
                 <div class="col-12"> <strong>Situacao  : </strong>{{row.situacao}}</div>
                 <div class="col-12"> <strong>Cidade  : </strong>{{row.cidade_id}}</div>
                 <div class="col-12"> <strong>Rua  : </strong>{{row.rua}}</div>
                 <div class="col-12"> <strong>Número  : </strong>{{row.numero}}</div>
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
import pessoaService from "@/services/pessoa.service";

export default {
    name: "IndexPessoas",
    components: {ButtonWidget, LayoutPage},
    data() {
        return {
            rows: null,
            search: null
        }
    },
    methods: {
        async list() {

            let pessoasService = new pessoaService();
           let dataRow = await pessoasService.list(this.search);
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
            let pessoasService = new pessoaService();
            let dataRow = await pessoasService.delete(id);
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
