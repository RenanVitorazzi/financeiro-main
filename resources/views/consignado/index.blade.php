@extends('layout')
@section('title')
Consignados
@endsection
@section('body')
<div class='mb-2 d-flex justify-content-between'>
    <h3> Consignados </h3>
    
    <div class="d-flex">
        @if (auth()->user()->is_admin)
        <a href="{{route('pdf_consignados_geral')}}" class="btn btn-dark mr-2" target="_blank">
            Relação representante <i class="fas fa-print"></i>
        </a>
        @elseif(auth()->user()->is_representante)
            <a href="{{route('pdf_consignados', auth()->user()->is_representante)}}" class="btn btn-dark mr-2" target="_blank">
                Relação cliente <i class="fas fa-print"></i>
            </a>
        @endif
       
        
        <x-botao-novo href="{{ route('consignado.create') }}"></x-botao-novo>
    </div>
</div>
<x-table>
    <x-table-header> 
        <tr>
            <th>Cliente</th>
            <th>Representante</th>
            <th>Data</th>
            <th>Peso</th>
            <th>Fator</th>
            <th>Ações</th>
        </tr>
    </x-table-header>
    <tbody>
        @forelse ($consignados as $consignado)
        <tr>
            <td>{{ $consignado->cliente->pessoa->nome }}</td>
            <td>{{ $consignado->representante->pessoa->nome}}</td>
            <td>@data($consignado->data)</td>
            <td>@peso($consignado->peso)</td>
            <td>@fator($consignado->fator)</td>
            <td class='d-flex justify-content-center'>
                @if(auth()->user()->is_admin)
                    <x-botao-editar class="mr-2" href="{{ route('consignado.edit', $consignado) }}"></x-botao-editar>
                    <x-botao-excluir action="{{ route('consignado.destroy', $consignado) }}"></x-botao-excluir>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan='6'>Nenhum registro!</td>
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
    
</script>
@endsection