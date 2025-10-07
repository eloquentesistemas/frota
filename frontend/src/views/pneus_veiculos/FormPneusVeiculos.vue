<template>
  <input-form placeholder="Selecione Fornecedor  " class-list="col-12" type="select2" url="/api/fornecedor/list"  label="Fornecedor  " value="" name="pessoa_id"/>
  <input-form placeholder="Selecione Veiculo  " class-list="col-12" type="select2" url="/api/veiculos/list" label="Veiculo  " value="" name="veiculo_id"/>
<input-form class-list="col-12"  type="bigint" label="Quilometragem  " value="" name="quilometragem"/>
<input-form class-list="col-12"  type="bigint" label="Quantidade  " value="" name="quantidade"/>
<input-form class-list="col-12"  type="decimal" label="Valor por Pneu  " value="" name="valor"/>
<input-form class-list="col-12"  type="bigint" label="Aro  " value="" name="aro"/>
<input-form class-list="col-12"  type="string" label="Marca  " value="" name="marca"/>

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
