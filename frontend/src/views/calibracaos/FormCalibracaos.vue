<template>
  <input-form class-list="col-12" type="date" label="Data " value="" name="data"/>
  <input-form placeholder="Selecione Fornecedor " class-list="col-12" type="select2" url="/api/fornecedor/list"
              label="Fornecedor " value="" name="pessoa_id"/>
  <input-form placeholder="Selecione Veículo " class-list="col-12" type="select2" url="/api/veiculos/list"
              label="Veículo " value="" name="veiculo_id"/>
  <input-form class-list="col-12" type="string" label="Serviço " value="" name="servico"/>
  <input-form class-list="col-12" type="bigint" label="Km " value="" name="km"/>
  <input-form class-list="col-4" type="decimal" label="Valor  " value="" name="valor"/>

</template>

<script>
import InputForm from "@/components/form/inputForm.vue";


export default {
  name: "FormCalibracaos",
  components: {InputForm},
  data() {
    return {}
  },
  methods: {},

  created() {
  }
}
</script>

<style scoped>
@import "toastr/build/toastr.css";
</style>
