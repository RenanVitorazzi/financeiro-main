<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relação fornecedores (a partir de @data($data) )</title>
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
    thead th {
        background-color:black;
        color:white;
    }
    tbody tr:nth-child(even) {
        background-color: #a9acb0;
    }
    h3 {
        text-align: center;
    }
    .nome {
        font-size: 10px;
        text-align: left;
    }
</style>
<body>
    <h3>Relação fornecedores (a partir de @data($data) )</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>FORNECEDOR</th>
                <th>COMPRA</th>
                <th>FECHAMENTO</th>
                <th>DIFERENÇA</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cc as $cc_fornecedor)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$cc_fornecedor->pessoa->nome}}</td>
                    <td>@peso($cc_fornecedor->debito)</td>
                    <td>@peso($cc_fornecedor->credito)</td>
                    <td>@peso($cc_fornecedor->credito - $cc_fornecedor->debito)</td>
                </tr>
            @empty
                <tr>
                    <td colspan=5>Nenhum registro</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan=2>TOTAL</th>
                <th>@peso($cc->sum('debito'))</th>
                <th>@peso($cc->sum('credito'))</th>
                <th>@peso($cc->sum('credito') - $cc->sum('debito'))</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>

