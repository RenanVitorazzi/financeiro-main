<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HISTÓRICO {{$cliente->pessoa->nome}}</title>
</head>
<style>
    table {
        width:100%;
        border-collapse: collapse;
        font-size:12px;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    /* tr:nth-child(even) {
        background-color: #d9dde2;
    } */
    h1 {
        text-align: center;
    }
    .nome {
        font-size:10px;
    }
    .fator {
        background-color: #d9dde2;
    }
</style>
<body>
    <table>
        <thead>
            <tr>
                <th colspan = 8>HISTÓRICO {{$cliente->pessoa->nome}}</th>
            </tr>
            <tr>
                <td>DATA</td>
                <td colspan=5>COMPRA</td>
                <td>VALOR</td>
                <td>PRAZO MÉDIO</td>
            </tr>
        </thead>
        <tbody>
            @forelse ($compras as $compra)
                
            @foreach ($parcelas->where('venda_id', $compra->id) as $parcela)
                @php
                    $dias = \Carbon\Carbon::parse($parcela->data_parcela)->diffInDays($compra->data_venda);
                    $totalPrazo += $dias;
                @endphp                                
            @endforeach
                
            @if (!$consignados->where('venda_id', $compra->id)->isEmpty())
                    <tr>
                        <td rowspan=2>(@data($consignados->where('venda_id', $compra->id)->first()->data)) @data($compra->data_venda)</td>
                        <td>P</td>
                        <td>(@peso($consignados->where('venda_id', $compra->id)->first()->peso)) @peso($compra->peso)</td>
                        <td>@moeda($compra->cotacao_peso)</td>
                        <td>@moeda($compra->peso * $compra->cotacao_peso)</td>
                        <td rowspan=2>@moeda(($compra->peso * $compra->cotacao_peso) + ($compra->fator * $compra->cotacao_fator) )</td>
                        <td rowspan=2>@moeda($parcelas->where('venda_id', $compra->id)->sum('valor_parcela'))</td>
                        <td rowspan=2>{{number_format($totalPrazo / $parcelas->where('venda_id', $compra->id)->count(), 2)}}</td>
                    </tr>
                    <tr>
                        <td class='fator'>F</td>
                        <td class='fator'>(@fator($consignados->where('venda_id', $compra->id)->first()->fator)) @fator($compra->fator)</td>
                        <td class='fator'>@moeda($compra->cotacao_fator)</td>
                        <td class='fator'>@moeda($compra->fator * $compra->cotacao_fator)</td>
                    </tr>
                @else
                    <tr>
                        <td rowspan=2>@data($compra->data_venda)</td>
                        <td>P</td>
                        <td>@peso($compra->peso)</td>
                        <td>@moeda($compra->cotacao_peso)</td>
                        <td>@moeda($compra->peso * $compra->cotacao_peso)</td>
                        <td rowspan=2>@moeda(($compra->peso * $compra->cotacao_peso) + ($compra->fator * $compra->cotacao_fator) )</td>   
                        <td rowspan=2> @moeda($parcelas->where('venda_id', $compra->id)->sum('valor_parcela'))</td>
                        <td rowspan=2>{{number_format($totalPrazo / $parcelas->where('venda_id', $compra->id)->count(), 2)}}</td>
                        @php
                            $totalPrazo = 0;
                        @endphp
                    </tr>
                    <tr>
                        <td class='fator'>F</td>
                        <td class='fator'>@fator($compra->fator)</td>
                        <td class='fator'>@moeda($compra->cotacao_fator)</td>
                        <td class='fator'>@moeda($compra->fator * $compra->cotacao_fator)</td>
                    </tr>
                @endif
                
            @empty
                <tr>
                    <td colspan=7>Nenhum registro</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan=6>Total</td>
                <td>@moeda($parcelas->sum('valor_parcela'))</td>
                <td></td>
            </tr>
        </tfoot>
    </table>


</body>
</html>

