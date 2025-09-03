<template>
    <input-form class-list="col-12"  type="string" label="Nome  " value="" name="nome"/>
<input-form class-list="col-12"  type="string" label="Placa  " value="" name="placa"/>
  <input-form placeholder="Selecione Cor  " class-list="col-12" type="select" :items="[{id:'branco',message:'branco',},{id:'prata',message:'prata',},{id:'cinza',message:'cinza',},{id:'preto',message:'preto',},{id:'azul',message:'azul',},{id:'vermelho',message:'vermelho',},{id:'amarelo',message:'amarelo',},{id:'verde',message:'verde',},{id:'laranja',message:'laranja',},{id:'bicolor',message:'bicolor',},]" label="Cor  " value="" name="cor"/>
<input-form class-list="col-12"  type="date" label="Vencimento do documento  " value="" name="vencimento_documento"/>
<input-form class-list="col-12"  type="tinyint" label="Ativo  " value="" name="ativo"/>
<input-form class-list="col-12"  type="text" label="Descritivo  " value="" name="descritivo"/>

</template>

<script>
import InputForm from "@/components/form/inputForm.vue";



export default {
    name: "FormVeiculos",
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
