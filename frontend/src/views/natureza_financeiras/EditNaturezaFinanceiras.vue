<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Editar Natureza Financeira</h5>
                    </div>
                    <div class="float-end">
                        <button-widget cor="azul" href="../index" tamanho="M">
                            Voltar
                        </button-widget>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <FormNaturezaFinanceiras></FormNaturezaFinanceiras>
                    <div class="col-4">
                        <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
                    </div>
            </div>
        </div>
    </layout-page>

</template>

<script>
import FormNaturezaFinanceiras from "@/views/natureza_financeiras/FormNaturezaFinanceiras.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import natureza_financeiraService from "@/services/natureza_financeira.service";

export default {
    name: "EditNaturezaFinanceiras",
    components: {LayoutPage, ButtonWidget, FormNaturezaFinanceiras},
    methods:{
        async edit(id){
            let natureza_financeirasService = new natureza_financeiraService();
            let response = await natureza_financeirasService.view(id);
        document.getElementById('nome').value = response.data.nome;
document.getElementById('descritivo').value = response.data.descritivo;

        },
        async sendForm(){
            let dataForm = {
        nome: document.getElementById('nome').value,
descritivo: document.getElementById('descritivo').value,

            _method:'PUT'

        }
            if(!dataForm.parent_id){
                delete dataForm.parent_id
            }
            let id = this.$route.params.id;
            let natureza_financeirasService = new natureza_financeiraService();
            let response = await natureza_financeirasService.update(dataForm,id);
            if(response.data?.id){
                toastr.success('Salvo com sucesso')
            }else{
                if (response.response.data?.message){
                    toastr.error(response.response.data?.message);
                }else{
                    toastr.error('Houve um problema ao inserir');
                }

            }
        }
    },
    created() {
        this.edit(this.$route.params.id)
    }
}
</script>

<style scoped>

</style>
