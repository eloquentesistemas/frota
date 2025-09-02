create table pessoas
(
    id                  bigint primary key auto_increment,
    nome                varchar(200)                                           not null,
    descritivo          text                                                   null,
    cpf                 varchar(11)                                            null,
    cnpj                varchar(14)                                            null,
    email               varchar(200)                                           null,
    telefone            varchar(30)                                            null,
    celular             varchar(30)                                            null,
    tipo                enum ('cliente','fornecedor', 'funcionario','gerente') not null,
    ativo               boolean,
    cidade_id           bigint                                                 not null,
    pessoa_categoria_id bigint                                                 not null,
    created_at          timestamp                                              null,
    updated_at          timestamp                                              null
);

create table pessoa_categorias
(
    id          bigint primary key auto_increment,
    nome       varchar(200)                                           not null,
    descritivo text                                                   null,
    tipo       enum ('cliente','fornecedor', 'funcionario','gerente') not null,
    ativo      boolean,

    created_at timestamp                                              null,
    updated_at timestamp                                              null
);


create table produtos
(
    id                    bigint primary key auto_increment,
    nome                 varchar(200) not null,
    descritivo           text         null,
    produto_categoria_id bigint       not null,
    ativo                boolean,
    created_at           timestamp    null,
    updated_at           timestamp    null
);

create table produto_categorias
(
    id           bigint primary key auto_increment,
    nome       varchar(200)                                           not null,
    descritivo text                                                   null,
    tipo       enum ('cliente','fornecedor', 'funcionario','gerente') not null,
    ativo      boolean,

    created_at timestamp                                              null,
    updated_at timestamp                                              null
);


create table produto_questionarios
(
    id          bigint primary key auto_increment,
    nome       varchar(200) not null,
    descritivo text         null,
    ativo      boolean,
    produto_id bigint       not null,
    created_at timestamp    null,
    updated_at timestamp    null
);

create table produto_perguntas
(
    id               bigint primary key auto_increment,
    enunciado       text      not null,
    tipo            enum ('decimal', 'inteiro', 'texto', 'binario', 'multipla-escolha', 'unica-escolha', 'arquivo'),
    obrigatorio     boolean,
    ordem           int       not null,
    questionario_id bigint    not null,
    ativo           boolean,
    created_at      timestamp not null,
    updated_at      timestamp not null
);

create table produto_respostas_escolhas
(
    id           bigint primary key auto_increment,
    resposta    text      not null,
    ordem       int       not null,
    indice      int       not null,
    pergunta_id bigint    not null,
    created_at  timestamp not null,
    updated_at  timestamp not null
);

create table produto_respostas_textos
(
    id           bigint primary key auto_increment,
    tamanho     int       not null,
    pergunta_id bigint    not null,
    created_at  timestamp not null,
    updated_at  timestamp not null
);


create table pedido_propostas
(
    id           bigint primary key auto_increment,
    descritivo text      null,
    ativo      boolean,
    produto_id bigint    not null,
    pessoa_id  bigint    not null,
    created_at timestamp null,
    updated_at timestamp null
);

create table pedido_respostas
(
    id                        bigint primary key auto_increment,
    pedido_proposta_id      bigint      not null,
    produto_questionario_id bigint      not null,
    produto_pergunta_id     bigint      not null,
    resposta                text        not null,
    tipo_resposta           varchar(20) not null,
    created_at              timestamp   null,
    updated_at              timestamp   null
);
