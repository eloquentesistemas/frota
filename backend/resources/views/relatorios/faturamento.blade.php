@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Relatório de Faturamento</h2>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="motorista_id">Motorista</label>
                <select id="motorista_id" class="form-control">
                    <option value="">Todos</option>
                    @foreach($motoristas as $m)
                        <option value="{{ $m->id }}">{{ $m->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="veiculo_id">Veículo</label>
                <select id="veiculo_id" class="form-control">
                    <option value="">Todos</option>
                    @foreach($veiculos as $v)
                        <option value="{{ $v->id }}">{{ $v->nome }} - {{ $v->placa }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="origem_cidade_id">Cidade Origem</label>
                <select id="origem_cidade_id" class="form-control">
                    <option value="">Todas</option>
                    @foreach($cidades as $c)
                        <option value="{{ $c->id }}">{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="destino_cidade_id">Cidade Destino</label>
                <select id="destino_cidade_id" class="form-control">
                    <option value="">Todas</option>
                    @foreach($cidades as $c)
                        <option value="{{ $c->id }}">{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="data_inicio">Data Início</label>
                <input type="date" id="data_inicio" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="data_fim">Data Fim</label>
                <input type="date" id="data_fim" class="form-control">
            </div>
        </div>

        <table id="faturamento-table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Motorista</th>
                <th>Veículo</th>
                <th>Placa</th>
                <th>Origem</th>
                <th>Destino</th>
                <th>Data Embarque</th>
                <th>Valor Total</th>
                <th>Valor Acerto Motorista</th>
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
            var table = $('#faturamento-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('faturamento.index') }}",
                    data: function(d){
                        d.motorista_id       = $('#motorista_id').val();
                        d.veiculo_id         = $('#veiculo_id').val();
                        d.origem_cidade_id   = $('#origem_cidade_id').val();
                        d.destino_cidade_id  = $('#destino_cidade_id').val();
                        d.data_inicio        = $('#data_inicio').val();
                        d.data_fim           = $('#data_fim').val();
                    }
                },
                columns: [
                    { data: 'motorista', name: 'motoristas.nome' },
                    { data: 'veiculo', name: 'veiculos.nome' },
                    { data: 'placa', name: 'veiculos.placa' },
                    { data: 'cidade_origem', name: 'origem.nome' },
                    { data: 'cidade_destino', name: 'destino.nome' },
                    { data: 'data_embarque', name: 'faturamentos.data_embarque' },
                    { data: 'valor_total', name: 'faturamentos.valor_total' },
                    { data: 'valor_acerto_motorista', name: 'faturamentos.valor_acerto_motorista' },
                ],
                dom: 'Bfrtip',
                buttons:buttons
            });

            $('#motorista_id,#veiculo_id,#origem_cidade_id,#destino_cidade_id,#data_inicio,#data_fim').change(function(){
                table.ajax.reload();
            });
        });
    </script>
@endpush
