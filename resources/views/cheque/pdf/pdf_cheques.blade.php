<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cheques à vencer - {{$representante->pessoa->nome}}</title>
</head>
<style>
    * {
        margin: 5 5 5 5;
    }
    table {
        margin: 0 0 0 0;
        width:100%;
        border-collapse: collapse;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
        font-size: 10px;
    }
    th {
        background-color:black;
        color:white;
    }
    tr:nth-child(even) {
        background-color: #a9acb0;
    }
    h5 {
        text-align: center;
        margin: 0 0 0 0;
    }
</style>
<body>
    {{-- <h5>Cheques à vencer - {{$representante->pessoa->nome}}</h5> --}}
    <table>
        <thead>
            <tr>
                <th>Titular</th>
                <th>Banco</th>
                <th>Número</th>
                <th>Vencimento</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($parcelas as $parcela)

                <tr>
                    <td>{{ $parcela->nome_cheque }}</td>
                    <td>{{ $parcela->numero_banco }}</td>
                    <td>{{ $parcela->numero_cheque }}</td>
                    <td>@data($parcela->data_parcela)</td>
                    <td>@moeda($parcela->valor_parcela)</td>
                </tr>
            @empty
                <tr>
                    <td colspan=3>Nenhum registro</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan=4><b>TOTAL</b></td>
                <td><b>@moeda($parcelas->sum('valor_parcela'))</b></td>
            </tr>
        </tfoot>
    </table>
    
</body>
</html>

