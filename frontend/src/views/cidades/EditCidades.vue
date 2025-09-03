<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Editar Cidades</h5>
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
                <FormCidades></FormCidades>
                    <div class="col-4">
                        <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
                    </div>
            </div>
        </div>
    </layout-page>

</template>

<script>
import FormCidades from "@/views/cidades/FormCidades.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import cidadeService from "@/services/cidade.service";

export default {
    name: "EditCidades",
    components: {LayoutPage, ButtonWidget, FormCidades},
    methods:{
        async edit(id){
            let cidadesService = new cidadeService();
            let response = await cidadesService.view(id);
        document.getElementById('codigo').value = response.data.codigo;
document.getElementById('nome').value = response.data.nome;
document.getElementById('uf').value = response.data.uf;

        },
        async sendForm(){
            let dataForm = {
        codigo: document.getElementById('codigo').value,
nome: document.getElementById('nome').value,
uf: document.getElementById('uf').value,

            _method:'PUT'

        }
            if(!dataForm.parent_id){
                delete dataForm.parent_id
            }
            let id = this.$route.params.id;
            let cidadesService = new cidadeService();
            let response = await cidadesService.update(dataForm,id);
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
