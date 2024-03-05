@extends('layout')
@section('title')
Recebimentos
@endsection
@section('body')
<div class='mb-2 d-flex justify-content-between'>
    <h3>Recebimentos</h3>
    <div>
        <div class="btn btn-dark" id='btnProcurarRebecimento'>
            <i class='fas fa-search'></i>
        </div>
        <x-botao-imprimir class='mr-2' href="{{ route('pdf_confirmar_depositos') }}"></x-botao-imprimir>
        <x-botao-novo href="{{ route('recebimentos.create') }}"></x-botao-novo>
    </div>
</div>

<form id='mostrarOpcaoProcura' style='display:none' method='GET' action="{{ route('procurarRecebimento')}}">
    @csrf
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <x-form-group type="number" name="valor">Valor</x-form-group>
        </div>

        <div class="col-sm-6 col-md-3">
            <x-form-group type="date" name="data">Data</x-form-group>
        </div>

        <div class="col-sm-6 col-md-3 form-group">
            <label for="representante_id">Representante</label>
            <x-select name="representante_id">
                <option></option>
                @foreach($representantes as $representante)
                    <option value="{{$representante->id}}" {{ (old('representante_id') ?? $parcela->representante_id ?? '') == $representante->id ? 'selected' : '' }} >
                        {{$representante->pessoa->nome}}
                    </option>
                @endforeach
            </x-select>
        </div>

        <div class="col-sm-6 col-md-3 form-group">
            <label for="conta_id">Conta</label>
            <x-select name="conta_id">
                <option></option>
                @foreach($contas as $conta)
                    <option value="{{$conta->id}}" {{ (old('conta_id') ?? $parcela->conta_id ?? '') == $conta->id ? 'selected' : '' }} >
                        {{$conta->nome}}
                    </option>
                @endforeach
            </x-select>
        </div>
       
        <div class="col-sm-6 col-md-3 form-group">
            <label for="confirmado">Pagamento confirmado?</label>
            <x-select name="confirmado">
                <option value=2>Ambos</option>
                <option value=1>Sim</option>
                <option value=0>Não</o  ption>
            </x-select>
        </div>

    </div>
    <input type="submit" class='btn btn-dark' value='Procurar'>
    {{-- <div class='btn btn-dark'>Procurar</div> --}}
</form>
<br>
<div id='tabelaRecebimentos'></div>

<div id='formUltimosRecebimentos'>
    <x-table id="tableRecebimentos">
        <x-table-header>
            <tr>
                <th colspan=8>Últimos lançamentos</th>
            </tr>
            <tr>
                <th>Data do pagamento</th>
                <th>Cliente</th>
                <th>Valor</th>
                <th>Conta</th>
                <th>Confirmado?</th>
                <th>Representante</th>
                {{-- <th>Dados da dívida</th> --}}
                <th><i class='fas fa-edit'></i></th>
            </tr>
        </x-table-header>
        <tbody>
                @foreach ($pgtoRepresentante as $pgto)
                <tr class="{{ !$pgto->confirmado ? 'table-danger' : ''}}">
                    <td>@data($pgto->data)</td>
                    <td>
                        @if (!$pgto->parcela()->exists())
                            CRÉDITO CONTA-CORRENTE
                        @else
                            {{ $pgto->parcela->venda_id !== NULL ? $pgto->parcela->venda->cliente->pessoa->nome : $pgto->parcela->nome_cheque }}
                        @endif
                    </td>
                    {{-- <td>{{ $pgto->parcela->venda_id !== NULL ? $pgto->parcela->venda->cliente->pessoa->nome : $pgto->parcela->nome_cheque }}</td> --}}

                    <td>@moeda($pgto->valor)</td>
                    <td>{{ $pgto->conta->nome ?? ''}}</td>
                    <td>{{ $pgto->confirmado ? 'Sim' : 'Não' }}</td>
                    <td>{{ $pgto->representante->pessoa->nome }}</td>
                    <td>
                        <div class='d-flex'>
                            <a class='btn btn-dark mr-2' href={{ route('recebimentos.edit', $pgto->id) }}>
                                <i class='fas fa-edit'></i>
                            </a>
                            <x-botao-excluir action="{{ route('recebimentos.destroy', $pgto->id) }}">
                            </x-botao-excluir>
                        </div>
                    </td>
                </tr>
                @endforeach
        </tbody>
    </x-table>
    </div>
@endsection
@section('script')
<script>
    @if(Session::has('message'))
        toastr["success"]("{{ Session::get('message') }}")
    @endif

    $(document).ready( function () {

        $('#tableRecebimentos').DataTable({
            order: []
        });

        $("#btnProcurarRebecimento").click( () => {
            $("#mostrarOpcaoProcura").toggle()
            $("#formUltimosRecebimentos").toggle()

        })

        $("form").submit( (e) => {
            e.preventDefault()
            procurarRebecimentos(e)
        })

        function procurarRebecimentos(e) {
            let table = ``
            let tableRow = ``

            let dataForm = $(e.target).serialize()

            $.ajax({
                type: 'GET',
                url: e.target.action,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: dataForm,
                dataType: 'json',
                beforeSend: () => {
                    Swal.showLoading()
                },
                success: (response) => {

                    if (response.length === 0) {
                        tableRow = `
                            <tr>
                                <td colspan=5>Nenhum resultado!</td>
                            </tr>  
                        `
                    } else {
                        response.forEach(element => {
                            let conta = (element.conta) ? element.conta.nome : 'Não informada'
                            let confirmado = (element.confirmado == 1) ? 'Sim' : 'Não'
                            let data = transformarData(element.data)
    
                            tableRow += `
                                <tr>
                                    <td>${data}</td>
                                    <td>${element.valor}</td>  
                                    <td>${conta}</td>
                                    <td>${element.representante.pessoa.nome}</td>  
                                    <td>${confirmado}</td>  
                                </tr>  
                            `
                        });
                    }

                    table = `
                        <hr>  
                        <x-table>
                            <tr>
                                <th>Data</th>
                                <th>Valor</th>
                                <th>Conta</th>
                                <th>Representante</th>
                                <th>Confirmado</th>
                            </tr>
                            <tbody>
                                ${tableRow}
                            </tbody>
                        </x-table>
                    `

                    Swal.close()

                    $("#tabelaRecebimentos").html(table)
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    console.error(jqXHR)
                    console.error(textStatus)
                    console.error(errorThrown)
                }
            });
        }

        function transformarData (data) {
            var parts = data.split("-");
            return parts[2]+'/'+parts[1]+'/'+parts[0];
        }

    } );

    
</script>
@endsection
