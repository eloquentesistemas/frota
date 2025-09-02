@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Relatório de Veículos com Motoristas</h2>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="veiculo_id">Veículo</label>
                <select id="veiculo_id" class="form-control">
                    <option value="">Todos</option>
                    @foreach($veiculos as $v)
                        <option value="{{ $v->id }}">{{ $v->nome }} - {{ $v->placa }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="motorista_id">Motorista</label>
                <select id="motorista_id" class="form-control">
                    <option value="">Todos</option>
                    @foreach($motoristas as $m)
                        <option value="{{ $m->id }}">{{ $m->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="placa">Placa</label>
                <input type="text" id="placa" class="form-control" placeholder="Digite a placa">
            </div>
        </div>

        <table id="relatorio-table" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Veículo</th>
                <th>Placa</th>
                <th>Motorista</th>
                <th>Telefone</th>
                <th>Vencimento CNH</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <!-- DataTables Buttons -->
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
            var table = $('#relatorio-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('veiculos.index') }}",
                    data: function (d) {
                        d.veiculo_id   = $('#veiculo_id').val();
                        d.motorista_id = $('#motorista_id').val();
                        d.placa        = $('#placa').val();
                    }
                },
                columns: [
                    { data: 'veiculo', name: 'veiculos.nome' },
                    { data: 'placa', name: 'veiculos.placa' },
                    { data: 'motorista', name: 'pessoas.nome' },
                    { data: 'telefone', name: 'pessoas.telefone' },
                    { data: 'vencimento_cnh', name: 'pessoas.vencimento_cnh', orderable: false, searchable: false }
                ],
                dom: 'Bfrtip',
                buttons: buttons
            });

            $('#veiculo_id, #motorista_id').change(function () {
                table.ajax.reload();
            });

            $('#placa').keyup(function () {
                table.ajax.reload();
            });
        });
    </script>
@endpush
