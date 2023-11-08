@extends('layout')

@section('title')
Vendas - {{$representante->pessoa->nome}} 
@endsection

@section('body')
<style>
    
</style>
<meta name="csrf-token" content="{{ csrf_token() }}" />

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        @if (!auth()->user()->is_representante)
            <li class="breadcrumb-item"><a href="{{ route('representantes.index') }}">Representantes</a></li>    
        @endif
        <li class="breadcrumb-item active" aria-current="page">Acertos</li>
    </ol>
</nav>
<div class='mb-2 d-flex justify-content-between'>
    <h3>Acertos - {{ $representante->pessoa->nome}}</h3> 
    <div> 
        <x-botao-imprimir class="mr-2" href="{{ route('pdf_acerto_documento', ['representante_id' => $representante->id]) }}"></x-botao-imprimir>
        {{-- <x-botao-novo href="{{ route('venda.create', ['id' => $representante->id]) }}"></x-botao-novo> --}}
    </div>
</div>
<div class="row ">
@foreach ($acertos as $acerto)
<div class='col-6'>
    <x-table>
        <x-table-header>
            <tr>
                <th colspan=7>{{$acerto->cliente}}</th>
            </tr>
            <tr>
                <th class="vencimento">Vencimento</th>
                <th>Status</th>
                <th>Forma</th>
                <th>Valor</th>
                <th>Pagamentos</th>
                <th>Total Aberto</th>
                <th></th>
            </tr>
        </x-table-header>
        <tbody>
        @php
            $sql = DB::select( "SELECT
                    p.data_parcela as vencimento,
                    p.valor_parcela AS valor,
                    p.status,
                    p.forma_pagamento,
                    (SELECT nome from pessoas WHERE id = r.pessoa_id) as representante,
                    SUM(pr.valor) AS valor_pago,
                    p.id as parcela_id
                FROM
                    vendas v
                        INNER JOIN
                    parcelas p ON p.venda_id = v.id
                        LEFT JOIN clientes c ON c.id = v.cliente_id
                        LEFT JOIN representantes r ON r.id = v.representante_id
                        LEFT JOIN pagamentos_representantes pr ON pr.parcela_id = p.id
                WHERE
                    p.deleted_at IS NULL
                    AND v.deleted_at IS NULL
                    AND r.id = ?
                    AND (
                    p.forma_pagamento like 'Cheque' AND p.status like 'Aguardando Envio'
                    OR
                    p.forma_pagamento != 'Cheque' AND p.status != 'Pago'
                    )
                    AND pr.deleted_at IS NULL
                    AND pr.baixado IS NULL
                    AND c.id = ?
                GROUP BY p.id
                ORDER BY c.pessoa_id, data_parcela , valor_parcela",
                [$representante_id, $acerto->cliente_id]
            );

            $cliente_valor = 0;
            $cliente_valor_pago = 0;
        @endphp
            @foreach ($sql as $divida)
                @php
                    $pgtos = DB::select( "SELECT c.nome as conta_nome, pr.*
                        FROM pagamentos_representantes pr
                        INNER JOIN contas c ON c.id=pr.conta_id
                        WHERE pr.parcela_id = ?
                            AND pr.baixado IS NULL
                            AND pr.deleted_at IS NULL",
                        [$divida->parcela_id]
                    );
                    $cliente_valor += $divida->valor;
                    $cliente_valor_pago += $divida->valor_pago;

                    $total_divida_valor += $divida->valor;
                    $total_divida_valor_pago += $divida->valor_pago;
                @endphp
                <tr class="{{ $divida->vencimento < $hoje ? 'table-danger' : ''}}">
                    <td>@data($divida->vencimento)</td>
                    <td class='status'>{{$divida->status}}</td>
                    <td>{{$divida->forma_pagamento == 'Transferência Bancária' ? 'Op' : $divida->forma_pagamento}}</td>
                    <td>@moeda($divida->valor)</td>
                    <td class='pagamentos'>
                        @foreach ($pgtos as $pgto)
                            <div class="linha-pagamento">
                                @data($pgto->data) - @moeda($pgto->valor) ({{$pgto->conta_nome}})
                                <b>{{$pgto->confirmado ? '' : 'PAGAMENTO NÃO CONFIRMADO'}}</b>
                            </div>
                        @endforeach
                    </td>
                    <td>@moeda($divida->valor - $divida->valor_pago)</td>
                    <td>
                        @if($divida->forma_pagamento == 'Transferência Bancária')
                            <a class="btn btn-dark" target='_blank' href="{{route('criarRecebimentoPeloAcerto', $divida->parcela_id)}}">
                                <i class="fas fa-plus" ></i>
                            </a>
                        @elseif($divida->status == 'Aguardando Envio')
                            <a class="btn btn-dark" target='_blank' href="{{route('cheques.edit', $divida->parcela_id)}}">
                                <i class="fas fa-pencil-alt" ></i>
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan=3><b>TOTAL</b></td>
                <td>@moeda($cliente_valor)</td>
                <td>@moeda($cliente_valor_pago)</td>
                <td><b>@moeda($cliente_valor - $cliente_valor_pago)</b></td>
            </tr>
        </tfoot>
    </x-table>
</div>
@endforeach
</div>
{{-- 
    <x-table>
        <x-table-header>
            <tr><th colspan=3>RESUMO</th></tr>
            <tr>
                <th>VALOR</th>
                <th>VALOR RECEBIDO</th>
                <th>VALOR EM ABERTO</th>
            </tr>
        </x-table-header>
        <tbody>
            <tr>
                <td>@moeda($total_divida_valor)</td>
                <td>@moeda($total_divida_valor_pago)</td>
                <td><b>@moeda($total_divida_valor - $total_divida_valor_pago)</b></td>
            </tr>
        </tbody>
    </x-table> 
--}}

@endsection
@section('script')
<script>
   
</script>
@endsection