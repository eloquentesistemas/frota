<template>
      <input-form placeholder="Selecione motorista  " class-list="col-12" type="select2" url="/api/motorista/list" label="Motorista  " value="" name="pessoa_id"/>
  <input-form placeholder="Selecione veículo  " class-list="col-12" type="select2" url="/api/veiculos/list" label="Veículo  " value="" name="veiculo_id"/>

</template>

<script>
import InputForm from "@/components/form/inputForm.vue";



export default {
    name: "FormPessoaVeiculos",
    components: {InputForm},
    data() {
        return {}
    },
    methods:{},

created() {}
}
</script>

<style scoped>
@import "toastr/build/toastr.css";
</style>
