<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conta Corrente - {{ $representante->pessoa->nome }}</title>
</head>
<style>
    table {
        width:100%;
        border-collapse: collapse;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    th {
        /* background-color:black; */
        /* color:white; */
    }
    tr:nth-child(even) {
        background-color: #ebedf0;
    }
    h3 {
        text-align: center;
        margin: 0 0 0 0;
    }
</style>
<body>
    <h3>
        Conta Corrente - {{ $representante->pessoa->nome }} 
    </h3>

    <br>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Observação</th>
                <th>Crédito</th>
                <th>Débito</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($contaCorrente as $registro)
                <tr>
                    <td>@data($registro->data)</td> 
                    <td>{{ $registro->observacao }}</td>
                    {{-- <td>@peso($registro->peso)</td> --}}

                    @if ($registro->balanco == 'Reposição')
                        <td>- @peso($registro->peso)</td>
                        <td></td>
                    @elseif($registro->balanco == 'Venda')
                        <td></td>
                        <td>@peso($registro->peso)</td>
                    @endif
                    <td>@peso($registro->saldo_peso)</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhum registro criado</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>