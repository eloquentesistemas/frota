<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Adicionar Contas</h5>
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
                <FormContas></FormContas>
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
import FormContas from "@/views/contas/FormContas.vue";
import contaService from "@/services/conta.service";
import toastr from "toastr/build/toastr.min";

export default {
    name: "CreateContas",
    components: {FormContas, LayoutPage, ButtonWidget},
    methods:{
        async sendForm(){
            let dataForm = {
        data_ocorrido: document.getElementById('data_ocorrido').value,
nome: document.getElementById('nome').value,
modalidade: document.getElementById('modalidade').value,
natureza_financeira_id: document.getElementById('natureza_financeira_id').value,
valor: document.getElementById('valor').value,
parcelas: document.getElementById('parcelas').value,
descritivo: document.getElementById('descritivo').value,


        }
            let contasService = new contaService();
            let response = await contasService.store(dataForm);

            if(response.data?.id){
                location.href = '/contas/index';
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
