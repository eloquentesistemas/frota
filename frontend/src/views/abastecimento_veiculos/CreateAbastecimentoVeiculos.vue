<template>
  <layout-page>
    <div class="card-header">
      <div class="row">
        <div class="col-12 ps-4 pt-3 ">
          <div class="float-start">
            <h5>Adicionar Abastecimentos</h5>
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
        <FormAbastecimentoVeiculos></FormAbastecimentoVeiculos>
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
import FormAbastecimentoVeiculos from "@/views/abastecimento_veiculos/FormAbastecimentoVeiculos.vue";
import abastecimento_veiculoService from "@/services/abastecimento_veiculo.service";
import toastr from "toastr/build/toastr.min";

export default {
  name: "CreateAbastecimentoVeiculos",
  components: {FormAbastecimentoVeiculos, LayoutPage, ButtonWidget},
  methods: {
    async sendForm() {
      let dataForm = {
        veiculo_id: document.getElementById('veiculo_id').value,
        quilometragem: document.getElementById('quilometragem').value,
        litros: document.getElementById('litros').value,
        valor: document.getElementById('valor').value,
        pessoa_id: document.getElementById('pessoa_id').value,
        numero_nota: document.getElementById('numero_nota').value,
        tipo: document.getElementById('tipo').value,
        descritivo: document.getElementById('descritivo').value,


      }
      let abastecimento_veiculosService = new abastecimento_veiculoService();
      let response = await abastecimento_veiculosService.store(dataForm);

      if (response.data?.id) {
        location.href = '/abastecimento_veiculos/index';
      } else {
        if (response.response.data?.message) {
          toastr.error(response.response.data?.message);
        } else {
          toastr.error('Houve um problema ao inserir');
        }

      }
    }
  }
}
</script>
<style scoped>
</style>
