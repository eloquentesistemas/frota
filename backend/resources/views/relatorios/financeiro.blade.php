@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Relatório Financeiro</h2>

        <div class="row mb-3">
            <div class="col-md-4">
                <div class="alert alert-info">
                    <strong>Total a Pagar:</strong> R$ {{ number_format($totalPagar,2,',','.') }} <br>
                    <strong>Total a Receber:</strong> R$ {{ number_format($totalReceber,2,',','.') }} <br>
                    <strong>Total Pago:</strong> R$ {{ number_format($totalPago,2,',','.') }}
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="modalidade">Modalidade</label>
                <select id="modalidade" class="form-control">
                    <option value="">Todas</option>
                    <option value="pagar">Pagar</option>
                    <option value="receber">Receber</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="natureza_financeira_id">Natureza Financeira</label>
                <select id="natureza_financeira_id" class="form-control">
                    <option value="">Todas</option>
                    @foreach($naturezas as $n)
                        <option value="{{ $n->id }}">{{ $n->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="data_inicio">Data Início</label>
                <input type="date" id="data_inicio" class="form-control">
            </div>

            <div class="col-md-3">
                <label for="data_fim">Data Fim</label>
                <input type="date" id="data_fim" class="form-control">
            </div>
        </div>

        <table id="financeiro-table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Data</th>
                <th>Nome</th>
                <th>Modalidade</th>
                <th>Valor</th>
                <th>Parcelas</th>
                <th>Natureza</th>
                <th>Descritivo</th>
                <th>Status Pagamento</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(function () {
            var buttons = [
                {
                    extend: 'copy',
                    text: '<i class="fa fa-files-o" aria-hidden="true"></i> ' + 'Copiar',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: ':visible',
                    },
                    footer: true,
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-csv" aria-hidden="true"></i> ' + 'CSV',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: ':visible',
                    },
                    footer: true,
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel" aria-hidden="true"></i> ' + 'Excel',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: ':visible',
                    },
                    footer: true,
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf" aria-hidden="true"></i> ' + 'PDF',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: ':visible',
                    },
                    footer: true,
                }
            ];
            var table = $('#financeiro-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('financeiro.index') }}",
                    data: function(d){
                        d.modalidade = $('#modalidade').val();
                        d.natureza_financeira_id = $('#natureza_financeira_id').val();
                        d.data_inicio = $('#data_inicio').val();
                        d.data_fim = $('#data_fim').val();
                    }
                },
                columns: [
                    { data: 'data_ocorrido', name: 'contas.data_ocorrido' },
                    { data: 'nome', name: 'contas.nome' },
                    { data: 'modalidade', name: 'contas.modalidade' },
                    { data: 'valor', name: 'contas.valor' },
                    { data: 'parcelas', name: 'contas.parcelas' },
                    { data: 'natureza_financeira', name: 'natureza_financeiras.nome' },
                    { data: 'descritivo', name: 'contas.descritivo' },
                    { data: 'status_pagamento', name: 'status_pagamento' }
                ],
                dom: 'Bfrtip',
                buttons:buttons
            });

            $('#modalidade,#natureza_financeira_id,#data_inicio,#data_fim').change(function(){
                table.ajax.reload();
            });
        });
    </script>
@endpush
