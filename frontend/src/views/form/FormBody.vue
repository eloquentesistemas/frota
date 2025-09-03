<script>
import InputForm from "@/components/form/inputForm.vue";
import toastr from "toastr/build/toastr.min";

export default {
  name: "FormBody",
  components: {InputForm},
  props: {
    produto_perguntas: Object
  },
  methods: {
    inputDecimal() {
      let rows = document.getElementsByClassName('decimal');
      console.log(rows)
      for (let i = 0; i < rows.length; i++) {
        rows[i].addEventListener('keyup', (el) => {
          let valor = el.target.value;
          valor = String(valor).replaceAll(/[^0-9]/g, '');
          if (valor.length === 0) {
            el.target.value = ''
            return false;
          }
          if (valor.length === 1) {
            valor = '000' + valor;
          } else if (valor.length === 2) {
            valor = '00' + valor;
          } else if (valor.length === 3) {
            valor = '0' + valor;
          }

          let numberFormated = Number(valor.substring(0, (valor.length - 2))) + ',' + valor.substring((valor.length - 2), valor.length)
          el.target.value = numberFormated;
        })
      }
    },
    inputInteiro() {
      let rows = document.getElementsByClassName('inteiro');
      for (let i = 0; i < rows.length; i++) {
        rows[i].addEventListener('keyup', (el) => {
          let valor = el.target.value;
          valor = String(valor).replaceAll(/[^0-9]/g, '');
          el.target.value = valor;
        })
      }
    },
    inputText() {
      let rows = document.getElementsByClassName('texto');
      for (let i = 0; i < rows.length; i++) {
        rows[i].addEventListener('keyup', (el) => {
          let texto = el.target.value;
          let tamanho = el.target.dataset.tamanho;
          if (texto.length > tamanho) {
            el.target.value = texto.substring(0, tamanho);
          }
        })
      }
    },
    inputFile() {
      let rows = document.getElementsByClassName('arquivo');

      for (let i = 0; i < rows.length; i++) {
        rows[i].addEventListener('change', (el) => {
          let input = el.target;
          let arquivo = input.files[0]; // Acessa o arquivo carregado
          let maxSizeMB = parseFloat(input.getAttribute('data-tamanho')); // Obtém o tamanho máximo em MB do atributo data-tamanho
          let maxSizeBytes = maxSizeMB * 1024 * 1024; // Converte MB para bytes

          // Verifica se o tamanho do arquivo excede o limite
          if (arquivo.size > maxSizeBytes) {


            toastr.error("O arquivo é muito grande. O tamanho máximo permitido é de " + maxSizeMB + " MB.");

            // Remove o arquivo do input
            input.value = '';
          }
        });
      }
    },


  },
  mounted() {
    this.inputDecimal()
    this.inputInteiro()
    this.inputText()
    this.inputFile()


  }
}
</script>

<template>
  <div class="row pb-5 cliente">
    <div class="col-12 col-lg-6">
      <input-form class-list="col-12" type="mask" mask="000.000.000-00" label="CPF" name="cpf"
                  placeholder="Digite CPF" value=""/>
    </div>
    <div class="col-12 col-lg-6">
      <input-form class-list="col-12" type="mask" mask="00.000.000/0000-00" label="CNPJ (opcional)" name="cnpj"
                  placeholder="Digite CNPJ" value=""/>
    </div>
    <div class="col-12 col-lg-12">
      <input-form class-list="col-12" label="Nome" name="nome" placeholder="Digite Nome" type="string" value=""/>
    </div>
    <div class="col-12 col-lg-6">
      <input-form class-list="col-12" label="E-mail " name="email" placeholder="Digite E-mail" type="string" value=""/>
    </div>
    <div class="col-12 col-lg-6">
      <input-form class-list="col-12" type="mask" mask="(00) 0 0000-0000" label="Telefone" name="telefone"
                  placeholder="Digite Telefone" value=""/>
    </div>
    <div class="col-12 col-lg-6">
      <input-form class-list="col-12" type="mask" mask="(00) 0 0000-0000" label="Celular (opcional)" name="celular"
                  placeholder="Digite Celular" value=""/>
    </div>
    <div class="col-12 col-lg-6">
      <input-form placeholder="Selecione Cidade " class-list="col-12" type="select2" url="/api/cidades/list"
                  label="Cidade " value="" name="cidade_id"/>
    </div>

  </div>
  <div class="row">
    <div class="col-12 p-3">
      <div v-for="produto_pergunta in produto_perguntas" v-bind:key="produto_pergunta" class="row">
        <div class="col-12 p-2">
          <p>
            <strong>
              {{ produto_pergunta.ordem }}
            </strong> .
            {{ produto_pergunta.enunciado }}
            {{ produto_pergunta.obrigatorio ? '*' : '' }}
          </p>
        </div>
        <div class="col-12">
          <div v-if="produto_pergunta.tipo==='decimal'" class="input-group">

            <input :class="produto_pergunta.obrigatorio?'required':''"
                   :data-produto_pergunta_id="produto_pergunta.id"
                   :data-produto_questionario_id="produto_pergunta.questionario_id"
                   class="decimal form-control question"
                   placeholder="0,000" type="text">
          </div>
          <div v-if="produto_pergunta.tipo==='inteiro'" class="input-group">

            <input :data-produto_pergunta_id="produto_pergunta.id"
                   :class="produto_pergunta.obrigatorio?'required':''"
                   :data-produto_questionario_id="produto_pergunta.questionario_id"
                   class="inteiro form-control question"
                   placeholder="123..9..."
                   type="text">
          </div>
          <div v-if="produto_pergunta.tipo==='texto'">
            <textarea :data-produto_pergunta_id="produto_pergunta.id"
                      :class="produto_pergunta.obrigatorio?'required':''"
                      :data-produto_questionario_id="produto_pergunta.questionario_id"
                      :data-tamanho="produto_pergunta.produto_respostas_textos.tamanho"
                      class="texto  form-control question"
                      placeholder="Digite o texto"></textarea>
          </div>
          <div v-if="produto_pergunta.tipo==='binario'">
            <select :data-produto_pergunta_id="produto_pergunta.id"
                    :class="produto_pergunta.obrigatorio?'required':''"
                    :data-produto_questionario_id="produto_pergunta.questionario_id"
                    class="binario form-control question">
              <option value="sim">sim</option>
              <option value="nao">não</option>
            </select>
          </div>
          <div v-if="produto_pergunta.tipo==='arquivo'">
            <input :data-produto_pergunta_id="produto_pergunta.id"
                   :class="produto_pergunta.obrigatorio?'required':''"
                   :data-produto_questionario_id="produto_pergunta.questionario_id" capture="environment"
                   :data-tamanho="produto_pergunta.produto_respostas_arquivos.tamanho"
                   class="arquivo form-control question"
                   type="file">
          </div>

          <div v-if="produto_pergunta.tipo==='unica-escolha'"
               :class="produto_pergunta.obrigatorio?'required':''"
               :data-produto_pergunta_id="produto_pergunta.id"
               :data-produto_questionario_id="produto_pergunta.questionario_id"
               class="question unica-escolha">
            <div v-for="produto_respostas_escolha in  produto_pergunta.produto_respostas_escolhas"
                 v-bind:key="produto_respostas_escolha"
                 class="options form-check">
              <input :name="produto_pergunta.id"
                     :value="produto_respostas_escolha.id+' - '+produto_respostas_escolha.resposta"
                     class="form-check-input"
                     type="radio">
              <label class="form-check-label">{{ produto_respostas_escolha.resposta }}</label><br>
            </div>

          </div>

          <div v-if="produto_pergunta.tipo==='multipla-escolha'"
               :class="produto_pergunta.obrigatorio?'required':''"
               :data-produto_pergunta_id="produto_pergunta.id"
               :data-produto_questionario_id="produto_pergunta.questionario_id"
               class="question multipla-escolha">
            <div v-for="produto_respostas_escolha in  produto_pergunta.produto_respostas_escolhas"
                 v-bind:key="produto_respostas_escolha"
                 class="options form-check">
              <input :name="produto_pergunta.id"
                     :value="produto_respostas_escolha.id+' - '+produto_respostas_escolha.resposta"
                     class="form-check-input"
                     type="checkbox">
              <label class="form-check-label">{{ produto_respostas_escolha.resposta }}</label><br>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style scoped>
.cliente {
  border-bottom: 1px solid var(--bs-card-border-color);
}

.input-danger {
  border: darkred 1px solid !important;
}
</style>