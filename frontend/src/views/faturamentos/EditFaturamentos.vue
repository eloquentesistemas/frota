<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Editar Cargas</h5>
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
                <FormFaturamentos></FormFaturamentos>
                    <div class="col-4">
                        <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
                    </div>
            </div>
        </div>
    </layout-page>

</template>

<script>
import FormFaturamentos from "@/views/faturamentos/FormFaturamentos.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import faturamentoService from "@/services/faturamento.service";

export default {
    name: "EditFaturamentos",
    components: {LayoutPage, ButtonWidget, FormFaturamentos},
    methods:{
        async edit(id){
            let faturamentosService = new faturamentoService();
            let response = await faturamentosService.view(id);
        document.getElementById('pessoa_motorista_id').value = response.data.pessoa_motorista_id;
document.getElementById('veiculo_id').value = response.data.veiculo_id;
document.getElementById('data_embarque').value = response.data.data_embarque;
document.getElementById('origem_cidade_id').value = response.data.origem_cidade_id;
document.getElementById('origem_local').value = response.data.origem_local;
document.getElementById('destino_cidade_id').value = response.data.destino_cidade_id;
document.getElementById('destino_local').value = response.data.destino_local;
document.getElementById('pessoa_cliente_id').value = response.data.pessoa_cliente_id;
document.getElementById('danfe').value = response.data.danfe;
document.getElementById('peso').value = response.data.peso;
document.getElementById('valor_acerto_motorista').value = response.data.valor_acerto_motorista;
document.getElementById('valor_total').value = response.data.valor_total;
document.getElementById('DMT').value = response.data.DMT;
document.getElementById('carga').value = response.data.carga;
document.getElementById('descritivo').value = response.data.descritivo;

        },
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

            _method:'PUT'

        }
            if(!dataForm.parent_id){
                delete dataForm.parent_id
            }
            let id = this.$route.params.id;
            let faturamentosService = new faturamentoService();
            let response = await faturamentosService.update(dataForm,id);
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
