<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Adicionar Motorista</h5>
                    </div>
                    <div class="float-end">
                        <button-widget cor="azul" href="./index" tamanho="M">
                            Voltar
                        </button-widget>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <FormPessoaVeiculos></FormPessoaVeiculos>
                    <div class="col-4">
                    <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
                    </div>
                </div>
        </div>
    </layout-page>
</template>
<script>
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import FormPessoaVeiculos from "@/views/pessoa_veiculos/FormPessoaVeiculos.vue";
import pessoa_veiculoService from "@/services/pessoa_veiculo.service";
import toastr from "toastr/build/toastr.min";

export default {
    name: "CreatePessoaVeiculos",
    components: {FormPessoaVeiculos, LayoutPage, ButtonWidget},
    methods:{
        async sendForm(){
            let dataForm = {
        pessoa_id: document.getElementById('pessoa_id').value,
veiculo_id: document.getElementById('veiculo_id').value,


        }
            let pessoa_veiculosService = new pessoa_veiculoService();
            let response = await pessoa_veiculosService.store(dataForm);

            if(response.data?.id){
                location.href = '/pessoa_veiculos/index';
            }else{
                if (response.response.data?.message){
                    toastr.error(response.response.data?.message);
                }else{
                    toastr.error('Houve um problema ao inserir');
                }

            }
        }
    }
}
</script>
<style scoped>
</style>
