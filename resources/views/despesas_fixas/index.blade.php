@extends('layout')
@section('title')
Contas
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cadastros_auxiliares') }}">Cadastros Auxiliares</a></li>
        <li class="breadcrumb-item active" aria-current="page">Despesas Fixas</li>
    </ol>
</nav>

<div class='mb-2 d-flex justify-content-between'>
    <h3> Despesas Fixas </h3>
    <x-botao-novo href="{{ route('despesas_fixas.create') }}"></x-botao-novo>
</div>

<x-table id="myTable">
    <x-table-header>
        <tr>
            <th>Nome</th>
            <th>Data da quitação</th>
            <th>Dia do vencimento</th>
            <th>Valor</th>
            <th>Local</th>
            <th>Ações</th>
        </tr>
    </x-table-header>
    <tbody>
        @forelse ($despesas_fixas as $despesa_fixa)
        <tr>
            <td>{{ $despesa_fixa->nome }}</td>
            <td>{{ $despesa_fixa->data_quitacao ? \Carbon\Carbon::parse($despesa_fixa->data_quitacao)->format('d/m/Y') : '-' }}</td>
            <td>{{ $despesa_fixa->dia_vencimento }}</td>
            <td>@moeda($despesa_fixa->valor)</td>
            <td>{{ $despesa_fixa->local->nome }}</td>
            <td><x-botao-editar href="{{ route('despesas_fixas.edit', $despesa_fixa) }}"></x-botao-editar></td>
        </tr>
        @empty
        <tr>
            <td colspan=6>Nenhum registro!</td>
        </tr>
        @endforelse
    </tbody>
</x-table>
@endsection
@section('script')
<script>
    @if(Session::has('message'))
        toastr["success"]("{{ Session::get('message') }}")
    @endif

    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script>
@endsection
