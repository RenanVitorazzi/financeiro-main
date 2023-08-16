@extends('layout')
@section('title')
Fornecedores
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Fornecedores</li>
        {{-- <li class="breadcrumb-item active" aria-current="page">Cadastro</li> --}}
    </ol>
</nav>
<div class='mb-2 d-flex justify-content-between'>
    <h3> Fornecedores </h3>
    <div>
        <div class="btn btn-danger mr-2" id='btnMostrarInativos'>Mostrar Inativos</div>
        <x-botao-imprimir class="mr-2" href="{{ route('pdf_fornecedores') }}"></x-botao-imprimir>
        <x-botao-novo href="{{ route('fornecedores.create') }}"></x-botao-novo>
    </div>
</div>
<x-table class="table-striped">
    <x-table-header>
        <tr>
            <th>Nome</th>
            <th>Saldo</th>
            <th></th>
        </tr>
    </x-table-header>
    <tbody>
        @forelse ($fornecedores as $fornecedor)
        <tr class="{{ !$fornecedor->inativo ?: 'd-none inativo table-danger' }}">
            <td>
                {{ $fornecedor->pessoa->nome }}
                @if ($fornecedor->inativo)
                    <span class='text-muted'>(Inativo)</span>
                @endif
                @if (!$lancamentos_pendentes->where('fornecedor_id', $fornecedor->id)->isEmpty())
                    <a class="font-weight-bold ml-2 badge badge-pill badge-danger"
                        href="{{ route('conta_corrente.edit', $lancamentos_pendentes->where('fornecedor_id', $fornecedor->id)->first()->id )}}"
                    >
                        Lançamento pendente   
                    </a>
                @endif
            </td>
            <td>@peso($fornecedor->conta_corrente_sum_peso_agregado) g</td>
            <td>
                <a class="btn btn-dark mr-2" title="Conta corrente" href="{{ route('fornecedores.show', $fornecedor->id) }}">
                    <i class="fas fa-chart-area"></i>
                </a>
                <x-botao-editar class="mr-2" href="{{ route('fornecedores.edit', $fornecedor->id) }}"></x-botao-editar>
                <x-botao-excluir action="{{ route('fornecedores.destroy', $fornecedor->id) }}">
                </x-botao-excluir>
            </td>
        </tr>
        @empty
            <tr>
                <td colspan=3>Nenhum registro criado!</td>
        @endforelse
    </tbody>
</x-table>
{{-- <div class='row'>
    <div class="col-12">
        <ul class="d-flex list-group list-group">
            @forelse ($fornecedores as $fornecedor)

                <li class='list-group-item d-flex justify-content-between'>
                    <div class='mt-2'>
                        <span>{{ $fornecedor->pessoa->nome }} </span>
                        <span>(@peso($fornecedor->conta_corrente_sum_peso_agregado)g)</span>
                        @if (!$lancamentos_pendentes->where('fornecedor_id', $fornecedor->id)->isEmpty())
                            <a class="font-weight-bold ml-2 badge badge-pill badge-danger"
                                href="{{ route('conta_corrente.edit', $lancamentos_pendentes->where('fornecedor_id', $fornecedor->id)->first()->id )}}"
                            >
                                Lançamento pendente   
                            </a>
                        @endif
                    </div>
                    <div class='d-flex'>
                        <a class="btn btn-dark mr-2" title="Conta corrente" href="{{ route('fornecedores.show', $fornecedor->id) }}">
                            <i class="fas fa-chart-area"></i>
                        </a>
                        <x-botao-editar class="mr-2" href="{{ route('fornecedores.edit', $fornecedor->id) }}"></x-botao-editar>
                        <x-botao-excluir action="{{ route('fornecedores.destroy', $fornecedor->id) }}">
                        </x-botao-excluir>
                    </div>
                </li>
            @empty
                <li class='list-group-item list-group-item-danger'>Nenhum registro criado!</li>
            @endforelse
        </ul>
    </div>
 

</div> --}}


@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
