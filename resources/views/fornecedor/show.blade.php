@extends('layout')
@section('title')
Conta Corrente - {{ $fornecedor->pessoa->nome }}
@endsection
@section('body')
<style>
    .borda-vermelha {
        border: 0.7mm solid #dc3545;
        border-collapse: collapse;
    }
</style>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('fornecedores.index') }}">Fornecedores</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cadastro</li>
    </ol>
</nav>
<div class="d-flex justify-content-between">
    <h3>{{ $fornecedor->pessoa->nome }}</h3>
    <div>
        <x-botao-imprimir class="mr-2" href="{{ route('pdf_fornecedor', ['id' => $fornecedor->id, 'data_inicio' => $data_inicio]) }}"></x-botao-imprimir>
        <x-botao-novo href="{{ route('conta_corrente.create', ['fornecedor_id' => $fornecedor->id]) }}">
        </x-botao-novo>
    </div>
</div>
@if (count($registrosContaCorrente) > 0)
    <h3 class="{{ $registrosContaCorrente[0]->saldo > 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
        @peso($registrosContaCorrente->last()->saldo)g
    </h3>
@endif
<x-table>
    <x-table-header>
        <tr>
            <th>Data</th>
            <th>Quantidade (Gramas)</th>
            <th>Balanço</th>
            <th>Observação</th>
            <th>Saldo</th>
            <th>Ações</th>
        </tr>
    </x-table-header>
    <tbody>
        @forelse ($registrosContaCorrente as $contaCorrente)
            @if ($ultimaConferencia && $contaCorrente->id == $ultimaConferencia->id && $filtrarConferencia !== true)
                @php
                    $filtrarConferencia = true
                @endphp
            @endif
            @if ($filtrarConferencia)
                <tr
                    @if ($ultimaConferencia && $contaCorrente->id == $ultimaConferencia->id && $filtrarConferencia)
                        class="table-success"
                    @elseif(!$contaCorrente->peso_agregado)
                        class="borda-vermelha table-danger"
                    @endif
                    
                >
                    <td>@data($contaCorrente->data)</td>
                    <td>@peso($contaCorrente->peso)</td>
                    <td class="{{ $contaCorrente->balanco == 'Crédito' ? 'text-success' : 'text-danger' }}">
                        <b>{{ $contaCorrente->balanco }}</b>
                        <i class="fas {{ $contaCorrente->balanco == 'Crédito' ? 'fa-angle-up' : 'fa-angle-down' }}"></i>
                    </td>
                    <td>{{ $contaCorrente->observacao }}
                        <b>{{$contaCorrente->conferido ? 'CONFERIDO: '. $contaCorrente->conferido->format('d/m/Y') : ''}}</b>
                    </td>
                    <td class="{{ $contaCorrente->balanco > 0 ? 'text-success' : 'text-danger' }}">@peso($contaCorrente->saldo)</td>
                    <td>
                        <a class="btn btn-dark mr-2" href="{{ route('conta_corrente_anexo.index', ['id' => $contaCorrente->id]) }}" title="Anexos">
                            <i class="fas fa-file-image"></i>
                        </a>
                        <x-botao-editar class="mr-2" href="{{ route('conta_corrente.edit', $contaCorrente->id) }}"></x-botao-editar>
                        <x-botao-excluir action="{{ route('conta_corrente.destroy', $contaCorrente->id) }}"></x-botao-excluir>
                    </td>
                </tr>
            @endif
        @empty
            <tr class="table-danger">
                <td colspan=6>Nenhum registro</td>
            </tr>
        @endforelse
    </tbody>
</x-table>
@endsection
