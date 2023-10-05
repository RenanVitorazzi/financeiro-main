<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PRORROGAÇÕES E RESGATES @data($dia)</title>
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

    td:first-child {
        width:15%;
    }

    td:nth-child(2) {
        width:40%;
    }    
</style>
<body>

@forelse ($cheques->groupBy('parceiro_id') as $parceiro)

    <x-table id="adiamentos">
        <x-tableheader id="copiarAdiamentos" >
            <th colspan=5>{{$parceiro->where('parceiro_id', $parceiro->first()->parceiro_id)->first()->parceiro->pessoa->nome}}</th>
        </x-tableheader>

        <x-tableheader>
            <th>STATUS</th>
            <th>TITULAR</th>
            <th>VALOR</th>
            <th>DATA</th>
            <th>PARA</th>
        </x-tableheader>
        <tbody>
        @forelse ($cheques->where('parceiro_id', $parceiro->first()->parceiro_id) as $cheque)
            @if ($cheque->movimentacoes->first()->status == 'Adiado')
                @if ($antigosAdiamentos->where('parcela_id', $cheque->id)->first())
                    <tr>
                        <td>PRORROGAÇÃO</td>
                        <td>{{ $cheque->nome_cheque }}</td>
                        <td>@moeda($cheque->valor_parcela)</td>
                        <td>
                            <s>@data($cheque->data_parcela)</s>
                            <p>@data($antigosAdiamentos->where('parcela_id', $cheque->id)->first()->data)</p>
                        </td>
                        <td>@data($cheque->adiamentos->nova_data)</td>
                    </tr>
                @else
                    <tr>
                        <td>PRORROGAÇÃO</td>
                        <td>{{ $cheque->nome_cheque }}</td>
                        <td>@moeda($cheque->valor_parcela)</td>
                        <td>@data($cheque->data_parcela)</td>
                        <td>@data($cheque->adiamentos->nova_data)</td>
                    </tr>
                @endif
            @elseif ($cheque->movimentacoes->first()->status == 'Resgatado')
                <tr>
                    <td>RESGATE</td>
                    <td>{{ $cheque->nome_cheque }}</td>
                    <td>@moeda($cheque->valor_parcela)</td>
                    <td>@data($cheque->data_parcela)</td>
                    <td>-</td>
                </tr>
            @endif
            
        @empty
            <tr>
                <td colspan=5>Nenhuma prorrogação!</td>
            </tr>
        @endforelse
        </tbody>
    </x-table>
    <br>
@empty
<h5>Nenhuma prorrogação ou resgate!</h5>
@endforelse
</body>
</html>

