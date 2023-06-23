@extends('layout')
@section('title')
{{ $representante->pessoa->nome }} 
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        @if (!auth()->user()->is_representante)
        <li class="breadcrumb-item"><a href="{{ route('representantes.index') }}">Representantes</a></li>
        @endif
        <li class="breadcrumb-item active">{{ $representante->pessoa->nome }} </li>
    </ol>
</nav>

<div class='mb-2 d-flex justify-content-between'>
    <h3>{{ $representante->pessoa->nome }}</h3>
    <x-botao-imprimir href="{{ route('fechamento_representante', $representante->id) }}"></x-botao-imprimir>
</div>

<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-header">Conta Corrente</div>
            <div class="card-body"> 
                <p>Peso: @peso($representante->conta_corrente_sum_peso_agregado)g</p>
                <p>Fator: @fator($representante->conta_corrente_sum_fator_agregado)</p>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-header">Devolvidos</div>
            <div class="card-body">
                <p>@moeda($devolvidos->sum('valor_parcela'))</p>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-header">Prorrogações</div>
            <div class="card-body">
                <p>@moeda($representante->parcelas->sum('adiamentos_sum_juros_totais'))</p>
            </div>
        </div>
    </div>
</div>
<p></p>
<form action={{ route('baixarDebitosRepresentantes', $representante->id) }} method="POST">
    @csrf
    <div class="card">
        <div class="card-header">Cheques Prorrogados</div>
        <div class="card-body"> 
            <x-table>
                <thead>
                    <tr>
                        <th>Titular</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th>Nova data</th>
                        <th>Juros</th>
                        <th>Parceiro</th>
                        <th><input type='checkbox' id='selecionarAdiados'></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($representante->parcelas as $cheque)
                    <tr>
                        <td>{{ $cheque->nome_cheque }}</td>
                        <td>@moeda($cheque->valor_parcela)</td>
                        <td>@data($cheque->data_parcela)</td>
                        <td>@data($cheque->adiamentos->nova_data)</td>
                        <td>@moeda($cheque->adiamentos->juros_totais)</td>
                        <td>{{ $cheque->parceiro->pessoa->nome  ?? 'Carteira'}}</td>
                        <td>
                            <input type='checkbox' name='adiamentos[]' value="{{ $cheque->adiamentos->id }}">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan=6>Nenhum cheque devolvido</td>
                    </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>
    </div>

    <p></p>
    <div class="card">
        <div class="card-header">Cheques Devolvidos</div>
        <div class="card-body"> 
            <x-table>
                <thead>
                    <tr>
                        <th>Titular</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th>Parceiro</th>
                        <th><input type='checkbox' id='selecionarDevolvidos'></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($devolvidos as $cheque)
                    <tr>
                        <td>{{ $cheque->nome_cheque }}</td>
                        <td>@moeda($cheque->valor_parcela)</td>
                        <td>@data($cheque->data_parcela)</td>
                        <td>{{ $cheque->parceiro->pessoa->nome  ?? 'Carteira'}}</td>
                        <td><input type='checkbox' name='devolvidos[]' value="{{ $cheque->id }}"></td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan=5>Nenhum cheque devolvido</td>
                        </tr>
                    @endforelse 
                </tbody>
            </x-table>
        </div>
    </div>
    <br>
    <button type='submit' class="btn btn-dark float-right">Baixar pagamentos</button>
</form>
@endsection
@section('script')
<script>
    $("#selecionarAdiados").click( (e) => {
        let status = $(e.target).prop("checked");
        $("input[name='adiamentos[]']").each( (index, element) => {
           $(element).prop("checked", status);
        });
    })
    $("#selecionarDevolvidos").click( (e) => {
        let status = $(e.target).prop("checked");
        $("input[name='devolvidos[]']").each( (index, element) => {
           $(element).prop("checked", status);
        });
    })


</script>
@endsection