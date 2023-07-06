<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estoque</title>
</head>
<style>
    * {
        margin: 10;
        padding:0;
        text-transform: uppercase;
    }
    body {
        margin-top: 1px;
    }
    table {
        margin: 0 4 0 4;
        width:100%;
        border-collapse: collapse;
        font-size: 12px;
        page-break-inside: avoid;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    th {
        background-color: #d1d1d3;
    }
    h3 {
        text-align: center;
    }
    .peso {
        background-color: #d1d1d3;
    }
    .saldo_final {
        font-weight: bold;
    }
    
</style>
<body>
    <h3>Estoque</h3>
    @if ($tipo == 'peso')
    
    <table>
        <x-table-header>
            <tr>
                <th rowspan=2>Data</th>
                <th rowspan=2>Descrição</th>
                <th colspan=3>Peso</th>
            </tr>
            <tr>
                <th>Débito</th>
                <th>Crédito</th>
                <th>Saldo</th>
            </tr>
        </x-table-header>
        <tbody>
            @forelse ($lancamentos as $lancamento)
                @if ($lancamento->balanco_estoque == 'Débito')
                    <tr>
                        <td>@data($lancamento->data)</td>
                        <td>
                            @if ($lancamento->representante_id)
                                {{ $lancamento->balanco_representante }}
                                {{ $lancamento->nome_representante }}
                                {{ $lancamento->observacao_representante }}
                            @elseif ($lancamento->fornecedor_id)
                                {{ $lancamento->balanco_fornecedor }}
                                {{ $lancamento->nome_fornecedor }}
                                {{ $lancamento->observacao_fornecedor }}
                            @else
                                {{ $lancamento->observacao}}
                            @endif
                        </td>
                        <td>@peso($lancamento->peso)</td>
                        <td></td>
                        <td>@peso($lancamento->saldo_peso)</td>
                        
                    </tr>
                @elseif ($lancamento->balanco_estoque == 'Crédito')
                    <tr>
                        <td>@data($lancamento->data)</td>
                        <td>
                            @if ($lancamento->representante_id)
                                {{ $lancamento->balanco_representante }}
                                {{ $lancamento->nome_representante }}
                                {{ $lancamento->observacao_representante }}
                            @elseif ($lancamento->fornecedor_id)
                                {{ $lancamento->balanco_fornecedor == 'Débito' ? 'COMPRA' : 'DEVOLUÇÃO'}}
                                {{ $lancamento->nome_fornecedor }}
                                {{ $lancamento->observacao_fornecedor }}
                            @else
                                {{ $lancamento->observacao}}
                            @endif
                        </td>
                        <td></td>
                        <td>@peso($lancamento->peso)</td>
                        <td class="{{$loop->last ? 'saldo_final peso' : ''}}">@peso($lancamento->saldo_peso)</td>
                       
                    </tr>
                @endif
            @empty
            <tr>
                <td colspan=5>Nenhum registro</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @elseif($tipo == 'fator')
    
    <table>
        <x-table-header>
            <tr>
                <th rowspan=2>Data</th>
                <th rowspan=2>Descrição</th>
                <th colspan=3>Fator</th>
            </tr>
            <tr>
                <th>Débito</th>
                <th>Crédito</th>
                <th>Saldo</th>
            </tr>
        </x-table-header>
        <tbody>
            @forelse ($lancamentos as $lancamento)
                @if ($lancamento->balanco_estoque == 'Débito')
                    <tr>
                        <td>@data($lancamento->data)</td>
                        <td>
                            @if ($lancamento->representante_id)
                                {{ $lancamento->balanco_representante }}
                                {{ $lancamento->nome_representante }}
                                {{ $lancamento->observacao_representante }}
                            @elseif ($lancamento->fornecedor_id)
                                {{ $lancamento->balanco_fornecedor }}
                                {{ $lancamento->nome_fornecedor }}
                                {{ $lancamento->observacao_fornecedor }}
                            @else
                                {{ $lancamento->observacao}}
                            @endif
                        </td>
                        <td>@fator($lancamento->fator)</td>
                        <td></td>
                        <td>@fator($lancamento->saldo_fator)</td>
                    </tr>
                @elseif ($lancamento->balanco_estoque == 'Crédito')
                    <tr>
                        <td>@data($lancamento->data)</td>
                        <td>
                            @if ($lancamento->representante_id)
                                {{ $lancamento->balanco_representante }}
                                {{ $lancamento->nome_representante }}
                                {{ $lancamento->observacao_representante }}
                            @elseif ($lancamento->fornecedor_id)
                                {{ $lancamento->balanco_fornecedor == 'Débito' ? 'COMPRA' : 'DEVOLUÇÃO'}}
                                {{ $lancamento->nome_fornecedor }}
                                {{ $lancamento->observacao_fornecedor }}
                            @else
                                {{ $lancamento->observacao}}
                            @endif
                        </td>
                        
                        <td></td>
                        <td>@fator($lancamento->fator)</td>
                        <td class="{{$loop->last ? 'saldo_final' : ''}}">@fator($lancamento->saldo_fator)</td>
                    </tr>
                @endif
            @empty
            <tr>
                <td colspan=8>Nenhum registro</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @else
    
    <table>
        <x-table-header>
            <tr>
                <th rowspan=2>Data</th>
                <th rowspan=2>Descrição</th>
                <th colspan=3>Peso</th>
                <th colspan=3>Fator</th>
            </tr>
            <tr>
                <th>Débito</th>
                <th>Crédito</th>
                <th>Saldo</th>
                <th>Débito</th>
                <th>Crédito</th>
                <th>Saldo</th>
            </tr>
        </x-table-header>
        <tbody>
            @forelse ($lancamentos as $lancamento)
                @if ($lancamento->balanco_estoque == 'Débito')
                    <tr>
                        <td>@data($lancamento->data)</td>
                        <td>
                            @if ($lancamento->representante_id)
                                {{ $lancamento->balanco_representante }}
                                {{ $lancamento->nome_representante }}
                                {{ $lancamento->observacao_representante }}
                            @elseif ($lancamento->fornecedor_id)
                                {{ $lancamento->balanco_fornecedor }}
                                {{ $lancamento->nome_fornecedor }}
                                {{ $lancamento->observacao_fornecedor }}
                            @else
                                {{ $lancamento->observacao}}
                            @endif
                        </td>
                        <td class='peso'>@peso($lancamento->peso)</td>
                        <td class='peso'></td>
                        <td class='peso'>@peso($lancamento->saldo_peso)</td>
                        <td>@fator($lancamento->fator)</td>
                        <td></td>
                        <td>@fator($lancamento->saldo_fator)</td>
                    </tr>
                @elseif ($lancamento->balanco_estoque == 'Crédito')
                    <tr>
                        <td>@data($lancamento->data)</td>
                        <td>
                            @if ($lancamento->representante_id)
                                {{ $lancamento->balanco_representante }}
                                {{ $lancamento->nome_representante }}
                                {{ $lancamento->observacao_representante }}
                            @elseif ($lancamento->fornecedor_id)
                                {{ $lancamento->balanco_fornecedor == 'Débito' ? 'COMPRA' : 'DEVOLUÇÃO'}}
                                {{ $lancamento->nome_fornecedor }}
                                {{ $lancamento->observacao_fornecedor }}
                            @else
                                {{ $lancamento->observacao}}
                            @endif
                        </td>
                        <td class='peso'></td>
                        <td class='peso'>@peso($lancamento->peso)</td>
                        <td class="{{$loop->last ? 'saldo_final peso' : 'peso'}}">@peso($lancamento->saldo_peso)</td>
                        <td></td>
                        <td>@fator($lancamento->fator)</td>
                        <td class="{{$loop->last ? 'saldo_final' : ''}}">@fator($lancamento->saldo_fator)</td>
                    </tr>
                @endif
            @empty
            <tr>
                <td colspan=8>Nenhum registro</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endif
</body>
</html>

