<template>
      <input-form placeholder="Selecione Veiculo  " class-list="col-12" type="select2" url="/api/veiculos/list" label="Veiculo  " value="" name="veiculo_id"/>
<input-form class-list="col-12"  type="bigint" label="Quilometragem  " value="" name="quilometragem"/>
<input-form class-list="col-12"  type="bigint" label="Quantidade  " value="" name="quantidade"/>
<input-form class-list="col-12"  type="decimal" label="Valor  " value="" name="valor"/>

</template>

<script>
import InputForm from "@/components/form/inputForm.vue";



export default {
    name: "FormPneusVeiculos",
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
