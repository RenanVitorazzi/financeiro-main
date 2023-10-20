@extends('layout')
@section('title')
Relatório PIX BRADESCO
@endsection
@section('body')
<style>
    .pointer{
        cursor: pointer;
    }
</style>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('import') }}">Importação</a></li>
        <li class="breadcrumb-item active" aria-current="page">PIX BRADESCO</li>
    </ol>
</nav>
<div class='mb-2 d-flex justify-content-between'>
    <h3> Relatório PIX BRADESCO </h3>
    <h5>Conta: {{ $import->conta->nome }}</h5>
</div>

<x-table class="table-light" id='table-pix'>
    <x-table-header>

        <tr>
            <th>#</th>
            <th>Data</th>
            <th>Descrição</th>
            <th>Débito</th>
            <th>Crédito</th>
            <th>Lançamentos</th>
        </tr>
    </x-table-header>
    <tbody>
        @forelse ($import->arrayDados as $index => $item)
            {{-- @dd($item) --}}
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>@data($item['data'])</td>
                <td>{{$item['nome']}}</td>
                @if ($item['tipo'] == 'Crédito')
                    <td></td>
                    <td>@moeda($item['valor'])</td>
                    <td>
                        @forelse ($item['pagamentosRepresentantes'] as $pr)
                            {{-- @dd($pr) --}}
                            <p>
                                {{-- {{$pr}} --}}
                                @if($pr->comprovante_id == $item['comprovante_id'])
                                    <div class="alert alert-success pointer" >
                                        Pagamento relacionado pelo <b>PIX ID</b>
                                        <br>
                                        <i class='fas fa-check fa-lg mt-2'></i>
                                    </div>
                                @elseif ($item['valor'] == $pr->valor && $item['data'] == $pr->data && $pr->comprovante_id == NULL)
                                    <div class="alert alert-warning" >
                                        Pagamento relacionado pela <b>data</b> e pelo <b>valor</b>
                                        <br>
                                        <span class='btn btn-warning mt-2'>Relacionar por PIX ID</span>
                                        {{-- <i class='fas fa-check fa-lg mt-2'></i> --}}
                                    </div>
                                @endif
                            </p>
                        @empty
                            <span class='btn btn-dark'>
                                <span>Lançar pagamento <i class='fas fa-plus ml-2'></i></span>
                            </span>
                            <span class='btn btn-danger'>
                                <span>Ignorar <i class='fas fa-trash ml-2'></i></span>
                            </span>
                        @endif

                    </td>
                @elseif ($item['tipo'] == 'Débito')
                    <td>@moeda($item['valor'])</td>
                    <td></td>
                    <td>
                        @forelse ($item['pagamentosParceiros'] as $pr)
                            @dd($pr)
                            <p>
                                @if($pr->comprovante_id == $item['comprovante_id'])
                                    <div class="alert alert-success pointer" >
                                        Pagamento relacionado pelo <b>PIX ID</b>
                                        <br>
                                        <i class='fas fa-check fa-lg mt-2'></i>
                                    </div>
                                @elseif ($item['valor'] == $pr->valor && $item['data'] == $pr->data && $pr->comprovante_id == NULL)
                                    <div class="alert alert-warning" >
                                        Pagamento relacionado pela <b>data</b> e pelo <b>valor</b>
                                        <br>
                                        <span class='btn btn-warning mt-2'>Relacionar por PIX ID</span>
                                        {{-- <i class='fas fa-check fa-lg mt-2'></i> --}}
                                    </div>
                                @endif
                            </p>
                        @empty
                            <span class='btn btn-dark'>
                                <span>Lançar pagamento <i class='fas fa-plus ml-2'></i></span>
                            </span>
                            <span class='btn btn-danger'>
                                <span>Ignorar <i class='fas fa-trash ml-2'></i></span>
                            </span>
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan=5>Nenhum registro</td>
            </tr>
        @endforelse
    </tbody>
</x-table>


@endsection
@section('script')
<script>
    $(".botaoIgnorar").each( (index, element) => {
        $(element).click( (botao) => {
            $(botao.currentTarget).parent().parent().fadeOut( "slow" );
            console.log($(botao.currentTarget).parent().parent())
        })
    })

    $(".botaoCriarRegistro").each( (index, element) => {
        $(element).click( (elementoBotao) => {
            let botao = $(elementoBotao.currentTarget)

            console.log($(botao.currentTarget).parent().parent())
        })
    })
</script>
@endsection
