<template>
  <layout-page>
    <div class="card-header">
      <div class="row">
        <div class="col-12 ps-4 pt-3 ">
          <div class="float-start">
            <h5>Editar Lubrificações</h5>
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
        <FormLubrificacaos></FormLubrificacaos>
        <div class="col-4">
          <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
        </div>
      </div>
    </div>
  </layout-page>

</template>

<script>
import FormLubrificacaos from "@/views/lubrificacaos/FormLubrificacaos.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import lubrificacaoService from "@/services/lubrificacao.service";

export default {
  name: "EditLubrificacaos",
  components: {LayoutPage, ButtonWidget, FormLubrificacaos},
  methods: {
    async edit(id) {
      let lubrificacaosService = new lubrificacaoService();
      let response = await lubrificacaosService.view(id);
      document.getElementById('data').value = response.data.data;
      document.getElementById('pessoa_id').value = response.data.pessoa_id;
      document.getElementById('veiculo_id').value = response.data.veiculo_id;
      document.getElementById('servico').value = response.data.servico;
      document.getElementById('km').value = response.data.km;
      document.getElementById('valor').value = response.data.valor;

    },
    async sendForm() {
      let dataForm = {
        data: document.getElementById('data').value,
        pessoa_id: document.getElementById('pessoa_id').value,
        veiculo_id: document.getElementById('veiculo_id').value,
        servico: document.getElementById('servico').value,
        km: document.getElementById('km').value,
        valor: document.getElementById('valor').value,

        _method: 'PUT'

      }
      if (!dataForm.parent_id) {
        delete dataForm.parent_id
      }
      let id = this.$route.params.id;
      let lubrificacaosService = new lubrificacaoService();
      let response = await lubrificacaosService.update(dataForm, id);
      if (response.data?.id) {
        toastr.success('Salvo com sucesso')
      } else {
        if (response.response.data?.message) {
          toastr.error(response.response.data?.message);
        } else {
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
