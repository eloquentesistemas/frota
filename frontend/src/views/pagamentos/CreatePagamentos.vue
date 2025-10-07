<template>
  <layout-page>
    <div class="card-header">

      <div class="row">
        <div class="col-12 ps-4 pt-3 ">
          <div class="float-start">
            <h5>Adicionar Pagamentos</h5>
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
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import FormPagamentos from "@/views/pagamentos/FormPagamentos.vue";
import pagamentoService from "@/services/pagamento.service";
import toastr from "toastr/build/toastr.min";

export default {
  name: "CreatePagamentos",
  components: {FormPagamentos, LayoutPage, ButtonWidget},
  data() {
    return {
      urlVoltar: null,
      detalhes:null
    }
  },
  methods: {
    async sendForm() {
      let dataForm = {
        data_ocorrido: document.getElementById('data_ocorrido').value,
        valor: document.getElementById('valor').value,
        parcela: document.getElementById('parcela').value,
        descritivo: document.getElementById('descritivo').value,
        conta_id: this.$route.params.conta_id,


      }
      let pagamentosService = new pagamentoService();
      let response = await pagamentosService.store(dataForm);

      if (response.data?.id) {
        location.href = this.urlVoltar;
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
    this.tipo = await this.$route.query.tipo;
    this.urlVoltar = await '/pagamentos/index/' + this.$route.params.conta_id+'?tipo='+this.tipo;
    console.log(this.urlVoltar)
    let pagamentosService = new pagamentoService();
    this.detalhes = await pagamentosService.detalhes(this.$route.params.conta_id);
  }
}
</script>
<style scoped>
</style>
