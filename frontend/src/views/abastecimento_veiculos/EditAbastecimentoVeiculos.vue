<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Editar Abastecimentos</h5>
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
                <FormAbastecimentoVeiculos></FormAbastecimentoVeiculos>
                    <div class="col-4">
                        <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
                    </div>
            </div>
        </div>
    </layout-page>

</template>

<script>
import FormAbastecimentoVeiculos from "@/views/abastecimento_veiculos/FormAbastecimentoVeiculos.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import abastecimento_veiculoService from "@/services/abastecimento_veiculo.service";

export default {
    name: "EditAbastecimentoVeiculos",
    components: {LayoutPage, ButtonWidget, FormAbastecimentoVeiculos},
    methods:{
        async edit(id){
            let abastecimento_veiculosService = new abastecimento_veiculoService();
            let response = await abastecimento_veiculosService.view(id);
        document.getElementById('veiculo_id').value = response.data.veiculo_id;
document.getElementById('quilometragem').value = response.data.quilometragem;
document.getElementById('litros').value = response.data.litros;
document.getElementById('valor').value = response.data.valor;

        },
        async sendForm(){
            let dataForm = {
        veiculo_id: document.getElementById('veiculo_id').value,
quilometragem: document.getElementById('quilometragem').value,
litros: document.getElementById('litros').value,
valor: document.getElementById('valor').value,

            _method:'PUT'

        }
            if(!dataForm.parent_id){
                delete dataForm.parent_id
            }
            let id = this.$route.params.id;
            let abastecimento_veiculosService = new abastecimento_veiculoService();
            let response = await abastecimento_veiculosService.update(dataForm,id);
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
