import {createRouter, createWebHistory} from "vue-router";

import LoginForm from "@/views/auth/LoginForm.vue";
import notFound from "@/views/errors/NotFound.vue";
import Middleware from "@/services/Middleware";
import IndexUsers from "@/views/users/IndexUsers.vue";
import createUsers from "@/views/users/CreateUsers.vue";
import editUsers from "@/views/users/EditUsers.vue";
import createCidades from "@/views/cidades/CreateCidades.vue";
import indexCidades from "@/views/cidades/IndexCidades.vue";
import editCidades from "@/views/cidades/EditCidades.vue";
import createAbastecimentoVeiculos from "@/views/abastecimento_veiculos/CreateAbastecimentoVeiculos.vue";
import indexAbastecimentoVeiculos from "@/views/abastecimento_veiculos/IndexAbastecimentoVeiculos.vue";
import editAbastecimentoVeiculos from "@/views/abastecimento_veiculos/EditAbastecimentoVeiculos.vue";
import createContas from "@/views/contas/CreateContas.vue";
import indexContas from "@/views/contas/IndexContas.vue";
import editContas from "@/views/contas/EditContas.vue";
import createFaturamentos from "@/views/faturamentos/CreateFaturamentos.vue";
import indexFaturamentos from "@/views/faturamentos/IndexFaturamentos.vue";
import editFaturamentos from "@/views/faturamentos/EditFaturamentos.vue";
import createNaturezaFinanceiras from "@/views/natureza_financeiras/CreateNaturezaFinanceiras.vue";
import indexNaturezaFinanceiras from "@/views/natureza_financeiras/IndexNaturezaFinanceiras.vue";
import editNaturezaFinanceiras from "@/views/natureza_financeiras/EditNaturezaFinanceiras.vue";
import createPagamentos from "@/views/pagamentos/CreatePagamentos.vue";
import indexPagamentos from "@/views/pagamentos/IndexPagamentos.vue";
import editPagamentos from "@/views/pagamentos/EditPagamentos.vue";
import createPessoaVeiculos from "@/views/pessoa_veiculos/CreatePessoaVeiculos.vue";
import indexPessoaVeiculos from "@/views/pessoa_veiculos/IndexPessoaVeiculos.vue";
import editPessoaVeiculos from "@/views/pessoa_veiculos/EditPessoaVeiculos.vue";
import createPessoas from "@/views/pessoas/CreatePessoas.vue";
import indexPessoas from "@/views/pessoas/IndexPessoas.vue";
import editPessoas from "@/views/pessoas/EditPessoas.vue";
import createPneusVeiculos from "@/views/pneus_veiculos/CreatePneusVeiculos.vue";
import indexPneusVeiculos from "@/views/pneus_veiculos/IndexPneusVeiculos.vue";
import editPneusVeiculos from "@/views/pneus_veiculos/EditPneusVeiculos.vue";
import createVeiculos from "@/views/veiculos/CreateVeiculos.vue";
import indexVeiculos from "@/views/veiculos/IndexVeiculos.vue";
import editVeiculos from "@/views/veiculos/EditVeiculos.vue";
const routes = [
    {
        path: '/login',
        name: 'LoginForm',
        component: LoginForm,
        meta: {
            auth: false
        }

    },
    //user

    {
        path: '/users/create',
        name: 'usersCreate',
        component: createUsers,
        meta: {
            auth: true
        }
    },

    {
        path: '/users/index',
        name: 'users',
        component: IndexUsers,
        meta: {
            auth: true
        }
    },
    {
        path: '/users/edit/:id',
        name: 'editUsers',
        component: editUsers,
        meta: {
            auth: true
        }
    },
    //cidades

    {
        path: '/cidades/create',
        name: 'cidadesCreate',
        component: createCidades,
        meta: {
            auth: true
        }
    },

    {
        path: '/cidades/index',
        name: 'indexCidades',
        component: indexCidades,
        meta: {
            auth: true
        }
    },
    {
        path: '/cidades/edit/:id',
        name: 'editCidades',
        component: editCidades,
        meta: {
            auth: true
        }
    },

// abastecimento_veiculos
    {
        path: '/abastecimento_veiculos/create',
        name: 'abastecimentoVeiculosCreate',
        component: createAbastecimentoVeiculos,
        meta: { auth: true }
    },
    {
        path: '/abastecimento_veiculos/index',
        name: 'indexAbastecimentoVeiculos',
        component: indexAbastecimentoVeiculos,
        meta: { auth: true }
    },
    {
        path: '/abastecimento_veiculos/edit/:id',
        name: 'editAbastecimentoVeiculos',
        component: editAbastecimentoVeiculos,
        meta: { auth: true }
    },

// contas
    {
        path: '/contas/create',
        name: 'contasCreate',
        component: createContas,
        meta: { auth: true }
    },
    {
        path: '/contas/index',
        name: 'indexContas',
        component: indexContas,
        meta: { auth: true }
    },
    {
        path: '/contas/edit/:id',
        name: 'editContas',
        component: editContas,
        meta: { auth: true }
    },

// faturamentos
    {
        path: '/faturamentos/create',
        name: 'faturamentosCreate',
        component: createFaturamentos,
        meta: { auth: true }
    },
    {
        path: '/faturamentos/index',
        name: 'indexFaturamentos',
        component: indexFaturamentos,
        meta: { auth: true }
    },
    {
        path: '/faturamentos/edit/:id',
        name: 'editFaturamentos',
        component: editFaturamentos,
        meta: { auth: true }
    },

// natureza_financeiras
    {
        path: '/natureza_financeiras/create',
        name: 'naturezaFinanceirasCreate',
        component: createNaturezaFinanceiras,
        meta: { auth: true }
    },
    {
        path: '/natureza_financeiras/index',
        name: 'indexNaturezaFinanceiras',
        component: indexNaturezaFinanceiras,
        meta: { auth: true }
    },
    {
        path: '/natureza_financeiras/edit/:id',
        name: 'editNaturezaFinanceiras',
        component: editNaturezaFinanceiras,
        meta: { auth: true }
    },

// pagamentos
    {
        path: '/pagamentos/create/:conta_id',
        name: 'pagamentosCreate',
        component: createPagamentos,
        meta: { auth: true }
    },
    {
        path: '/pagamentos/index/:conta_id',
        name: 'indexPagamentos',
        component: indexPagamentos,
        meta: { auth: true }
    },
    {
        path: '/pagamentos/edit/:id',
        name: 'editPagamentos',
        component: editPagamentos,
        meta: { auth: true }
    },

// pessoa_veiculos
    {
        path: '/pessoa_veiculos/create',
        name: 'pessoaVeiculosCreate',
        component: createPessoaVeiculos,
        meta: { auth: true }
    },
    {
        path: '/pessoa_veiculos/index',
        name: 'indexPessoaVeiculos',
        component: indexPessoaVeiculos,
        meta: { auth: true }
    },
    {
        path: '/pessoa_veiculos/edit/:id',
        name: 'editPessoaVeiculos',
        component: editPessoaVeiculos,
        meta: { auth: true }
    },

// pessoas
    {
        path: '/pessoas/create',
        name: 'pessoasCreate',
        component: createPessoas,
        meta: { auth: true }
    },
    {
        path: '/pessoas/index',
        name: 'indexPessoas',
        component: indexPessoas,
        meta: { auth: true }
    },
    {
        path: '/pessoas/edit/:id',
        name: 'editPessoas',
        component: editPessoas,
        meta: { auth: true }
    },

// pneus_veiculos
    {
        path: '/pneus_veiculos/create',
        name: 'pneusVeiculosCreate',
        component: createPneusVeiculos,
        meta: { auth: true }
    },
    {
        path: '/pneus_veiculos/index',
        name: 'indexPneusVeiculos',
        component: indexPneusVeiculos,
        meta: { auth: true }
    },
    {
        path: '/pneus_veiculos/edit/:id',
        name: 'editPneusVeiculos',
        component: editPneusVeiculos,
        meta: { auth: true }
    },

// veiculos
    {
        path: '/veiculos/create',
        name: 'veiculosCreate',
        component: createVeiculos,
        meta: { auth: true }
    },
    {
        path: '/veiculos/index',
        name: 'indexVeiculos',
        component: indexVeiculos,
        meta: { auth: true }
    },
    {
        path: '/veiculos/edit/:id',
        name: 'editVeiculos',
        component: editVeiculos,
        meta: { auth: true }
    },





    {
        path: '/404',
        component: notFound
    },
    {
        path: '/',
        redirect: '/login'
    }

];
const router = createRouter({history: createWebHistory(), routes});
router.beforeEach((to) => {
    let middleware = new Middleware();
    if (!middleware.logout(to)) {
        middleware.routeExists(to);
        middleware.validateHash(to);

    }


})
router.afterEach((to) => {
    let middleware = new Middleware();
    middleware.setRegisterLastRouteBeforeLogin();
    middleware.userPermissions(to);
    middleware.finishLoading();
});
export default router;