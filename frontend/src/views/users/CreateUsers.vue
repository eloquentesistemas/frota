<template>
    <layout-page>
      <div class="row">
        <div class="col-12 p-4">
          <div class="row">
            <div class="col-12 p-2 pt-3 ">
              <div class="float-start">
                <h5>Adicionar Usuários</h5>
              </div>
              <div class="float-end">
                <button-widget cor="azul" href="./index" tamanho="M">
                  Voltar
                </button-widget>
              </div>
            </div>

          </div>
          <div class="row">
            <FormUsers></FormUsers>
            <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
          </div>
        </div>
      </div>


    </layout-page>
</template>
<script>
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import FormUsers from "@/views/users/FormUsers.vue";
import RequestHelper from "@/services/RequestHelper";
import toastr from "toastr/build/toastr.min";

export default {
    name: "CreateUsers",
    components: {FormUsers, LayoutPage, ButtonWidget},
    methods: {
        async sendForm() {
            let dataForm = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,


            }
            if (!dataForm.parent_id) {
                delete dataForm.parent_id
            }
            let request = new RequestHelper();
            let response = await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/users', dataForm);
            if (response.data?.id) {
                location.href = '/users/index';
            } else {
                if (response.response.data?.message) {
                    toastr.error(response.response.data?.message);
                } else {
                    toastr.error('Houve um problema ao inserir');
                }

            }
        }
    }
}
</script>
<style scoped>
</style>