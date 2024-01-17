@extends('layout')
@section('title')
Contas
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cadastros_auxiliares') }}">Cadastros Auxiliares</a></li>
        <li class="breadcrumb-item active" aria-current="page">Conta</li>
    </ol>
</nav>

<div class='mb-2 d-flex justify-content-between'>
    <h3> Contas </h3>
    <x-botao-novo href="{{ route('contas.create') }}"></x-botao-novo>
</div>

<x-table id="myTable">
    <x-table-header>
        <tr>
            <th>Nome</th>
            <th>Pix</th>
            <th>Número do banco</th>
            <th>Agência</th>
            <th>Conta</th>
            <th>Conta Corrente</th>
            <th>Inativa</th>
            <th>Ações</th>
        </tr>
    </x-table-header>
    <tbody>
        @forelse ($contas as $conta)
        <tr>
            <td>{{ $conta->nome }}</td>
            <td>{{ $conta->pix }}</td>
            <td>{{ $conta->numero_banco }}</td>
            <td>{{ $conta->agencia }}</td>
            <td>{{ $conta->conta }}</td>
            <td>{{ $conta->conta_corrente }}</td>
            <td>{{ $conta->inativo ? 'Inativo' : 'Ativo' }}</td>
            <td><x-botao-editar href="{{ route('contas.edit', $conta) }}"></x-botao-editar></td>
        </tr>
        @empty
        <tr>
            <td colspan=8>Nenhum registro!</td>
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
