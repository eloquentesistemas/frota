<template>
    <input-form class-list="col-12"  type="string" label="Nome  " value="" name="nome"/>
<input-form class-list="col-4"  type="string" label="CPF ou CNPJ  " value="" name="cpf_cnpj"/>
  <input-form placeholder="Selecione Tipo  " class-list="col-4" type="select" :items="[{id:'motorista',message:'motorista',},{id:'cliente',message:'cliente',},{id:'fornecedor',message:'fornecedor',},]" label="Tipo  " value="" name="tipo"/>
<input-form class-list="col-4"  type="mask" mask="(00) 0 0000-0000" label="Telefone  " value="" name="telefone"/>
<input-form class-list="col-4"  type="string" label="Número Cnh  " value="" name="numero_cnh"/>
<input-form class-list="col-4"  type="string" label="Categoria Cnh  " value="" name="categoria_cnh"/>
<input-form class-list="col-4"  type="date" label="Vencimento Cnh  " value="" name="vencimento_cnh"/>
  <input-form placeholder="Selecione Situação  " class-list="col-4" type="select" :items="[{id:'ativo',message:'ativo',},{id:'inativo',message:'inativo',},]" label="Situação  " value="" name="situacao"/>
  <input-form placeholder="Selecione Cidade  " class-list="col-4" type="select2" url="/api/cidade/list" label="Cidade  " value="" name="cidade_id"/>
<input-form class-list="col-4"  type="string" label="Rua  " value="" name="rua"/>
<input-form class-list="col-4"  type="string" label="Número  " value="" name="numero"/>
<input-form class-list="col-12"  type="text" label="Descritivo  " value="" name="descritivo"/>

</template>

<script>
import InputForm from "@/components/form/inputForm.vue";



export default {
    name: "FormPessoas",
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
