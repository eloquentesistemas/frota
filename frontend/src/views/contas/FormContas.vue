<template>
  <input-form class-list="col-4" type="date" label="Data da conta  " value="" name="data_ocorrido"/>
  <input-form class-list="col-8" type="string" label="Nome  " value="" name="nome"/>
  <input-form class-list="col-4" type="decimal" label="Valor  " value="" name="valor"/>
  <input-form class-list="col-4" type="bigint" label="Parcelas  " value="" name="parcelas"/>
  <input-form placeholder="Selecione Natureza Financeira  " class-list="col-12" type="select2"
              url="/api/natureza_financeiras/list" label="Natureza Financeira  " value=""
              name="natureza_financeira_id"/>
  <input-form class-list="col-12" type="text" label="Descritivo  " value="" name="descritivo"/>

</template>

<script>
import InputForm from "@/components/form/inputForm.vue";


export default {
  name: "FormContas",
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
