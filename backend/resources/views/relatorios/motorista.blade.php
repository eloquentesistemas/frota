@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Motorista</h2>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="cidade_id">Cidade</label>
                <select id="cidade_id" class="form-control">
                    <option value="">Todas</option>
                    @foreach($cidades as $cidade)
                        <option value="{{ $cidade->id }}">{{ $cidade->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="situacao_cnh">Situação CNH</label>
                <select id="situacao_cnh" class="form-control">
                    <option value="">Todas</option>
                    <option value="CNH em dia">CNH em dia</option>
                    <option value="CNH vencida">CNH vencida</option>
                    <option value="Sem CNH cadastrada">Sem CNH cadastrada</option>
                </select>
            </div>
        </div>

        <table id="clientes-table" class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Cidade</th>
                <th>Situação CNH</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
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
            var table = $('#clientes-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('motorista.index') }}",
                    data: function (d) {
                        d.cidade_id = $('#cidade_id').val();
                        d.situacao_cnh = $('#situacao_cnh').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'pessoas.id' },
                    { data: 'nome', name: 'pessoas.nome' },
                    { data: 'telefone', name: 'pessoas.telefone' },
                    { data: 'cidade', name: 'cidades.nome' },
                    { data: 'situacao_cnh', name: 'situacao_cnh' }
                ],
                dom: 'Bfrtip',
                buttons: buttons
            });

            // aplicar filtros
            $('#cidade_id, #situacao_cnh').change(function () {
                table.ajax.reload();
            });
        });
    </script>
@endpush
