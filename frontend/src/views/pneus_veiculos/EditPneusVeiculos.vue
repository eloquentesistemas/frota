<template>
  <layout-page>
    <div class="card-header">
      <div class="row">
        <div class="col-12 ps-4 pt-3 ">
          <div class="float-start">
            <h5>Editar troca de pneus</h5>
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
        <FormPneusVeiculos></FormPneusVeiculos>
        <div class="col-4">
          <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
        </div>
      </div>
    </div>
  </layout-page>

</template>

<script>
import FormPneusVeiculos from "@/views/pneus_veiculos/FormPneusVeiculos.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import pneus_veiculoService from "@/services/pneus_veiculo.service";

export default {
  name: "EditPneusVeiculos",
  components: {LayoutPage, ButtonWidget, FormPneusVeiculos},
  methods: {
    async edit(id) {
      let pneus_veiculosService = new pneus_veiculoService();
      let response = await pneus_veiculosService.view(id);
      document.getElementById('veiculo_id').value = response.data.veiculo_id;
      document.getElementById('quilometragem').value = response.data.quilometragem;
      document.getElementById('quantidade').value = response.data.quantidade;
      document.getElementById('valor').value = response.data.valor;
      document.getElementById('marca').value = response.data.marca;
      document.getElementById('aro').value = response.data.aro;
      document.getElementById('pessoa_id').value = response.data.pessoa_id;


    },
    async sendForm() {
      let dataForm = {
        veiculo_id: document.getElementById('veiculo_id').value,
        quilometragem: document.getElementById('quilometragem').value,
        quantidade: document.getElementById('quantidade').value,
        valor: document.getElementById('valor').value,
        aro: document.getElementById('aro').value,
        marca: document.getElementById('marca').value,
        pessoa_id: document.getElementById('pessoa_id').value,

        _method: 'PUT'

      }
      if (!dataForm.parent_id) {
        delete dataForm.parent_id
      }
      let id = this.$route.params.id;
      let pneus_veiculosService = new pneus_veiculoService();
      let response = await pneus_veiculosService.update(dataForm, id);
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
