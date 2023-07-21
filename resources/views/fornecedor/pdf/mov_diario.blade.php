<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Movimentação diária @data($hoje)</title>
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
    /* tr:nth-child(even) {    
        background-color: #d9dde2;
    } */
    th {
        background-color: #d9dde2;
    }
    h3 {
        margin: 0 0 0 0;
        text-align: center;
    }
    .titular {
        font-size:9px;
    }
</style>
<body>
    <h3>Movimentação diária - @data($hoje)</h3>
    @if(count($cc_representante) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan = 5>Representantes</th>
                </tr>
                <tr>
                    <th>Representante</th>
                    <th>Balanço</th>
                    <th>Peso</th>
                    <th>Fator</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cc_representante as $representante)
                    <tr>
                        <td>{{$representante->nome}}</td>
                        <td>{{$representante->balanco}}</td>
                        <td>@peso($representante->peso)</td>
                        <td>@fator($representante->fator)</td>
                        <td>{{$representante->observacao}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan=5>Nenhum registro</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <br>
    @endif
    @if (count($cc_fornecedor) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan = 4>Fornecedores</th>
                </tr>
                <tr>
                    <th>Representante</th>
                    <th>Balanço</th>
                    <th>Peso</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cc_fornecedor as $fornecedor)
                    <tr>
                        <td>{{$fornecedor->nome}}</td>
                        <td>{{$fornecedor->balanco}}</td>
                        <td>@peso($fornecedor->peso)</td>
                        <td>
                            {{$fornecedor->observacao}}
                            @if($fornecedor->balanco == 'Crédito')
                                @moeda($fornecedor->valor) / @moeda($fornecedor->cotacao)
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan=4>Nenhum registro</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <br>    
    @endif
    @if (count($adiamentos) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan = 7>Adiamentos</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Para</th>
                    <th>Valor</th>
                    <th>Juros</th>
                    <th>Rep</th>
                    <th>Parceiro</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($adiamentos as $adiamento)
                    <tr>
                        <td class='titular'>{{$adiamento->nome_cheque}}</td>
                        <td>@data($adiamento->data_parcela)</td>
                        <td>@data($adiamento->nova_data)</td>
                        <td>@moeda($adiamento->valor_parcela)</td>
                        <td>@moeda($adiamento->juros_totais)</td>
                        <td>{{$adiamento->nome_representante}}</td>
                        <td>{{$adiamento->nome_parceiro}}</td>
                    </tr>
                    @php 
                        $juros_totais += $adiamento->juros_totais;
                    @endphp
                @empty
                    <tr>
                        <td colspan=7>Nenhum registro</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=4>Juros Totais</th>
                    <th colspan=3>@moeda($juros_totais)</th>
                </tr>
            </tfoot>
        </table>
        <br>
    @endif
    @if (count($devolvidos) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan = 6>Cheques Devolvidos/Resgatados</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Rep</th>
                    <th>Valor</th>
                    <th>Parceiro</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($devolvidos as $devolvido)
                    <tr>
                        <td class='titular'>{{$devolvido->nome_cheque}}</td>
                        <td>@data($devolvido->data_parcela)</td>
                        <td>{{$devolvido->status}} {{$devolvido->motivo}}</td>
                        <td>{{$devolvido->nome_representante}}</td>
                        <td>@moeda($devolvido->valor_parcela)</td>
                        <td>{{$devolvido->nome_parceiro}}</td>
                    </tr>
                    @php 
                        $total_devolvido += $devolvido->valor_parcela;
                    @endphp
                @empty
                    <tr>
                        <td colspan=6>Nenhum registro</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=4>Total</th>
                    <th colspan=2>@moeda($total_devolvido)</th>
                </tr>
            </tfoot>
        </table>
        <br>
    @endif
    @if (count($depositados) > 0)
        <table>
            <thead>
                <tr>
                    <th colspan = 4>Cheques Depositados</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Valor</th>
                    <th>Representante</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($depositados as $depositado)
                    <tr>
                        <td class='titular'>{{$depositado->nome_cheque}}</td>
                        <td>@data($depositado->data_parcela)</td>
                        <td>@moeda($depositado->valor_parcela)</td>
                        <td>{{$depositado->nome_representante}}</td>
                    </tr>
                    @php 
                        $total_depositado += $depositado->valor_parcela;
                    @endphp
                @empty
                    <tr>
                        <td colspan=4>Nenhum registro</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=3>Total</th>
                    <th colspan=1>@moeda($total_depositado)</th>
                </tr>
            </tfoot>
        </table>
        <br>
    @endif
    @if(!$despesas->isEmpty())
        <table>
            <thead>
                <tr>
                    <th colspan = 3>Despesas</th>
                </tr>
                <tr>
                    <th>Nome</th>
                    <th>Local</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($despesas as $despesa)
                    <tr>
                        <td class='titular'>{{$despesa->nome}}</td>
                        <td>{{ $despesa->local->nome }}</td>
                        <td>@moeda($despesa->valor)</td>
                    </tr>
                    
                @empty
                    <tr>
                        <td colspan=3>Nenhum registro</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=2>Total</th>
                    <th colspan=1>@moeda($despesas->sum('valor'))</th>
                </tr>
            </tfoot>
        </table>
        <br>
    @endif
    @if(!$recebimentos->isEmpty())
        <table>
            <thead>
                <tr>
                    <th colspan = 4>Recebimentos</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Valor da dívida</th>
                    <th>Conta</th>
                    <th>Valor recebido</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recebimentos as $recebimento)
                    @if ($recebimento->parcela()->exists() && $recebimento->parcela->forma_pagamento == 'Cheque')
                        <tr>
                            <td class='titular'>{{$recebimento->parcela->nome_cheque}}</td>
                            <td>{{ $recebimento->parcela->forma_pagamento }} - @moeda($recebimento->parcela->valor_parcela)</td>
                            <td>{{$recebimento->conta->nome}}</td>
                            <td>@moeda($recebimento->valor)</td>
                        </tr>
                    @elseif(!$recebimento->parcela()->exists())
                    <tr>
                        <td class='titular'>
                            {{$recebimento->representante->pessoa->nome}} 
                        </td>
                        <td>{{ $recebimento->observacao }}</td>
                        <td>{{$recebimento->conta->nome}}</td>
                        <td>@moeda($recebimento->valor)</td>
                    </tr>
                    @else
                        <tr>
                            <td class='titular'>
                                {{$recebimento->parcela->venda->cliente->pessoa->nome}} 
                            </td>
                            <td>{{ $recebimento->parcela->forma_pagamento }} - @moeda($recebimento->parcela->valor_parcela)</td>
                            <td>{{$recebimento->conta->nome}}</td>
                            <td>@moeda($recebimento->valor)</td>
                        </tr>
                    @endif
                   
                @empty
                    <tr>
                        <td colspan=4>Nenhum registro</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=3>Total</th>
                    <th colspan=1>@moeda($recebimentos->sum('valor'))</th>
                </tr>
            </tfoot>
        </table>
        <br>
    @endif
</body>
</html>

