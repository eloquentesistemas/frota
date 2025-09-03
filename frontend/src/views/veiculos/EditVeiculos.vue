<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Editar Veiculos</h5>
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
                <FormVeiculos></FormVeiculos>
                    <div class="col-4">
                        <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
                    </div>
            </div>
        </div>
    </layout-page>

</template>

<script>
import FormVeiculos from "@/views/veiculos/FormVeiculos.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import veiculoService from "@/services/veiculo.service";

export default {
    name: "EditVeiculos",
    components: {LayoutPage, ButtonWidget, FormVeiculos},
    methods:{
        async edit(id){
            let veiculosService = new veiculoService();
            let response = await veiculosService.view(id);
        document.getElementById('nome').value = response.data.nome;
document.getElementById('placa').value = response.data.placa;
document.getElementById('cor').value = response.data.cor;
document.getElementById('vencimento_documento').value = response.data.vencimento_documento;
document.getElementById('ativo').value = response.data.ativo;
document.getElementById('descritivo').value = response.data.descritivo;

        },
        async sendForm(){
            let dataForm = {
        nome: document.getElementById('nome').value,
placa: document.getElementById('placa').value,
cor: document.getElementById('cor').value,
vencimento_documento: document.getElementById('vencimento_documento').value,
ativo: document.getElementById('ativo').value,
descritivo: document.getElementById('descritivo').value,

            _method:'PUT'

        }
            if(!dataForm.parent_id){
                delete dataForm.parent_id
            }
            let id = this.$route.params.id;
            let veiculosService = new veiculoService();
            let response = await veiculosService.update(dataForm,id);
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
