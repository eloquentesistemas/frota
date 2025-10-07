<template>
  <layout-page>
    <div class="card-header">
      <div class="row">
        <div class="col-12 ps-4 pt-3 ">
          <div class="float-start">
            <h5>Adicionar Calibrações</h5>
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
        <FormCalibracaos></FormCalibracaos>
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
import FormCalibracaos from "@/views/calibracaos/FormCalibracaos.vue";
import calibracaoService from "@/services/calibracao.service";
import toastr from "toastr/build/toastr.min";

export default {
  name: "CreateCalibracaos",
  components: {FormCalibracaos, LayoutPage, ButtonWidget},
  methods: {
    async sendForm() {
      let dataForm = {
        data: document.getElementById('data').value,
        pessoa_id: document.getElementById('pessoa_id').value,
        veiculo_id: document.getElementById('veiculo_id').value,
        servico: document.getElementById('servico').value,
        km: document.getElementById('km').value,
        valor: document.getElementById('valor').value,


      }
      let calibracaosService = new calibracaoService();
      let response = await calibracaosService.store(dataForm);

      if (response.data?.id) {
        location.href = '/calibracaos/index';
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
