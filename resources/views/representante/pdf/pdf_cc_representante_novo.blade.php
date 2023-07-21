<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Extrato {{$representante->pessoa->nome}}</title>
</head>
<style>
    table {
        width:100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    th {
        background-color:black;
        color:white;
    }
    /* tr:nth-child(even) {
        background-color: #a9acb0;
    } */
    h3 {
        text-align: center;
    }
    .titular {
        font-size: 10px;
        text-align: left;
    }
    .credito {
        background-color: rgb(173, 255, 173);
    }
</style>
<body>
    <h3>EXTRATO {{$representante->pessoa->nome}} - @data($hoje)</h3>
    <table>
        <thead>
            <tr>
                <th>DATA</th>
                <th>DESCRIÇÃO</th>
                <th>DÉBITO</th>
                <th>CRÉDITO</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>@data($infoRepresentante[$representante->id]['Data'])</td>
                <td colspan=3>SALDO ANTERIOR</td>
                <td>@moeda($saldo_total)</td>
            </tr>
            @forelse ($saldos as $saldo)
                @if ($saldo->balanco == 'Débito')
                    <tr>
                        <td>@data($saldo->data_entrega)</td>
                        <td>
                            <a target='_blank' href='{{ route('pdf_cheques_entregues', ['representante_id' => $representante->id, 'data_entrega' => $saldo->data_entrega]) }}'>
                                {{ $saldo->descricao }} - @data($saldo->data_entrega)
                            </a>
                        </td>
                        <td>@moeda(-$saldo->valor_total_debito)</td>
                        <td></td>
                        @php
                            $saldo_total -= $saldo->valor_total_debito;
                        @endphp
                        <td>@moeda($saldo_total)</td>
                    </tr>
                @elseif($saldo->balanco == 'Crédito' && $saldo->valor_total_debito < 0)
                    <tr>
                        <td>@data($saldo->data_entrega)</td>
                        <td>{{ $saldo->descricao }}</td>
                        <td>@moeda($saldo->valor_total_debito)</td>
                        <td></td>
                        @php
                            $saldo_total += $saldo->valor_total_debito;
                        @endphp
                        <td>@moeda($saldo_total)</td>
                    </tr>
                @else
                    <tr>
                        <td>@data($saldo->data_entrega)</td>
                        <td>{{ $saldo->descricao }}</td>
                        <td></td>
                        <td>@moeda($saldo->valor_total_debito)</td>
                        @php
                            $saldo_total += $saldo->valor_total_debito;
                        @endphp
                        <td>@moeda($saldo_total)</td>
                    </tr>
                @endif

            @empty
                <tr>
                    <td colspan=5>NENHUM REGISTRO</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            @php
            if ($saldo_total < 0) {
                $tfoot = 'debito';
            } else {
                $tfoot = 'credito';
            }
            @endphp
            <tr>
                <td colspan=3><b>TOTAL</b></td>
                <td colspan=2><b>@moeda($saldo_total)</b></td>
            </tr>
        </tfoot>
    </table>
    <br>
    <table>
        <tr>
            <td></td>
            <td>QUANTIDADE</td>
            <td>TOTAL DEVOLVIDO</td>
            <td>TOTAL EM ABERTO</td>
        </tr>
        <tr>
            <td>
                <a  target='_blank' href='{{ route('pdf_cheques_devolvidos_escritorio', ['representante_id' => $representante->id]) }}'> 
                    CHEQUES NA EMPRESA 
                </a>
            </td>
            <td>{{ $chequesNaoEntregues->count() }}</td>
            <td>@moeda($chequesNaoEntregues->sum('valor_parcela'))</td>
            {{-- <td>Total Pago: @moeda($chequesNaoEntregues->sum('pagamentos_representantes_sum_valor'))</td> --}}
            <td>@moeda($chequesNaoEntregues->sum('valor_parcela') - $chequesNaoEntregues->sum('pagamentos_representantes_sum_valor'))</td>
        </tr>
        <tr>
            <td>
                <a  target='_blank' href='{{ route('pdf_cheques_devolvidos_parceiros', ['representante_id' => $representante->id]) }}'> 
                    CHEQUES COM PARCEIROS 
                </a>            
            </td>
            <td>{{ $chequesComParceiros->count() }}</td>
            {{-- <td>@moeda($chequesComParceiros->sum('valor_parcela'))</td> --}}
            <td>@moeda($chequesComParceiros->sum('valor_parcela'))</td>
            {{-- <td>Total Pago: @moeda($chequesComParceiros->sum('pagamentos_parceiros_sum_valor'))</td> --}}
            <td>@moeda(($chequesComParceiros->sum('valor_parcela') - $chequesComParceiros->sum('pagamentos_representantes_sum_valor')))</td>

        </tr>
        <tr>
            <td>TOTAL GERAL</td>
            <td>{{ $chequesNaoEntregues->count() + $chequesComParceiros->count()}}</td>
            <td colspan=2> @moeda(
                ($chequesNaoEntregues->sum('valor_parcela') - $chequesNaoEntregues->sum('pagamentos_representantes_sum_valor') ) +
                ($chequesComParceiros->sum('valor_parcela') - $chequesComParceiros->sum('pagamentos_representantes_sum_valor'))
            )</td>
        </tr>
    </table>
</body>
</html>

