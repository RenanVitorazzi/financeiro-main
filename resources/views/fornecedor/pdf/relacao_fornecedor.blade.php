<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fornecedores</title>
</head>
<style>
    table {
        width:100%;
        border-collapse: collapse;
        font-size: 12px
    }

    td, th {
        border: 1px solid black;
        text-align: center;
    }

    th {
        background-color:black;
        color:white;
    }

    tr:nth-child(even) {
        background-color: #a9acb0;
    }
    .debito {
        color: red;
        font-weight: 300;
    }

    .conferido {
        background-color: rgb(255, 255, 105);
    }

    p {
        margin: 0 0 0 0;
    }
</style>
<body>
    <h3>
        <div>
            {{ $fornecedor->pessoa->nome }}
            (@peso($registrosContaCorrente[count($registrosContaCorrente)-1]->saldo))
        </div>
    </h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Observação</th>
                <th>Débito</th>
                <th>Crédito</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registrosContaCorrente as $conta)
                @if ($conta->data > $data_inicio)
                    @if ($conta->balanco == 'Débito')
                        <tr class="{{ $conta->conferido ? 'conferido' : ''}}">
                            <td>@data($conta->data)</td>
                            <td>
                                {{ $conta->observacao }} 
                                @if ($conta->conferido)
                                    <p>Conta conferido dia: {{ \Carbon\Carbon::parse($conta->conferido)->format('d/m/Y') }}</p>
                                @endif 
                            </td>
                            <td>@peso($conta->peso)</td>
                            <td></td>
                            
                            <td>@peso($conta->saldo)</td>
                        </tr>
                    @else
                        <tr class="{{ $conta->conferido ? 'conferido' : ''}}">
                            <td>@data($conta->data)</td>
                            <td>
                                {{ $conta->observacao }}
                                @if ($conta->conferido)
                                    <p>Conta conferido dia: {{ $conta->conferido }}</p>
                                @endif 
                            </td>
                            <td></td>
                            <td>@peso($conta->peso)</td>
                            
                            <td>@peso($conta->saldo)</td>
                        </tr>
                    @endif
                @endif
            @empty
                <tr>
                    <td colspan=5>Nenhum registro</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan=4>Saldo atual</td>
                <td class='debito'>@peso($registrosContaCorrente[count($registrosContaCorrente) - 1]->saldo)</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
