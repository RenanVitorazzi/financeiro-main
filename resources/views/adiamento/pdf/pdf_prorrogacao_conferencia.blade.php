<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONFERÊNCIA PRORROGAÇÕES E RESGATES @data($dia)</title>
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
    
    h3 {
        text-align: center;
    }
    * {
        margin-top: 5px;
        margin-bottom: 5px;
        font-size: 10px;
    }

</style>
<body>
    <h3> {{$parceiro->pessoa->nome }} - @data($dia) </h3>

    <table id="adiamentos">
        <thead>
            <th>TITULAR</th>
            <th>STATUS</th>
            <th>DATA</th>
            <th>VALOR</th>
            <th>PARA</th>
        </thead>
        <tbody>
            @forelse ($cheques as $cheque)
                <tr>
                    <td>{{ $cheque->nome_cheque }}</td>
                    <td>{{$cheque->status}}</td>
                    <td>@data($cheque->data_parcela)</td>
                    <td>@moeda($cheque->valor_parcela)</td>
                    <td>@data($cheque->nova_data)</td>
                </tr>
                @empty
                <tr>
                    <td colspan=5>Nenhum resultado!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

