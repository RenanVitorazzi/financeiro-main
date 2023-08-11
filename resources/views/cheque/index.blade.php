@extends('layout')
@section('title')
Carteira de cheques
@endsection
@section('body')

<div class='mb-2 d-flex justify-content-between'>
    <h3> Carteira de Cheques </h3>
    @if (auth()->user()->is_admin)
    <div>
        <x-botao-imprimir class="mr-2" href="{{ route('carteira_cheque_total') }}"></x-botao-imprimir>
        <x-botao-novo href="{{ route('cheques.create') }}"></x-botao-novo>
    </div>
    @endif
</div>
       
<x-table id="tabelaBalanco">
    <x-table-header>
        <tr>
            <th>Data</th>
            <th>Titular</th>
            <th>Representante</th>
            <th>Valor</th>
            <th>Detalhes</th>
            <th>Ações</th>
        </tr>
    </x-table-header>
    <tbody>
        @forelse ($cheques as $cheque)
            <tr class="{{ (($cheque->adiamentos->nova_data ?? $cheque->data_parcela) < Carbon\Carbon::now()) ? 'table-danger' : '' }}">
                <td>@data($cheque->adiamentos->nova_data ?? $cheque->data_parcela)</td>
                <td>{{ $cheque->nome_cheque }}</td>
                <td>{{ $cheque->representante->pessoa->nome ?? '' }}</td>
                <td>@moeda($cheque->valor_parcela)</td>
                <td>{{ $cheque->numero_cheque }} {{ $cheque->observacao}}</td>
                <td>
                    <x-botao-editar href="{{ route('cheques.edit', $cheque->id) }}"></x-botao-editar>
                </td>
            </tr>
        @empty
        <tr>
            <td colspan=7>Nenhum registro</td>
        </tr>
        @endforelse
    </tbody>
</x-table>

@endsection
@section('script')
<script>
    $(document).ready( function () {
        $('#tabelaBalanco').dataTable( {
            "ordering": false
        } );
    } );
</script>
@endsection