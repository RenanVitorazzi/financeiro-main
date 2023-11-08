@extends('layout')

@section('title')
Representantes
@endsection

@section('body')
<div class='mb-2 d-flex justify-content-between'>
    <h3>Representantes</h3>
    <div class="d-flex">
        <div class="btn btn-danger mr-2" id='btnMostrarInativos'>Mostrar Inativos</div>
        <x-botao-imprimir class="mr-2" href="{{ route('relacao_ccr') }}"></x-botao-imprimir>
        <x-botao-novo href="{{ route('representantes.create') }}"></x-botao-novo>
    </div>
</div>

<x-table class="table-striped">
    <x-table-header>
        <tr>
            <th>Nome</th>
            <th>Peso</th>
            <th>Fator</th>
            {{-- <th>Cheques Devolvidos</th> --}}
            <th></th>
        </tr>
    </x-table-header>
    <tbody>
        @forelse ($representantes as $representante)
        <tr class="{{ !$representante->inativo ?: 'd-none inativo table-danger' }}">
            <td>
                {{ $representante->pessoa->nome }}
                @if ($representante->inativo)
                    <span class='text-muted'>(Inativo)</span>
                @endif
            </td>
            <td>@peso($representante->conta_corrente_sum_peso_agregado)</td>
            <td>@fator($representante->conta_corrente_sum_fator_agregado)</td>
            <td>
                @if (!$representante->inativo)    
                    @if (!$representante->atacado)
                        <a class="btn btn-dark"
                            title="Acerto de documentos"
                            target='_blank'
                            href="{{ route('acertosRepresentante', $representante->id) }}">
                            Acertos
                        </a>
                    @endif
                    <a class="btn btn-dark"
                        title="Cheques Devolvidos "
                        target='_blank'
                        href="{{ route('pdf_cc_representante', $representante->id) }}">
                        Cheques Devolvidos
                    </a>
                    <a class="btn btn-dark"
                        title="Conta Corrente"
                        target='_blank'
                        href="{{ route('conta_corrente_representante.show', $representante->id) }}">
                        Conta Corrente
                    </a>
                    @if (!$representante->atacado)
                        <a class="btn btn-dark"
                            title="Vendas"
                            target='_blank'
                            href="{{ route('venda.show', $representante->id) }}">
                            Vendas
                        </a>
                    @endif
                @endif

                @if (!$representante->atacado)
                <a class="btn btn-dark" title="Dashboard" href="{{ route('representanteDashboard', $representante) }}">
                    <i class="fas fa-eye"></i>
                </a>
                @else
                <a class="btn btn-dark" title="Detalhes" href="{{ route('representantes.show', $representante->id) }}">
                    <i class="fas fa-eye"></i>
                </a>
                @endif
                {{--
                <a class="btn btn-dark" title="Imprimir devolvidos" target="_blank" href="{{ route('cheques_devolvidos', $representante->id) }}">
                    <i class="fas fa-print"></i>
                </a>

                --}}
                <x-botao-editar href="{{ route('representantes.edit', $representante->id) }}"></x-botao-editar>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan=4>Nenhum registro criado!</td>
        @endforelse
    </tbody>
</x-table>

@endsection
@section('script')
<script>
@if(Session::has('message'))
    toastr["success"]("{{ Session::get('message') }}")
@endif
$("#btnMostrarInativos").click( (e) => {
    $(e.currentTarget).toggleClass('btn-danger')
        .toggleClass('btn-dark')

    $(".inativo").toggleClass('d-none')
})
</script>
@endsection
