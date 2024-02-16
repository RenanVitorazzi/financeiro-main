@extends('layout')

@section('title')
Dashboard {{ $pessoa->nome }}
@endsection

@section('body')
<style>
    .card_dash>.card:hover {
        border-color:#007bff;
        background-color:#dfeefd;
        box-shadow: 2.5px 4px #888888;
    }
</style>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        @if (auth()->user()->is_admin)
            <li class="breadcrumb-item"><a href="{{ route('representantes.index') }}">Representantes</a></li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">Dashboard {{ $pessoa->nome }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-sm-6 mb-4 col-md-6 card_dash" data-tipo='CONTA_CORRENTE'>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Conta corrente</h5>
                <p class="card-text">
                    <div>Peso: @peso($contaCorrente->sum('peso_agregado'))</div>
                    <div>Fator: @fator($contaCorrente->sum('fator_agregado'))</div>
                </p>
                @if (auth()->user()->is_admin)
                    <a href="{{ route('conta_corrente_representante.show', $representante->id) }}" class="btn btn-primary">Conta Corrente</a>
                @endif
                <a href="{{ route('impresso_ccr', $representante->id) }}" class="btn btn-primary">Impresso</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 mb-4 col-md-6 card_dash" >
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Cheques Devolvidos</h5>
                <p class="card-text">
                    <div>Conta corrente: <b class='text-danger'>@moeda($saldoContaCorrenteChsDevolvidos) </b> </div>
                    <div>Na empresa ({{ $devolvidosNoEscritorio->count() }}): @moeda($devolvidosNoEscritorio->sum('valor_parcela')) </div>
                    <div>Nos parceiros ({{ $devolvidosComParceiros->count() }}): @moeda($devolvidosComParceiros->sum('valor_parcela'))</div>
                </p>
                <a href="{{route('pdf_cc_representante', $representante->id) }}" class="btn btn-primary">Impresso do conta corrente</a>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 mb-4 col-md-6 card_dash"  data-tipo='ACERTOS'>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Acertos</h5>
                <p class="card-text">
                    <div>Cheques a serem enviados: @moeda($acertos->where('forma_pagamento', 'LIKE', 'Cheque')->sum('valor_parcela'))</div>
                    <div>OP em aberto: @moeda($acertos->where('forma_pagamento', 'LIKE', 'Transferência Bancária')->sum('valor_parcela'))</div>
                    <div>Total: @moeda($acertos->sum('valor_parcela'))</div>
                </p>
                <a href="{{route('pdf_acerto_documento', $representante->id) }}" class="btn btn-primary">Relatório de Acertos</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 mb-4 col-md-6 card_dash"  data-tipo='ACERTOS_VENCIDOS'>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Acertos vencidos</h5>
                <p class="card-text">
                    <div>Cheques a serem enviados: @moeda($acertos->where('forma_pagamento', 'LIKE', 'Cheque')->where('data_parcela', '<', \Carbon\Carbon::now())->sum('valor_parcela'))</div>
                    <div>OP em aberto: @moeda($acertos->where('forma_pagamento', 'LIKE', 'Transferência Bancária')->where('data_parcela', '<', \Carbon\Carbon::now())->sum('valor_parcela'))</div>
                    <div>Total: @moeda($acertos->where('data_parcela', '<', \Carbon\Carbon::now())->sum('valor_parcela'))</div>
                </p>
                <a href="{{route('pdf_acerto_documento', $representante->id) }}" class="btn btn-primary">Relatório de Acertos</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 mb-4 col-md-6 card_dash"  data-tipo='CONSIGNADOS'>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Consignados</h5>
                <p class="card-text">
                    <div>Peso: @peso($consignados->sum('peso'))</div>
                    <div>Fator: @fator($consignados->sum('fator'))</div>
                </p>
                @if (auth()->user()->is_admin)
                    <a href="{{route('consignado.index') }}" class="btn btn-primary">Consignados</a>
                @endif
                <a href="{{route('pdf_consignados', $representante->id) }}" class="btn btn-primary">Impresso</a>
            </div>
        </div>
    </div>
    
    @if (auth()->user()->is_admin)
    <div class="col-sm-6 mb-4 col-md-6 card_dash"  data-tipo='ULTIMO_RELATORIO'>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Último relatório de vendas</h5>
                <p class="card-text">
                    <div>Peso: @peso($ultimoRelatorioVendas->sum('peso'))</div>
                    <div>Fator: @fator($ultimoRelatorioVendas->sum('fator'))</div>
                    <div>Total: @moeda($ultimoRelatorioVendas->sum('valor_total'))</div>
                </p>
                <a href="{{route('pdf_relatorio_vendas', $ultimoRelatorioVendas->first()->enviado_conta_corrente) }}" class="btn btn-primary">Relatório de Vendas</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
@section('script')
<script>
    // const CONTA_CORRENTE = @json($contaCorrente);
    // const CONSIGNADOS = @json($consignados);
    // const ACERTOS = @json($acertos);
    // const ACERTOS_ATRASADOS = @json($acertos->where('data_parcela', '<', \Carbon\Carbon::now()));
    // const ULTIMO_RELATORIO = @json($ultimoRelatorioVendas);

    // let arraySuprema = []
    // arraySuprema['CONTA_CORRENTE'] = CONTA_CORRENTE
    // arraySuprema['CONSIGNADOS'] = CONSIGNADOS
    // arraySuprema['ACERTOS'] = ACERTOS
    // arraySuprema['ACERTOS_ATRASADOS'] = ACERTOS_ATRASADOS
    // arraySuprema['ULTIMO_RELATORIO'] = ULTIMO_RELATORIO
    // // console.log(arraySuprema['CONTA_CORRENTE'])
    // const MODAL = $("#modal2")
    // const MODAL_HEADER = $("#modal-title2")
    // const MODAL_BODY = $("#modal-body2")
    // const MODAL_FOOTER = $("#modal-footer2")

    // $(".card_dash").click( (e) => {
    //     mostrarDetalhes($(e.currentTarget).data('tipo'))
    // })

    // function mostrarDetalhes(tipo) {
    //     console.log(tipo)
    //     MODAL.modal('show')
    //     MODAL_HEADER.text(tipo)
    //     let html = criarHtml(tipo)
    // }

    // function criarHtml(tipo) {
        
    //     console.log(tipo)
    //     arraySuprema[tipo].forEach(element => {
    //         console.log(element)
    //     }); 
    
    // }
</script>
@endsection