<template>
    <input-form class-list="col-12"  type="date" label="Data Ocorrido  " value="" name="data_ocorrido"/>
<input-form class-list="col-12"  type="decimal" label="Valor  " value="" name="valor"/>
<input-form class-list="col-12"  type="bigint" label="Parcela  " value="" name="parcela"/>
<input-form class-list="col-12"  type="text" label="Descritivo  " value="" name="descritivo"/>
</template>

<script>
import InputForm from "@/components/form/inputForm.vue";



export default {
    name: "FormPagamentos",
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
