<template>
    <input-form class-list="col-12"  type="string" label="Código  " value="" name="codigo"/>
<input-form class-list="col-12"  type="string" label="Nome  " value="" name="nome"/>
<input-form class-list="col-12"  type="string" label="UF " value="" name="uf"/>

</template>

<script>
import InputForm from "@/components/form/inputForm.vue";



export default {
    name: "FormCidades",
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
