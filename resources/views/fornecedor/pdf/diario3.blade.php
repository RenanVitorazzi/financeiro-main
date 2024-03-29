<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório Geral</title>
</head>
<style>
    /* * {
        margin: 5 5 5 5;
    } */
    table {
        border-collapse: collapse;
        font-size: 12px;
        page-break-inside: avoid;
        width:100%;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    tr:nth-child(even) {
        background-color: #d9dde2;
    }
    .tabela_invisivel {
        border: 2px solid rgb(255, 255, 255);
        width: 50%;
        padding-top:0px;
        margin-top: 0px;
        vertical-align: top;
    }
    
</style>
<body>

    <table >
        <tr >
            <td class='tabela_invisivel'>
                <table>
                    <thead>
                        <tr>
                            <th colspan = 2>Carteira {{$hoje}}</th>
                        </tr>
                        <tr>
                            <th>Mês</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($carteira as $carteira_mensal)
                            <tr>
                                <td>{{ $carteira_mensal->month }}/{{ $carteira_mensal->year }}</td>
                                <td>@moeda($carteira_mensal->total_mes)</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan=2>Nenhum registro</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><b>Total</b></td>
                            <td><b>@moeda($totalCarteira[0]->totalCarteira)</b></td>
                        </tr>
                    </tfoot>
                </table>
                
            </td>
            <td class='tabela_invisivel'>
                <table>
                    <thead>
                        <tr>
                            <th colspan=2>Fornecedores (Débito)</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Saldo</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fornecedores->where('conta_corrente_sum_peso_agregado', '<', 0) as $fornecedor)
                            <tr>
                                <td>{{ $fornecedor->pessoa->nome }}</td>
                                <td>@peso($fornecedor->conta_corrente_sum_peso_agregado)</td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan=2>Nenhum registro</td>
                            </tr>
                        @endforelse
                        <tfoot>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b>@peso($fornecedores->where('conta_corrente_sum_peso_agregado', '<', 0)->sum('conta_corrente_sum_peso_agregado'))</b></td>
                            
                                
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>


    <table>
        <tr>
            <td class='tabela_invisivel'>
                <table>
                    <thead>
                        <tr>
                            <th colspan=4>Representantes</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Peso</th>
                            <th>Fator</th>
                            <th>Total</th>
                            <!-- <th>Devolvidos</th>
                            <th>Prorrogações</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($representantes as $representante)
                            @if ($representante->conta_corrente_sum_peso_agregado != 0 || $representante->conta_corrente_sum_fator_agregado != 0)
                                <tr>
                                    <td>{{ $representante->pessoa->nome }}</td>
                                    <td>@peso(abs($representante->conta_corrente_sum_peso_agregado))</td>
                                    <td>@fator(abs($representante->conta_corrente_sum_fator_agregado))</td>
                                    <td>@peso(abs($representante->conta_corrente_sum_peso_agregado + ($representante->conta_corrente_sum_fator_agregado / 32)) )</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan=4>Nenhum registro</td>
                            </tr>
                        @endforelse
                        {{-- <tr>
                            <td>ESTOQUE</td>
                            <td>@peso($estoque->sum('peso_agregado'))</td>
                            <td>@fator($estoque->sum('fator_agregado'))</td>
                            <td>@peso($estoque->sum('peso_agregado') + ($estoque->sum('fator_agregado') / 32) )</td>
                        </tr> --}}
                        <tfoot>
                            
                            {{-- <tr>
                                <td><b>Total</b></td>
                                <td><b>@peso(abs($representantes->sum('conta_corrente_sum_peso_agregado')) + $estoque->sum('peso_agregado'))</b></td>
                                <td><b>@fator(abs($representantes->sum('conta_corrente_sum_fator_agregado'))+ $estoque->sum('fator_agregado'))</b></td>
                                <td>@peso($representantes->sum('conta_corrente_sum_peso_agregado'))</td>
                                <td>@fator($representantes->sum('conta_corrente_sum_fator_agregado'))</td>
                                <td>
                                    <b>
                                        @peso(
                                            abs($representantes->sum('conta_corrente_sum_peso_agregado')) + $estoque->sum('peso_agregado') +
                                            (abs($representantes->sum('conta_corrente_sum_fator_agregado'))/32 + $estoque->sum('fator_agregado') / 32)
                                        )
                                    </b>
                                </td>
                            </tr> 
                            --}}
                            <tr>
                                <td><b>Total</b></td>
                                <td><b>@peso(abs($representantes->sum('conta_corrente_sum_peso_agregado')))</b></td>
                                <td><b>@fator(abs($representantes->sum('conta_corrente_sum_fator_agregado')))</b></td>
                                {{-- <td>@peso($representantes->sum('conta_corrente_sum_peso_agregado'))</td> --}}
                                {{-- <td>@fator($representantes->sum('conta_corrente_sum_fator_agregado'))</td> --}}
                                <td>
                                    <b>
                                        @peso(
                                            abs($representantes->sum('conta_corrente_sum_peso_agregado')) +
                                            (abs($representantes->sum('conta_corrente_sum_fator_agregado'))/32)
                                        )
                                    </b>
                                </td>
                            </tr>
                        </tfoot>
                    </tbody>
                   
                </table>
            </td>
            <td class='tabela_invisivel'>
                <table>
                    <thead>
                        <tr>
                            <th colspan=5>Cheques devolvidos</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Conta Corrente</th>
                            <th>Escritório</th>
                            <th>Parceiros</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($representantes as $representante)
                            @if ($representante->conta_corrente_sum_peso_agregado != 0 || $representante->conta_corrente_sum_fator_agregado != 0)
                                <tr>
                                    <td>{{ $representante->pessoa->nome }}</td>
                                    <td>@moeda($chequesEmAberto[$representante->id]['contaCorrente'] )</td>
                                    <td>@moeda($chequesEmAberto[$representante->id]['escritorio'] )</td>
                                    <td>@moeda($chequesEmAberto[$representante->id]['parceiros'] )</td>
                                    <td>@moeda($chequesEmAberto[$representante->id]['totalGeral'] )</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan=5>Nenhum registro</td>
                            </tr>
                        @endforelse
                        <tfoot>
                            <tr>
                                <td colspan=4><b>Total</b></td>
                                <td><b>@moeda($totalGeralDeTodosChequesDevolvidos)</b></td>
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td class='tabela_invisivel'>

            </td>
            <td class='tabela_invisivel'>
                <table>
                    <thead>
                        <tr>
                            <th colspan=2>Fornecedores (Crédito)</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Saldo</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fornecedores->where('conta_corrente_sum_peso_agregado', '>', 0.005) as $fornecedor)
                            <tr>
                                <td>{{ $fornecedor->pessoa->nome }}</td>
                                <td>@peso($fornecedor->conta_corrente_sum_peso_agregado)</td>
                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan=2>Nenhum registro</td>
                            </tr>
                        @endforelse
                        <tfoot>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b>@peso($fornecedores->where('conta_corrente_sum_peso_agregado', '>', 0.005)->sum('conta_corrente_sum_peso_agregado'))</b></td>
                            
                                
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    
</body>
</html>

