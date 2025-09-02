<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Editar Contas</h5>
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
                <FormContas></FormContas>
                    <div class="col-4">
                        <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
                    </div>
            </div>
        </div>
    </layout-page>

</template>

<script>
import FormContas from "@/views/contas/FormContas.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import contaService from "@/services/conta.service";

export default {
    name: "EditContas",
    components: {LayoutPage, ButtonWidget, FormContas},
    methods:{
        async edit(id){
            let contasService = new contaService();
            let response = await contasService.view(id);
        document.getElementById('data_ocorrido').value = response.data.data_ocorrido;
document.getElementById('nome').value = response.data.nome;
document.getElementById('modalidade').value = response.data.modalidade;
document.getElementById('natureza_financeira_id').value = response.data.natureza_financeira_id;
document.getElementById('valor').value = response.data.valor;
document.getElementById('parcelas').value = response.data.parcelas;
document.getElementById('descritivo').value = response.data.descritivo;

        },
        async sendForm(){
            let dataForm = {
        data_ocorrido: document.getElementById('data_ocorrido').value,
nome: document.getElementById('nome').value,
modalidade: document.getElementById('modalidade').value,
natureza_financeira_id: document.getElementById('natureza_financeira_id').value,
valor: document.getElementById('valor').value,
parcelas: document.getElementById('parcelas').value,
descritivo: document.getElementById('descritivo').value,

            _method:'PUT'

        }
            if(!dataForm.parent_id){
                delete dataForm.parent_id
            }
            let id = this.$route.params.id;
            let contasService = new contaService();
            let response = await contasService.update(dataForm,id);
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
