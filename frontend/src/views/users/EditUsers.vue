<template>
    <layout-page>
      <div class="row">
        <div class="col-12 p-4">
          <div class="row">
            <div class="col-12 ps-4 p-3 ">
              <div class="float-start">
                <h5>Editar Usuários</h5>
              </div>
              <div class="float-end">
                <button-widget cor="azul" href="/users/index" tamanho="M">
                  Voltar
                </button-widget>
              </div>
            </div>

          </div>

          <div class="row">
            <FormUsers></FormUsers>
            <div class="col-4">
              <button class="btn btn-primary mt-4" type="button" @click="sendForm">Salvar</button>
            </div>

          </div>
        </div>
      </div>

    </layout-page>

</template>

<script>
import FormUsers from "@/views/users/FormUsers.vue";
import RequestHelper from "@/services/RequestHelper";
import ButtonWidget from "@/components/widget/buttonWidget.vue";
import LayoutPage from "@/components/page/layoutPage.vue";
import toastr from "toastr/build/toastr.min";

export default {
    name: "EditUsers",
    components: {LayoutPage, ButtonWidget, FormUsers},
    methods: {
        async edit(id) {
            let request = new RequestHelper();
            let response = await request.getAuth(process.env.VUE_APP_API_HOST_NAME + '/api/users/' + id, {});
            document.getElementById('name').value = response.data.name;
            document.getElementById('email').value = response.data.email;




        },
        async sendForm() {
            let dataForm = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,


                _method: 'PUT'

            }
            if (!dataForm.parent_id) {
                delete dataForm.parent_id
            }
            let request = new RequestHelper();
            let response = await request.postAuth(process.env.VUE_APP_API_HOST_NAME + '/api/users/' + this.$route.params.id, dataForm);
            if (response.data?.id) {
                toastr.success('Salvo com sucesso')
            } else {
                if (response.response.data?.message) {
                    toastr.error(response.response.data?.message);
                } else {
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