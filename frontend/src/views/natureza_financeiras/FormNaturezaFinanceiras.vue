<template>
    <input-form class-list="col-12"  type="string" label="Nome  " value="" name="nome"/>
<input-form class-list="col-12"  type="text" label="Descritivo  " value="" name="descritivo"/>

</template>

<script>
import InputForm from "@/components/form/inputForm.vue";



export default {
    name: "FormNaturezaFinanceiras",
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
