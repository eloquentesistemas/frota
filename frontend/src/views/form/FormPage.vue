<script>
import FormService from "@/services/form.service";
import FormBody from "@/views/form/FormBody.vue";
import Helpers from "@/services/Helpers";
import toastr from "toastr/build/toastr.min";
import swal from "sweetalert";

export default {
  name: "FormPage",
  components: {FormBody},
  data() {
    return {question: null, render: false}
  },
  methods: {

    async list() {
      let hash = this.$route.params.hash;
      let response = await new FormService().list(hash);
      this.question = await response?.data;
      this.render = true

    },
    async enviarResposta() {

      let pessoa = await {
        nome: document.getElementById('nome').value,
        cpf: document.getElementById('cpf').value,
        cnpj: document.getElementById('cnpj')?.value,
        email: document.getElementById('email').value,
        telefone: document.getElementById('telefone').value ,
        celular: document.getElementById('celular')?.value  ,
        cidade_id: document.getElementById('cidade_id').value  ,
      }
      if (new Helpers().empty(pessoa.nome)) {
        toastr.error('Nome não preenchido!')
        return;
      }
      if (new Helpers().empty(pessoa.cpf)) {
        toastr.error('CPF não preenchido!')
        return;
      }

      if (new Helpers().empty(pessoa.email)) {
        toastr.error('E-mail não preenchido!')
        return;
      }

      if (new Helpers().empty(pessoa.telefone)) {
        toastr.error('Telefone não preenchido!')
        return;
      }
      if (new Helpers().empty(pessoa.cidade_id)) {
        toastr.error('Cidade não preenchido!')
        return;
      }


      let respostaRows = await [];
      let questions = await document.getElementsByClassName('question');
      for (const question of questions) {

        let resposta =  await '';
        let tipo_resposta =  await '';
        if (question.classList.contains('decimal')) {
          tipo_resposta = 'decimal';
          resposta = await question.value;
          resposta = await resposta.replaceAll(',', '.')

        } else if (question.classList.contains('arquivo')) {

          await this.readFile(question.files[0])
            resposta = await window.dataFile
            tipo_resposta = 'arquivo';

        } else if (question.classList.contains('inteiro')) {
           resposta = question.value;
          tipo_resposta = 'inteiro';

        } else if (question.classList.contains('texto')) {
           resposta = question.value;
          tipo_resposta = 'texto';

        } else if (question.classList.contains('binario')) {
           resposta = question.value;
          tipo_resposta = 'binario';

        } else if (question.classList.contains('unica-escolha')) {
          let options = question.getElementsByTagName('input');
          for (let j = 0; j < options.length; j++) {

            if (options[j].checked) {
              resposta += options[j].value;
            }
          }

          tipo_resposta = 'unica-escolha';
        } else if (question.classList.contains('multipla-escolha')) {

          let options = question.getElementsByTagName('input');

          for (let j = 0; j < options.length; j++) {
            if (options[j].checked) {
              resposta += options[j].value + ';';
            }
          }
          tipo_resposta = 'multipla-escolha';

        }



        if(new Helpers().empty(resposta) && !question.classList.contains('required')){
          resposta = "NÃO RESPONDIDO";
        }

        respostaRows.push({
          'produto_questionario_id': question.getAttribute('data-produto_questionario_id'),
          'produto_pergunta_id': question.getAttribute('data-produto_pergunta_id'),
          'resposta': resposta,
          'tipo_resposta': tipo_resposta,
        })



      }
      let dataPayload =
        {_method: 'PUT',
         pessoa: pessoa,
        respostas:respostaRows
        };

     let response  = await new FormService().store(dataPayload,this.$route.params.hash);
     if(response.data.success){
       swal("Maravilha!", "Respostas registrada!", "success").then(() => {
         window.close();
       });
        return;
     }
     toastr.error('Houve um problema');
      console.log(response);

    },
    async  readFile(file){
      window.dataFile  = await [];
      return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.onload = (event) => {
          const base64String = event.target.result.split(',')[1]; // Pega apenas a parte base64 da string
          const fileExtension = file.name.split('.').pop(); // Pega a extensão do arquivo

          window.dataFile  = {
            extention: fileExtension,
            data: `base_64:${base64String}`
          };

          resolve(window.dataFile);
        };

        reader.onerror = (error) => {
          reject(error);
        };

        reader.readAsDataURL(file);
      });
    }
  }, mounted() {
    this.list();
  }
}
</script>

<template>
  <div v-if="render" class="container">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <img alt="Volt Logo" class="" height="100" src="@/assets/logo-normal.png">
            <h4 class="text-light ms-1">{{ question.nome }}</h4>
          </div>
          <div class="card-body">
           <form-body :produto_perguntas = "question.produto_perguntas" ></form-body>
            <div class="row">
              <div class="col-12 p-3">
                <button class="btn btn-ouro " @click="enviarResposta()">Enviar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.card-header {
  background-color: #000;
}
.btn-ouro {
  background: #ffc451;
  border: 0;
  padding: 10px 24px;
  color: #151515;
  transition: .4s;
  border-radius: 4px;
}



</style>