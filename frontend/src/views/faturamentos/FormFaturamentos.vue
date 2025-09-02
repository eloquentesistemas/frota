<template>
      <input-form placeholder="Selecione motorista  " class-list="col-12" type="select2" url="/api/motorista/list" label="Motorista  " value="" name="pessoa_motorista_id"/>
  <input-form placeholder="Selecione veiculo  " class-list="col-6" type="select2" url="/api/veiculos/list" label="Veiculo  " value="" name="veiculo_id"/>
<input-form class-list="col-6"  type="datetime" label="Data de embarque  " value="" name="data_embarque"/>
  <input-form placeholder="Selecione cidade de origem  " class-list="col-6" type="select2" url="/api/cidade/list" label="Cidade de origem " value="" name="origem_cidade_id"/>
<input-form class-list="col-6"  type="text" label="Local de Origem  " value="" name="origem_local"/>
  <input-form placeholder="Selecione  cidade de  destino  " class-list="col-6" type="select2" url="/api/cidade/list" label="Cidade de Destino  " value="" name="destino_cidade_id"/>
<input-form class-list="col-6"  type="text" label=" Local de destino  " value="" name="destino_local"/>
  <input-form placeholder="Selecione cliente  " class-list="col-12" type="select2" url="/api/cliente/list" label="Cliente  " value="" name="pessoa_cliente_id"/>
<input-form class-list="col-4"  type="bigint" label="DANFE  " value="" name="danfe"/>
<input-form class-list="col-4"  type="decimal" label="Peso  " value="" name="peso"/>
<input-form class-list="col-4"  type="decimal" label="Valor Acerto Motorista  " value="" name="valor_acerto_motorista"/>
<input-form class-list="col-4"  type="decimal" label="Valor Total  " value="" name="valor_total"/>
<input-form class-list="col-4"  type="string" label="DMT  " value="" name="DMT"/>
<input-form class-list="col-4"  type="text" label="Carga  " value="" name="carga"/>
<input-form class-list="col-12"  type="text" label="Descritivo  " value="" name="descritivo"/>

</template>

<script>
import InputForm from "@/components/form/inputForm.vue";



export default {
    name: "FormFaturamentos",
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
