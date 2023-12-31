@extends('layout')
@section('title')
Cadastro de despesa
@endsection
@section('body')
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('despesas.index') }}">Despesas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cadastro</li>
    </ol>
</nav>
<div class="btn btn-dark" id="procurarFixa">
    Procurar despesa fixa <i class="ml-2 fas fa-search"></i>
</div>
<br>
<br>
<form method="POST" action="{{ route('despesas.store')}}">
    @csrf
    <div class="row">
        <div class="col-12" id='alertarLancamentoDuplicado'></div>
        <div class="col-6 form-group">
            <label for="nome">Nome da despesa</label>
            <x-input name="nome" type="text" value="{{ old('nome', $descricao) }}"></x-input>
        </div>
        <div class="col-6 form-group">
            <label for="data_vencimento">Data do vencimento</label>
            <x-input name="data_vencimento" type="date" value="{{ old('data_vencimento', $data) }}"></x-input>
        </div>
        <div class="col-6 form-group">
            <label for="valor">Valor</label>
            <x-input name="valor" type="text" value="{{ old('valor', abs($valor)) }}"></x-input>
        </div>

        <div class="col-6 form-group">
            <label for="data_pagamento">Data do Pagamento</label>
            <x-input name="data_pagamento" type="date" value="{{ old('data_pagamento', $data) }}"></x-input>
        </div>

        <div class="col-6 form-group">
            <label for="local_id">Local</label>
            <x-select name="local_id">
                <option></option>
                @foreach ($locais as $local)
                    <option value="{{ $local->id }}" {{ old('local_id') == $local->id ? 'selected' : '' }}>{{ $local->nome }}</option>
                @endforeach
            </x-select>
        </div>

        <div class="col-6 form-group">
            <label for="forma_pagamento">Forma de pagamento</label>
            <x-select name="forma_pagamento">
                {{-- <option></option> --}}
                @foreach ($formasPagamento as $pagamento)
                    <option value="{{ $pagamento }}" {{ old('forma_pagamento', $forma_pagamento) == $pagamento ? 'selected' : '' }}>{{ $pagamento }}</option>
                @endforeach
            </x-select>
        </div>

        <div class="col-6 form-group">
            <label for="comprovante_id">Comprovante ID</label>
            <x-input name="comprovante_id" type="text" value="{{ old('comprovante_id', $comprovante_id) }}"></x-input>
        </div>

        <div class="col-6 form-group">
            <label for="conta_id">Conta</label>
            <x-select name="conta_id">
                <option></option>
                @foreach ($contas as $contaBanco)
                    <option value="{{ $contaBanco->id }}" {{ old('conta_id', $conta) == $contaBanco->id ? 'selected' : '' }}>{{ $contaBanco->nome }}</option>
                @endforeach
            </x-select>
        </div>

        <div class="col-12 form-group">
            <label for="observacao">Observação</label>
            <x-text-area name="observacao" type="text" value="{{ old('observacao') }}"></x-text-area>
        </div>
        <div class="col-6 form-group">
            <x-input name="fixas_id" type="hidden" value="{{ old('fixas_id') }}"></x-input>
        </div>
    </div>
    {{-- <p>@json($fixas)</p> --}}
    <input type="submit" class='btn btn-success'>
</form>
@endsection
@section('script')
<script>
    FIXAS = JSON.parse(@json($fixas));

    $("#procurarFixa").click( (e) => {
        $("#modal-title2").text('Despesas Fixas')
        $("#modal2").modal("show")
        let html = ``
        let tbody = ``

        FIXAS.forEach(element => {
            tbody += `
                <tr class="${( element.despesas.length > 0 ) ? 'table-warning': ''}">
                    <td>${element.nome}</td>
                    <td>${element.valor}</td>
                    <td>${element.dia_vencimento}</td>
                    <td>${element.local.nome}</td>
                    <td>
                        <div class="btn btn-secondary puxarInfos"
                            data-nome="${element.nome}"
                            data-valor="${element.valor}"
                            data-dia_vencimento="${element.dia_vencimento}"
                            data-local_id="${element.local_id}"
                            data-id="${element.id}"
                            data-despesas="${element.despesas.length}"
                        >
                            <i class="fas fa-check"></i>
                        </div>
                    </td>
                </tr>
            `
        });

        html += `
            <x-table id='tableFixas'>
                <x-table-header>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Dia de vencimento</th>
                    <th>Local</th>
                    <th></th>
                </x-table-header>
                <tbody>${tbody}</tbody>
            </x-table>
        `
        $("#modal-body2").html(html)

        $(".puxarInfos").click( (e) => {
            
            $("#alertarLancamentoDuplicado").html(``)

            let target = $(e.currentTarget)

            $("#nome").val(target.data('nome'))
            $("#valor").val(target.data('valor'))
            $("#local_id").val(target.data('local_id'))

            let dataJs = new Date()
            let dia = dataJs.getUTCDate()
            let mes = ('0' + (dataJs.getMonth() + 1)).slice(-2);
            let mesPassado = ('0' + (dataJs.getMonth() )).slice(-2);
            let ano = dataJs.getUTCFullYear()

            $("#data_vencimento").val(ano +'-'+ mes +'-'+ ('0' + target.data('dia_vencimento')).slice(-2))

            $("#data_referencia").val(ano +'-'+ mesPassado +'-'+ ('0' + target.data('dia_vencimento')).slice(-2))

            $("#fixas_id").val(target.data('id'))

            $("#modal2").modal("hide")

            if (target.data('despesas') > 0) {
                alertarPagamento()
            }
        })

        $("#tableFixas").dataTable()
    })

    function alertarPagamento() {
        $("#alertarLancamentoDuplicado").html(`
            <h5 class='alert alert-warning'>
                Já existe uma despesa lançada para este mês!
            </h5>
        `)
    }
</script>
@endsection
