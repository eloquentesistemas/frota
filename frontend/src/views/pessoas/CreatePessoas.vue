<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Adicionar Pessoas</h5>
                    </div>
                    <div class="float-end">
                        <button-widget cor="azul" href="./index" tamanho="M">
                            Voltar
                        </button-widget>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <FormPessoas></FormPessoas>
                    <div class="col-4">
                    <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
                    </div>
                </div>
        </div>
    </layout-page>
</template>
<script>
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import FormPessoas from "@/views/pessoas/FormPessoas.vue";
import pessoaService from "@/services/pessoa.service";
import toastr from "toastr/build/toastr.min";

export default {
    name: "CreatePessoas",
    components: {FormPessoas, LayoutPage, ButtonWidget},
    methods:{
        async sendForm(){
            let dataForm = {
        nome: document.getElementById('nome').value,
cpf_cnpj: document.getElementById('cpf_cnpj').value,
tipo: document.getElementById('tipo').value,
telefone: String(document.getElementById('telefone').value).replace(/[^a-zA-Z0-9]/g, ''),
numero_cnh: document.getElementById('numero_cnh').value,
categoria_cnh: document.getElementById('categoria_cnh').value,
vencimento_cnh: document.getElementById('vencimento_cnh').value,
situacao: document.getElementById('situacao').value,
cidade_id: document.getElementById('cidade_id').value,
rua: document.getElementById('rua').value,
numero: document.getElementById('numero').value,
descritivo: document.getElementById('descritivo').value,


        }
            let pessoasService = new pessoaService();
            let response = await pessoasService.store(dataForm);

            if(response.data?.id){
                location.href = '/pessoas/index';
            }else{
                if (response.response.data?.message){
                    toastr.error(response.response.data?.message);
                }else{
                    toastr.error('Houve um problema ao inserir');
                }

            }
        }
    }
}
</script>
<style scoped>
</style>
