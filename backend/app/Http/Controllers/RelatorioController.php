<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function cliente(Request $request)
    {
        if ($request->ajax()) {
            $query = Pessoa::where('tipo', 'cliente')
                ->select(
                    'pessoas.id',
                    'pessoas.nome',
                    'pessoas.telefone',
                    DB::raw("CASE
                    WHEN vencimento_cnh < CURDATE() THEN 'CNH vencida'
                    WHEN vencimento_cnh IS NULL THEN 'Sem CNH cadastrada'
                    ELSE 'CNH em dia'
                END as situacao_cnh"),
                    'cidades.nome as cidade'
                )
                ->leftJoin('cidades', 'pessoas.cidade_id', '=', 'cidades.id');

            // filtro por cidade
            if ($request->filled('cidade_id')) {
                $query->where('pessoas.cidade_id', $request->cidade_id);
            }

            // filtro por situação CNH
            if ($request->filled('situacao_cnh')) {
                if ($request->situacao_cnh === 'CNH vencida') {
                    $query->where('vencimento_cnh', '<', now()->toDateString());
                } elseif ($request->situacao_cnh === 'CNH em dia') {
                    $query->where('vencimento_cnh', '>=', now()->toDateString());
                } elseif ($request->situacao_cnh === 'Sem CNH cadastrada') {
                    $query->whereNull('vencimento_cnh');
                }
            }

            return datatables()->of($query)->make(true);
        }

        $cidades = Cidade::all();
        return view('relatorios.cliente', compact('cidades'));
    }
    public function motorista(Request $request)
    {
        if ($request->ajax()) {
            $query = Pessoa::where('tipo', 'motorista')
                ->select(
                    'pessoas.id',
                    'pessoas.nome',
                    'pessoas.telefone',
                    DB::raw("CASE
                    WHEN vencimento_cnh < CURDATE() THEN 'CNH vencida'
                    WHEN vencimento_cnh IS NULL THEN 'Sem CNH cadastrada'
                    ELSE 'CNH em dia'
                END as situacao_cnh"),
                    'cidades.nome as cidade'
                )
                ->leftJoin('cidades', 'pessoas.cidade_id', '=', 'cidades.id');

            // filtro por cidade
            if ($request->filled('cidade_id')) {
                $query->where('pessoas.cidade_id', $request->cidade_id);
            }

            // filtro por situação CNH
            if ($request->filled('situacao_cnh')) {
                if ($request->situacao_cnh === 'CNH vencida') {
                    $query->where('vencimento_cnh', '<', now()->toDateString());
                } elseif ($request->situacao_cnh === 'CNH em dia') {
                    $query->where('vencimento_cnh', '>=', now()->toDateString());
                } elseif ($request->situacao_cnh === 'Sem CNH cadastrada') {
                    $query->whereNull('vencimento_cnh');
                }
            }

            return datatables()->of($query)->make(true);
        }

        $cidades = Cidade::all();
        return view('relatorios.motorista', compact('cidades'));
    }
    public function relatorioVeiculos(Request $request)
    {
        // Carregar dados para selects
        $veiculos = \App\Models\Veiculo::select('id','nome','placa')->get();
        $motoristas = \App\Models\Pessoa::where('tipo','motorista')->select('id','nome')->get();

        return view('relatorios.veiculos', compact('veiculos','motoristas'));
    }

// Rota AJAX para DataTable
    public function veiculos(Request $request)
    {
        // Se for AJAX (DataTable)
        if ($request->ajax()) {
            $query = DB::table('veiculos')
                ->join('pessoa_veiculos','pessoa_veiculos.veiculo_id','=','veiculos.id')
                ->join('pessoas','pessoas.id','=','pessoa_veiculos.pessoa_id')
                ->select(
                    'veiculos.nome as veiculo',
                    'veiculos.placa',
                    'pessoas.nome as motorista',
                    'pessoas.telefone',
                    'pessoas.vencimento_cnh'
                )
                ->where('pessoas.tipo','motorista');

            // Filtros
            if ($request->filled('veiculo_id')) {
                $query->where('veiculos.id', $request->veiculo_id);
            }
            if ($request->filled('motorista_id')) {
                $query->where('pessoas.id', $request->motorista_id);
            }
            if ($request->filled('placa')) {
                $query->where('veiculos.placa','like','%'.$request->placa.'%');
            }

            return datatables()->of($query)
                ->editColumn('vencimento_cnh', function($item){
                    if(!$item->vencimento_cnh) return '<span class="text-secondary">Sem CNH</span>';
                    if($item->vencimento_cnh < now()->toDateString()) return '<span class="text-danger">'.$item->vencimento_cnh.' (Vencida)</span>';
                    return '<span class="text-success">'.$item->vencimento_cnh.' (Em dia)</span>';
                })
                ->rawColumns(['vencimento_cnh'])
                ->make(true);
        }

        // Dados para selects do filtro
        $veiculos = DB::table('veiculos')->select('id','nome','placa')->get();
        $motoristas = DB::table('pessoas')->where('tipo','motorista')->select('id','nome')->get();

        return view('relatorios.veiculos', compact('veiculos','motoristas'));
    }

    public function faturamento(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('faturamentos')
                ->join('pessoas as motoristas','motoristas.id','=','faturamentos.pessoa_motorista_id')
                ->join('veiculos','veiculos.id','=','faturamentos.veiculo_id')
                ->join('cidades as origem','origem.id','=','faturamentos.origem_cidade_id')
                ->join('cidades as destino','destino.id','=','faturamentos.destino_cidade_id')
                ->select(
                    'faturamentos.id',
                    'motoristas.nome as motorista',
                    'veiculos.nome as veiculo',
                    'veiculos.placa',
                    'origem.nome as cidade_origem',
                    'destino.nome as cidade_destino',
                    'faturamentos.data_embarque',
                    'faturamentos.valor_total',
                    'faturamentos.valor_acerto_motorista'
                );

            // Filtros
            if ($request->filled('motorista_id')) {
                $query->where('motoristas.id', $request->motorista_id);
            }
            if ($request->filled('veiculo_id')) {
                $query->where('veiculos.id', $request->veiculo_id);
            }
            if ($request->filled('origem_cidade_id')) {
                $query->where('origem.id', $request->origem_cidade_id);
            }
            if ($request->filled('destino_cidade_id')) {
                $query->where('destino.id', $request->destino_cidade_id);
            }
            if ($request->filled('data_inicio')) {
                $query->whereDate('faturamentos.data_embarque', '>=', $request->data_inicio);
            }
            if ($request->filled('data_fim')) {
                $query->whereDate('faturamentos.data_embarque', '<=', $request->data_fim);
            }

            return datatables()->of($query)
                ->editColumn('valor_total', function($item){
                    return 'R$ '.number_format($item->valor_total,2,',','.');
                })
                ->editColumn('valor_acerto_motorista', function($item){
                    return 'R$ '.number_format($item->valor_acerto_motorista,2,',','.');
                })
                ->make(true);
        }

        // Dados para selects
        $motoristas = DB::table('pessoas')->where('tipo','motorista')->select('id','nome')->get();
        $veiculos = DB::table('veiculos')->select('id','nome','placa')->get();
        $cidades = DB::table('cidades')->select('id','nome')->get();

        return view('relatorios.faturamento', compact('motoristas','veiculos','cidades'));
    }


    public function financeiro(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('contas')
                ->leftJoin('natureza_financeiras','natureza_financeiras.id','=','contas.natureza_financeira_id')
                ->leftJoin('pagamentos','pagamentos.conta_id','=','contas.id')
                ->select(
                    'contas.id',
                    'contas.data_ocorrido',
                    'contas.nome',
                    'contas.modalidade',
                    'contas.valor',
                    'contas.parcelas',
                    'contas.descritivo',
                    'natureza_financeiras.nome as natureza_financeira',
                    DB::raw('IF(SUM(pagamentos.valor) >= contas.valor, "Pago", "Não pago") as status_pagamento')
                )
                ->groupBy('contas.id','contas.data_ocorrido','contas.nome','contas.modalidade','contas.valor','contas.parcelas','contas.descritivo','natureza_financeiras.nome');

            // Filtros
            if ($request->filled('modalidade')) {
                $query->where('contas.modalidade', $request->modalidade);
            }
            if ($request->filled('natureza_financeira_id')) {
                $query->where('contas.natureza_financeira_id', $request->natureza_financeira_id);
            }
            if ($request->filled('data_inicio')) {
                $query->whereDate('contas.data_ocorrido','>=',$request->data_inicio);
            }
            if ($request->filled('data_fim')) {
                $query->whereDate('contas.data_ocorrido','<=',$request->data_fim);
            }

            return datatables()->of($query)
                ->editColumn('valor', function($item){
                    return 'R$ '.number_format($item->valor,2,',','.');
                })
                ->make(true);
        }

        // Resumo financeiro
        $totalPagar = DB::table('contas')
            ->where('modalidade','pagar')
            ->sum('valor');

        $totalReceber = DB::table('contas')
            ->where('modalidade','receber')
            ->sum('valor');

        $totalPago = DB::table('pagamentos')->sum('valor');

        $naturezas = DB::table('natureza_financeiras')->select('id','nome')->get();

        return view('relatorios.financeiro', compact('naturezas','totalPagar','totalReceber','totalPago'));
    }



}
