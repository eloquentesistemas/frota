<template>

  <input-form placeholder="Selecione Fornecedor  " class-list="col-12" type="select2" url="/api/fornecedor/list"  label="Fornecedor  " value="" name="pessoa_id"/>
  <input-form placeholder="Selecione Veiculo  " class-list="col-12" type="select2" url="/api/veiculos/list"
              label="Veiculo  " value="" name="veiculo_id"/>
  <input-form class-list="col-12" type="bigint" label="Quilometragem  " value="" name="quilometragem"/>
  <input-form class-list="col-12" type="decimal" label="Litro(s) Abastecido(s)  " value="" name="litros"/>
  <input-form class-list="col-12" type="decimal" label="Valor por Litro " value="" name="valor"/>
  <input-form class-list="col-12" type="bigint" label="Número de Nota  " value="" name="numero_nota"/>


</template>

<script>
import InputForm from "@/components/form/inputForm.vue";


export default {
  name: "FormAbastecimentoVeiculos",
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
