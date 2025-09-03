<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Editar Motorista</h5>
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
                <FormPessoaVeiculos></FormPessoaVeiculos>
                    <div class="col-4">
                        <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
                    </div>
            </div>
        </div>
    </layout-page>

</template>

<script>
import FormPessoaVeiculos from "@/views/pessoa_veiculos/FormPessoaVeiculos.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import pessoa_veiculoService from "@/services/pessoa_veiculo.service";

export default {
    name: "EditPessoaVeiculos",
    components: {LayoutPage, ButtonWidget, FormPessoaVeiculos},
    methods:{
        async edit(id){
            let pessoa_veiculosService = new pessoa_veiculoService();
            let response = await pessoa_veiculosService.view(id);
        document.getElementById('pessoa_id').value = response.data.pessoa_id;
document.getElementById('veiculo_id').value = response.data.veiculo_id;

        },
        async sendForm(){
            let dataForm = {
        pessoa_id: document.getElementById('pessoa_id').value,
veiculo_id: document.getElementById('veiculo_id').value,

            _method:'PUT'

        }
            if(!dataForm.parent_id){
                delete dataForm.parent_id
            }
            let id = this.$route.params.id;
            let pessoa_veiculosService = new pessoa_veiculoService();
            let response = await pessoa_veiculosService.update(dataForm,id);
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
