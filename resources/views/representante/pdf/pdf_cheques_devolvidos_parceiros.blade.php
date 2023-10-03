<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relação de cheques nos parceiros</title>
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
        background-color: #d6d8db;
    }
    h3 {
        text-align: center;
        margin: 0px;
    }
    .titular {
        font-size: 10px;
        text-align: left;
        padding-left: 5px
    }
    p {
        margin: 0;
    }
</style>
<body>
    <h3>CHEQUES NOS PARCEIROS - {{$representante->pessoa->nome}} @data($hoje)</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Titular</th>
                <th>Número</th>
                <th>Parceiro</th>
                <th>Valor</th>
                <th>Pagamentos</th>
                <th>Total devedor</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cheques as $cheque)
                <tr>
                    <td>
                        @if ($cheque->adiamentos)
                            <s>@data($cheque->data_parcela)</s><br>
                            @data($cheque->adiamentos->nova_data)
                        @else
                            @data($cheque->data_parcela)
                        @endif
                    </td>
                    <td class='titular'>{{$cheque->nome_cheque}}</td>
                    <td>{{ $cheque->numero_cheque }}</td>
                    <td>{{ $cheque->parceiro->pessoa->nome ?? 'Carteira'}}</td>
                    <td>@moeda($cheque->valor_parcela)</td>
                    <td>
                        @forelse ($cheque->pagamentos_representantes as $pagamentos)
                            <p>@data($pagamentos->data) - @moeda($pagamentos->valor) {{ $pagamentos->confirmado == 1 ? '' : '(Não confirmado)'}}</p>
                            @php
                                $totalPago += $pagamentos->valor;
                            @endphp
                            @empty
                        @endforelse
                    </td>
                    <td>@moeda($cheque->valor_parcela - $cheque->pagamentos_representantes->sum('valor'))</td>

                    {{-- <td>{{ $parceiros->where('id', '=', $cheque->parceiro_id)->first() }}</td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan=7>Nenhum registro</td>
                </tr>
            @endforelse
            <tfoot>
                <tr>
                    <td colspan=4><b>Total</b></td>
                    <td><b>@moeda($cheques->sum('valor_parcela'))</b></td>
                    <td><b>@moeda($totalPago)</b></td>
                    <td><b>@moeda($cheques->sum('valor_parcela') - $totalPago)</b></td>
                </tr>
            </tfoot>
        </tbody>

    </table>
</body>
</html>

