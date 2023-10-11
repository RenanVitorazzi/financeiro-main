<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Procura Cheque Impresso</title>
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
    s{
        margin: 0 0 0 0;
    }
    p {
        margin: 0 0 0 0;
    }
</style>
<body>
    {{-- <h5>Cheques à vencer - {{$representante->pessoa->nome}}</h5> --}}
    <table>
        <thead>
            <tr>
                <th>Titular</th>
                <th>Data</th>
                <th>Valor</th>
                {{-- <th>Representante</th> --}}
                <th>Banco</th>
                <th>Nº</th>
                <th>Status</th>
            </tr>

        </thead>
        <tbody>
            @forelse ($cheques as $parcela)

                <tr>
                    <td>{{ $parcela->nome_cheque }}</td>
                    <td>
                        @if ($parcela->nova_data)
                            <s>@data($parcela->data_parcela)</s>
                            <p>@data($parcela->nova_data)</p>
                        @else
                            @data($parcela->data_parcela)
                        @endif
                       
                    </td>
                    <td>@moeda($parcela->valor_parcela)</td>
                    {{-- <td>@moeda($parcela->representante_id)</td> --}}
                    <td>{{ $parcela->numero_banco }}</td>
                    <td>{{ $parcela->numero_cheque }}</td>
                    <td>{{ $parcela->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan=6>Nenhum registro</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
</body>
</html>

