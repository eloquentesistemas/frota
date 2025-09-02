<template>
  <layout-page>
    <div class="card-header">
      <div class="row">
        <div class="col-12 ps-4 pt-3 ">
          <div class="float-start">
            <h5>Editar Pagamentos</h5>
          </div>
          <div class="float-end">
            <button-widget cor="azul" :href="urlVoltar" tamanho="M">
              Voltar
            </button-widget>
          </div>
        </div>
        <div class="col-12">
          <div class="btn-group">
            <div class="btn btn-primary">Linhas: <strong>{{detalhes?.data?.rows}} </strong></div>
            <div class="btn btn-primary">Valor pago:  <strong>R${{detalhes?.data?.valorPago}}</strong></div>
            <div class="btn btn-primary">Estado: <strong>{{detalhes?.data?.status}}</strong></div>
            <div class="btn btn-primary">Valor Restante:  <strong>R$ {{detalhes?.data?.valorRestante}}</strong></div>
            <div class="btn btn-primary">Valor Total:  <strong>R$ {{detalhes?.data?.valorTotal}}</strong></div>
          </div>
        </div>

      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <FormPagamentos></FormPagamentos>
        <div class="col-4">
          <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
        </div>
      </div>
    </div>
  </layout-page>

</template>

<script>
import FormPagamentos from "@/views/pagamentos/FormPagamentos.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import pagamentoService from "@/services/pagamento.service";

export default {
  name: "EditPagamentos",
  components: {LayoutPage, ButtonWidget, FormPagamentos},
  data() {
    return {
      urlVoltar : null,
      conta_id : null,
      detalhes:null
    }
  },
  methods: {
    async edit(id) {
      let pagamentosService = new pagamentoService();
      let response = await pagamentosService.view(id);
      document.getElementById('data_ocorrido').value = response.data.data_ocorrido;
      document.getElementById('valor').value = response.data.valor;
      document.getElementById('parcela').value = response.data.parcela;
      document.getElementById('descritivo').value = response.data.descritivo;
      this.conta_id = response.data.conta_id;
      this.urlVoltar = '/pagamentos/index/'+this.conta_id


    },
    async sendForm() {
      let dataForm = {
        data_ocorrido: document.getElementById('data_ocorrido').value,
        valor: document.getElementById('valor').value,
        parcela: document.getElementById('parcela').value,
        descritivo: document.getElementById('descritivo').value,


        _method: 'PUT'

      }
      if (!dataForm.parent_id) {
        delete dataForm.parent_id
      }
      let id = this.$route.params.id;
      let pagamentosService = new pagamentoService();
      let response = await pagamentosService.update(dataForm, id);
      if (response.data?.id) {
        toastr.success('Salvo com sucesso')
        let pagamentosService = new pagamentoService();
        this.detalhes = await pagamentosService.detalhes(this.conta_id);
      } else {
        if (response.response.data?.message) {
          toastr.error(response.response.data?.message);
        } else {
          toastr.error('Houve um problema ao inserir');
        }

      }
    }
  },
  async mounted() {
    await this.edit(this.$route.params.id)
    let pagamentosService = new pagamentoService();
    this.detalhes = await pagamentosService.detalhes(this.conta_id);
  }
}
</script>

<style scoped>

</style>
