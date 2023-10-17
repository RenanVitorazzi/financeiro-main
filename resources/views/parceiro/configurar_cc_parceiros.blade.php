@extends('layout')
@section('title')
Parceiros
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        @if (auth()->user()->is_admin)
        <li class="breadcrumb-item"><a href="{{ route('parceiros.index') }}">Parceiros</a></li>
        @endif
        <li class="breadcrumb-item active">Extrato {{ $parceiro->pessoa->nome }} </li>
    </ol>
</nav>
<div class='mb-2 d-flex justify-content-between'>
    <h3>Extrato {{$parceiro->pessoa->nome}}</h3>  
    {{-- <div>
        <x-botao-novo href="{{ route('parceiros.create') }}"></x-botao-novo>
    </div> --}}
</div>
<form action="{{ route('atualizar_conta_corrente', $parceiro->id) }}" method="POST">
    @csrf
    @method('POST')
    <x-table id='tabela_parceiro'>
        <x-table-header>
            <tr>
                <th><input type='checkbox' id='selecionar_tudo'></th>
                <th>Data</th>
                <th>Titular</th>
                <th>Status</th>
                <th>Crédito</th>
                <th>Débito</th>
                <th>Saldo</th>
            </tr>
        </x-table-header>
        <tbody>
            @forelse ($saldos as $saldo)
                @php
                    if($saldo->status == 'Crédito') {
                        $tr = 'credito';
                        $saldo_total = $saldo_total + $saldo->valor;
                    } else {
                        $tr = '';
                        $saldo_total -= $saldo->valor;
                    }
                    
                @endphp 
                @if ($saldo->status == 'Crédito')
                    <tr>
                        <td><input type='checkbox' name='{{$saldo->tabela}}[]' value='{{$saldo->id}}'></td>
                        <td>@data($saldo->rank2)</td>
                        <td class='nome'>{{$saldo->nome_cheque}}</td>
                        <td>{{$saldo->status}}</td>
                        <td>@moeda($saldo->valor)</td>     
                        <td></td>      
                        <td>@moeda($saldo_total)</td>
                    </tr>
                @else
                    <tr>
                        <td><input type='checkbox' name='{{$saldo->tabela}}[]' value='{{$saldo->id}}'></td>
                        <td>@data($saldo->rank2)</td>
                        <td class='nome'>{{$saldo->nome_cheque}} - Nº {{$saldo->numero_cheque}}
                        </td>
                        <td>{{$saldo->status}}</td>
                        <td></td>    
                        <td>@moeda($saldo->valor)</td>       
                        <td>@moeda($saldo_total)</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan=6>Nenhum registro</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            @php
                if ($saldo_total < 0) {
                    $tfoot = 'debito';
                } else {
                    $tfoot = 'credito';
                }
            @endphp
            <tr>
                <td colspan=4><b>TOTAL</b></td>
                <td colspan=2 class={{$tfoot}}><b>@moeda($saldo_total)</b></td>
            </tr>
        </tfoot>
    </x-table>

    <button type='submit' class='btn btn-success'>
        Atualizar conta corrente
    </button>
</form>
@endsection
@section('script')
<script>
$( document ).ready(function() {

    $("#selecionar_tudo").click( (e) => {
        
        let status = $(e.currentTarget).is(':checked') ? 'selected' : ''

        $('input[type="checkbox"]:not("#selecionar_tudo")').each(function () {    
            this.checked = status
        });
    })

});
</script>
@endsection