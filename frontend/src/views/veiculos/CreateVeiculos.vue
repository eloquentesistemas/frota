<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Adicionar Veiculos</h5>
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
                <FormVeiculos></FormVeiculos>
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
import FormVeiculos from "@/views/veiculos/FormVeiculos.vue";
import veiculoService from "@/services/veiculo.service";
import toastr from "toastr/build/toastr.min";

export default {
    name: "CreateVeiculos",
    components: {FormVeiculos, LayoutPage, ButtonWidget},
    methods:{
        async sendForm(){
            let dataForm = {
        nome: document.getElementById('nome').value,
placa: document.getElementById('placa').value,
cor: document.getElementById('cor').value,
vencimento_documento: document.getElementById('vencimento_documento').value,
ativo: document.getElementById('ativo').value,
descritivo: document.getElementById('descritivo').value,


        }
            let veiculosService = new veiculoService();
            let response = await veiculosService.store(dataForm);

            if(response.data?.id){
                location.href = '/veiculos/index';
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
