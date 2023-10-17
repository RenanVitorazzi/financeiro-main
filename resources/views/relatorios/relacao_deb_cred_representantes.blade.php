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
    <h3>Relação representantes (a partir de @data($data) )</h3>
    <table>
        <thead>
            <tr>
                <th rowspan=2>#</th>
                <th rowspan=2>REPRESENTANTE</th>
                <th colspan=3>PESO</th>
                <th colspan=3>FATOR</th>
            </tr>
            <tr>
                <th>VENDA</th>
                <th>REPOSIÇÃO</th>
                <th>DIFERENÇA</th>

                <th>VENDA</th>
                <th>REPOSIÇÃO</th>
                <th>DIFERENÇA</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cc as $cc_representante)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$cc_representante->pessoa->nome}}</td>
                    
                    <td>@peso($cc_representante->debito_peso)</td>
                    <td>@peso($cc_representante->credito_peso)</td>
                    <td>@peso($cc_representante->credito_peso - $cc_representante->debito_peso)</td>
                    
                    <td>@fator($cc_representante->debito_fator)</td>
                    <td>@fator($cc_representante->credito_fator)</td>
                    <td>@fator($cc_representante->credito_fator - $cc_representante->debito_fator)</td>
                </tr>
            @empty
                <tr>
                    <td colspan=8>Nenhum registro</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan=2>TOTAL</th>
                <th>@peso($cc->sum('debito_peso'))</th>
                <th>@peso($cc->sum('credito_peso'))</th>
                <th>@peso($cc->sum('credito_peso') - $cc->sum('debito_peso'))</th>

                <th>@peso($cc->sum('debito_fator'))</th>
                <th>@peso($cc->sum('credito_fator'))</th>
                <th>@peso($cc->sum('credito_fator') - $cc->sum('debito_fator'))</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>

