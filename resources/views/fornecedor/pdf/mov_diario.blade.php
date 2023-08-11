<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$titulo}}</title>
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
    <h3>{{$titulo}}</h3>
    @if (!$cc_representante->isEmpty())
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
    @if (!$cc_fornecedor->isEmpty())
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
    @if ($cheques->has('Adiado'))
        <table>
            <thead>
                <tr>
                    <th colspan = 8>Prorrogações</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Para</th>
                    <th>Dias</th>
                    <th>Valor</th>
                    <th>Juros</th>
                    <th>Rep</th>
                    <th>Parceiro</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cheques['Adiado'] as $chequesAdiados)
                    <tr>
                        <td class='titular'>{{$chequesAdiados->nome_cheque}}</td>
                        <td>@data($chequesAdiados->data_parcela)</td>
                        <td>@data($chequesAdiados->adiamentos->nova_data)</td>
                        <td>{{ $chequesAdiados->adiamentos->dias_totais }}</td>
                        <td>@moeda($chequesAdiados->valor_parcela)</td>
                        <td>@moeda($chequesAdiados->adiamentos->juros_totais)</td>
                        <td>{{$chequesAdiados->representante->pessoa->nome}}</td>
                        <td>{{$chequesAdiados->parceiro->pessoa->nome ?? 'Carteira'}}</td>
                    </tr>
                   
                @empty
                    <tr>
                        <td colspan=7>Nenhum registro</td>
                    </tr>
                @endforelse
            </tbody>
            
        </table>
        <br>
    @endif
    @if ($cheques->has('Devolvido'))
        <table>
            <thead>
                <tr>
                    <th colspan = 6>Cheques Devolvidos/Resgatados</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Representante</th>
                    <th>Valor</th>
                    <th>Parceiro</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cheques['Devolvido'] as $devolvido)
                    <tr>
                        <td class='titular'>{{$devolvido->nome_cheque}}</td>
                        <td>@data($devolvido->data_parcela)</td>
                        <td>{{$devolvido->status}} {{$devolvido->motivo}}</td>
                        <td>{{$devolvido->representante->pessoa->nome}}</td>
                        <td>@moeda($devolvido->valor_parcela)</td>
                        <td>{{$devolvido->parceiro->pessoa->nome}}</td>
                    </tr>
                    
                @empty
                    <tr>
                        <td colspan=6>Nenhum registro</td>
                    </tr>
                @endforelse
            </tbody>
            
        </table>
        <br>
    @endif
    @if ($cheques->has('Resgatado'))
    <table>
        <thead>
            <tr>
                <th colspan = 6>Cheques Devolvidos/Resgatados</th>
            </tr>
            <tr>
                <th>Cliente</th>
                <th>Data</th>
                <th>Status</th>
                <th>Representante</th>
                <th>Valor</th>
                <th>Parceiro</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cheques['Resgatado'] as $resgatado)
                <tr>
                    <td class='titular'>{{$resgatado->nome_cheque}}</td>
                    <td>@data($resgatado->data_parcela)</td>
                    <td>{{$resgatado->status}}</td>
                    <td>{{$resgatado->representante->pessoa->nome}}</td>
                    <td>@moeda($resgatado->valor_parcela)</td>
                    <td>{{$resgatado->parceiro->pessoa->nome}}</td>
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
    {{-- @if (count($cheques['Depositado']) > 0) --}}
    @if ($cheques->has('Depositado'))
        <table>
            <thead>
                <tr>
                    <th colspan = 4>Cheques Depositados</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Representante</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cheques['Depositado'] as $depositado)
                    <tr>
                        <td class='titular'>{{$depositado->nome_cheque}}</td>
                        <td>@data($depositado->data_parcela)</td>
                        <td>{{$depositado->representante->pessoa->nome}}</td>
                        <td>@moeda($depositado->valor_parcela)</td>
                    </tr>
                  
                @empty
                    <tr>
                        <td colspan=4>Nenhum registro</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=3>TOTAL</td>
                    <td>@moeda($cheques['Depositado']->sum('valor_parcela'))</td>
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
                    <th>Observação</th>
                    <th>Conta</th>
                    <th>Valor recebido</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recebimentos as $recebimento)
                    @if ($recebimento->parcela()->exists() && $recebimento->parcela->forma_pagamento == 'Cheque')
                        <tr>
                            <td class='titular'>{{$recebimento->parcela->nome_cheque}}</td>
                            <td>Ref. {{ $recebimento->parcela->forma_pagamento }} - @moeda($recebimento->parcela->valor_parcela)</td>
                            <td>{{ $recebimento->conta->nome ?? '' }}</td>
                            <td>@moeda($recebimento->valor)</td>
                        </tr>
                    @elseif(!$recebimento->parcela()->exists())
                    <tr>
                        <td class='titular'>
                            {{$recebimento->representante->pessoa->nome}} 
                        </td>
                        <td>{{ $recebimento->observacao }}</td>
                        <td>{{ $recebimento->conta->nome ?? '' }}</td>
                        <td>@moeda($recebimento->valor)</td>
                    </tr>
                    @else
                        <tr>
                            <td class='titular'>
                                {{$recebimento->parcela->venda->cliente->pessoa->nome}} 
                            </td>
                            <td>{{ $recebimento->parcela->forma_pagamento }} - @moeda($recebimento->parcela->valor_parcela)</td>
                            <td>{{ $recebimento->conta->nome ?? '' }}</td>
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

