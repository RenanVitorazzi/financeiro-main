<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $representante->pessoa->nome }}  Relatório de Vendas </title>
</head>
<style>
    .marcatexto {
        box-shadow: 15px 0 0 0 #000, -5px 0 0 0 #000;
        background: #000;
        display: inline;
        padding: 3px 0 !important;
        position: relative;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        /* page-break-inside: avoid; */
        page-break-before: no;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    td {
        /* height: 1PX; */
        font-size: 10px;
    }
    th {
        background-color:rgb(216, 216, 216);
        /* color:white; */
    }
    /* tr:nth-child(even) {
        background-color: #a9acb0;
    } */
    h1, h3 {
        margin-top: 0;
        margin-bottom: 0;
        text-align: center;
    }
    .nome {
        font-size: 9px;
        text-align: left;
        padding-left: 3px;
    }
    .fator {
        background-color: #dfdfdf;
    }
    tfoot {
        font-weight: bolder;
    }

    .tabela_pix {
        width: 49%;
        float: right;
    }
    .tabela_dh {
        width: 49%;
        float: left;
    }
    .page_break { 
        page-break-before: always; 
    }
</style>
<body>
    <h3>
        Relatório Vendas - {{ $representante->pessoa->nome }} 
    </h3>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Cliente</th>
                <th colspan=5>Venda</th>
                <th>Total Compra</th>
                <th>Total Pago</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vendas as $venda)
                <tr>
                    <td rowspan=2>@data($venda->data_venda)</td>
                    <td rowspan=2 class='nome'>{{$venda->cliente->pessoa->nome}}</td>
                    <td rowspan=2>{{ $venda->metodo_pagamento }}</td>
                    <td>P</td>
                    <td>@peso($venda->peso)</td>
                    <td>@moeda($venda->cotacao_peso)</td>
                    <td>@moeda($venda->peso * $venda->cotacao_peso)</td>
                    <td rowspan=2>@moeda(($venda->peso * $venda->cotacao_peso) + ($venda->fator * $venda->cotacao_fator))</td>
                    <td rowspan=2>@moeda($cheques->where('venda_id', $venda->id)->sum('valor_parcela'))</td>
                </tr>
                <tr>
                    <td class='fator'>F</td>
                    <td class='fator'>@fator($venda->fator)</td>
                    <td class='fator'>@moeda($venda->cotacao_fator)</td>
                    <td class='fator'>@moeda($venda->fator * $venda->cotacao_fator)</td>
                </tr>
            @empty
                <tr>
                    <td colspan=7>Nenhum registro</td>
                </tr>
            @endforelse
            <tfoot>
                <tr>
                    <td rowspan=2 colspan=3><b>Total</b></td>
                    <td>P</td>
                    <td colspan=3><b>@peso($vendas->sum('peso'))</b></td>
                    <td rowspan=2 ><b>@moeda($vendas->sum('valor_total'))</b></td>
                    <td rowspan=2 ><b>@moeda($cheques->sum('valor_parcela'))</b></td>
                </tr>
                <tr>
                    <td>F</td>
                    <td colspan=3><b>@fator($vendas->sum('fator'))</b></td>
                </tr>
            </tfoot>
        </tbody>
    </table>
   
    <br>

    <table  class='page_break'>
        <thead>

            <tr>
                <th>Porcentagem</th>
                <th>Total de vendas (g)</th>
                <th>Total de comissão (g)</th>
                <th>Média de preço</th>
                <th>Valor comissão</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Peso ({{ $comissaoRepresentante["porcentagem_peso"] }} %)</td>
                <td>@peso($vendas->sum('peso'))</td>
                <td>@peso($vendas->sum('peso') * ($comissaoRepresentante["porcentagem_peso"] / 100))</td>
                <td>@moeda($totalVendaPesoAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('peso'))</td>
                <td>
                    @moeda(($totalVendaPesoAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('peso')) * $vendas->sum('peso') * ($comissaoRepresentante["porcentagem_peso"] / 100))
                </td>
            </tr>
            <tr>
                <td>Fator ({{ $comissaoRepresentante["porcentagem_fator"] }} %)</td>
                <td>@fator($vendas->sum('fator'))</td>
                <td>@peso($vendas->sum('fator') * ($comissaoRepresentante["porcentagem_fator"] / 100))</td>
                <td>@moeda($totalVendaFatorAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('fator'))</td>
                <td>
                    @moeda(($totalVendaFatorAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('fator')) * $vendas->sum('fator') * ($comissaoRepresentante["porcentagem_fator"] / 100))
                </td>
            </tr>
            
            <tr>
                <td colspan=4><b>Total comissão - sem desconto</b></td>
                <td><b>@moeda(
                    ($totalVendaPesoAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('peso')) * $vendas->sum('peso') * ($comissaoRepresentante["porcentagem_peso"] / 100)
                    +
                    ($totalVendaFatorAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('fator')) * $vendas->sum('fator') * ($comissaoRepresentante["porcentagem_fator"] / 100)
                )</b></td>
            </tr>
            <tr>
                <td colspan=4><b>Descontos (Pagamento retirado do pedido)</b></td>
                <td>@moeda($pagamentos->whereNotNull('recebido_representante')->sum('valor_parcela'))</td>
            </tr>
            <tr>
                <td colspan=4><b>Total</b></td>
                <td><b>@moeda(
                    ($totalVendaPesoAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('peso')) * $vendas->sum('peso') * ($comissaoRepresentante["porcentagem_peso"] / 100)
                    +
                    ($totalVendaFatorAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('fator')) * $vendas->sum('fator') * ($comissaoRepresentante["porcentagem_fator"] / 100)
                    -
                    ($pagamentos->whereNotNull('recebido_representante')->sum('valor_parcela') )
                )</b></td>
            </tr>
        </tbody>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th>Forma de pagamento</th>
                <th>Status</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pagamentosPorForma as $keyFormaPagamento => $pagamento)
                @foreach ($pagamento->groupBy('status') as $keyStatus => $teste)
                    <tr>
                        <td>{{ $keyFormaPagamento }}</td>  
                        <td>{{ $keyStatus }}</td>
                        <td>@moeda($teste->sum('valor_parcela'))</td>
                    </tr>  
                @endforeach
            @empty
                <tr>
                    <td colspan=3>Nenhum registro</td>
                </tr>
            @endforelse
            <tfoot>
                <tr>
                    <td colspan=2><b>Total</b></td>
                    <td><b>@moeda($pagamentosTotal)</b></td>
                </tr>
            </tfoot>
        </tbody>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th colspan=4>Descontos</th>
            </tr>
            {{-- <tr>
                <th>Data</th>
                <th>Cliente</th>
                <th>Valor</th>
            </tr> --}}
        </thead>
        <tbody>
            @forelse ($pagamentos->whereNotNull('recebido_representante') as $descontos)
                <tr>
                    <td>@data($descontos->data_parcela)</td>
                    <td>{{$descontos->venda->cliente->pessoa->nome}}</td>
                    <td>{{$descontos->forma_pagamento}}</td>
                    <td>@moeda($descontos->valor_parcela)</td>
                </tr>
            @empty
                <tr>
                    <td colspan=4>Nenhum desconto</td>
                </tr>
            @endforelse
            <tfoot>
                <tr>
                    <td colspan=3>Total</td>
                    <td>@moeda($pagamentos->whereNotNull('recebido_representante')->sum('valor_parcela'))</td>
                </tr>
            </tfoot>
        </tbody> 
    </table>

    <br>

    <table class='tabela_dh'>
        <thead>
            <tr>
                <th colspan=3>Pagamentos em dinheiro</th>
            </tr>
            {{-- <tr>
                <th>Cliente</th>
                <th>Valor</th>
            </tr> --}}
        </thead>
        <tbody>
            @forelse ($pagamentos->where('forma_pagamento', 'like', 'Dinheiro')->sortBy('data_parcela') as $dh)
            <tr>
                <td>@data($dh->data_parcela)</td>
                <td>{{$dh->venda->cliente->pessoa->nome}}</td>
                <td>@moeda($dh->valor_parcela)</td>
            </tr>
            @empty
                <tr>
                    <td colspan=3>Nenhuma venda recebida com dinheiro</td>
                </tr>
            @endforelse
            <tfoot>
                <tr>
                    <td colspan=2>Total</td>
                    <td>@moeda($pagamentos->where('forma_pagamento', 'like', 'Dinheiro')->sum('valor_parcela'))</td>
                </tr>
            </tfoot>
        </tbody>
    </table>
    
    <table class='tabela_pix'>
        <thead>
            <tr>
                <th colspan=3>Pagamentos em pix</th>
            </tr>
            {{-- <tr>
                <th>Data</th>
                <th>Cliente</th>
                <th>Valor</th>
            </tr> --}}
        </thead>
        <tbody>
            @forelse ($pagamentos->where('forma_pagamento', 'like', 'Pix')->sortBy('data_parcela') as $pix)
            <tr>
                <td>@data($pix->data_parcela)</td>
                <td>{{$pix->venda->cliente->pessoa->nome}}</td>
                <td>@moeda($pix->valor_parcela)</td>
            </tr>
            @empty
                <tr>
                    <td colspan=3>Nenhuma venda recebida com pix</td>
                </tr>
            @endforelse
            <tfoot>
                <tr>
                    <td colspan=2>Total</td>
                    <td>@moeda($pagamentos->where('forma_pagamento', 'like', 'Pix')->sum('valor_parcela'))</td>
                </tr>
            </tfoot>
        </tbody>


    </table>

</body>
</html>

