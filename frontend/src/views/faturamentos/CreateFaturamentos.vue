<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Adicionar Roteamentos</h5>
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
                <FormFaturamentos></FormFaturamentos>
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
import FormFaturamentos from "@/views/faturamentos/FormFaturamentos.vue";
import faturamentoService from "@/services/faturamento.service";
import toastr from "toastr/build/toastr.min";

export default {
    name: "CreateFaturamentos",
    components: {FormFaturamentos, LayoutPage, ButtonWidget},
    methods:{
        async sendForm(){
            let dataForm = {
        pessoa_motorista_id: document.getElementById('pessoa_motorista_id').value,
veiculo_id: document.getElementById('veiculo_id').value,
data_embarque: document.getElementById('data_embarque').value,
origem_cidade_id: document.getElementById('origem_cidade_id').value,
origem_local: document.getElementById('origem_local').value,
destino_cidade_id: document.getElementById('destino_cidade_id').value,
destino_local: document.getElementById('destino_local').value,
pessoa_cliente_id: document.getElementById('pessoa_cliente_id').value,
danfe: document.getElementById('danfe').value,
peso: document.getElementById('peso').value,
valor_acerto_motorista: document.getElementById('valor_acerto_motorista').value,
valor_total: document.getElementById('valor_total').value,
DMT: document.getElementById('DMT').value,
carga: document.getElementById('carga').value,
descritivo: document.getElementById('descritivo').value,


        }
            let faturamentosService = new faturamentoService();
            let response = await faturamentosService.store(dataForm);

            if(response.data?.id){
                location.href = '/faturamentos/index';
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
