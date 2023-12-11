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
    /*
    .page_break { 
        page-break-before: always; 
    }
    */
</style>
<body>
    <h3>
        CONFERÊNCIA PARCELAS - {{ $representante->pessoa->nome }} 
    </h3>

    @foreach ($vendas as $venda)
    <table >
        <thead>
            <tr>
                <th colspan=7>{{$venda->cliente->pessoa->nome}}</th>
            </tr>
            <tr>
                <th>Data</th>
                <th>Titular</th>
                <th>Forma pgto</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Rec Rep</th>
                <th>Pagamentos Rep</th>
            </tr>
        </thead>
        @foreach ($parcelas->where('venda_id', $venda->id) as $parcela)
            <tbody>
                <tr>
                    <td>@data($parcela->data_parcela)</td>
                    <td>{{$parcela->nome_cheque}}</td>
                    <td>{{$parcela->forma_pagamento}}</td>
                    <td>@moeda($parcela->valor_parcela)</td>
                    <td>{{$parcela->status}}</td>

                    <td>{{$parcela->recebido_representante ? 'X' : ''}}</td>
                    <td>
                        @if($parcela->status != 'Aguardando')
                            @foreach ($parcela->pagamentos_representantes as $pgto)
                                <p> @data($pgto->data) - @moeda($pgto->valor) ({{$pgto->conta->nome}})</p>
                            @endforeach
                            TOTAL PAGO: @moeda($parcela->pagamentos_representantes->sum('valor'))
                        @endif
                    </td>
                </tr>
            </tbody>
        @endforeach
    </table>
    <br>
    @endforeach

</body>
</html>

