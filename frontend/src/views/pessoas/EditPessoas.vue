<template>
    <layout-page>
        <div class="card-header">
            <div class="row">
                <div class="col-12 ps-4 pt-3 ">
                    <div class="float-start">
                        <h5>Editar Pessoas</h5>
                    </div>
                    <div class="float-end">
                        <button-widget cor="azul" href="../index" tamanho="M">
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
import FormPessoas from "@/views/pessoas/FormPessoas.vue";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";
import pessoaService from "@/services/pessoa.service";

export default {
    name: "EditPessoas",
    components: {LayoutPage, ButtonWidget, FormPessoas},
    methods:{
        async edit(id){
            let pessoasService = new pessoaService();
            let response = await pessoasService.view(id);
        document.getElementById('nome').value = response.data.nome;
document.getElementById('cpf_cnpj').value = response.data.cpf_cnpj;
document.getElementById('tipo').value = response.data.tipo;
document.getElementById('telefone').value = response.data.telefone;
document.getElementById('numero_cnh').value = response.data.numero_cnh;
document.getElementById('categoria_cnh').value = response.data.categoria_cnh;
document.getElementById('vencimento_cnh').value = response.data.vencimento_cnh;
document.getElementById('situacao').value = response.data.situacao;
document.getElementById('cidade_id').value = response.data.cidade_id;
document.getElementById('rua').value = response.data.rua;
document.getElementById('numero').value = response.data.numero;
document.getElementById('descritivo').value = response.data.descritivo;

        },
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

            _method:'PUT'

        }
            if(!dataForm.parent_id){
                delete dataForm.parent_id
            }
            let id = this.$route.params.id;
            let pessoasService = new pessoaService();
            let response = await pessoasService.update(dataForm,id);
            if(response.data?.id){
                toastr.success('Salvo com sucesso')
            }else{
                if (response.response.data?.message){
                    toastr.error(response.response.data?.message);
                }else{
                    toastr.error('Houve um problema ao inserir');
                }

            }
        }
    },
    created() {
        this.edit(this.$route.params.id)
    }
}
</script>

<style scoped>

</style>
