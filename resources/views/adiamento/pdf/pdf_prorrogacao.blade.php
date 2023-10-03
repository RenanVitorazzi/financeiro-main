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
    /* tr:nth-child(even) {
        background-color: #e4e8ec;
    } */
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

@forelse ($cheques->groupBy('parceiro_id') as $parceiro)

    <x-table id="adiamentos">
        <x-tableheader id="copiarAdiamentos" >
            <th colspan=5>{{$parceiro->where('parceiro_id', $parceiro->first()->parceiro_id)->first()->parceiro->pessoa->nome}}</th>
        </x-tableheader>

        <x-tableheader>
            {{-- <th>#</th> --}}
            <th>STATUS</th>
            <th>TITULAR</th>
            <th>VALOR</th>
            <th>DATA</th>
            <th>PARA</th>
            {{-- <th>Número</th> --}}
            {{-- <th>Representante</th> --}}
            {{-- <th>Parceiro</th> --}}
        </x-tableheader>
        <tbody>
        @forelse ($cheques->where('parceiro_id', $parceiro->first()->parceiro_id) as $cheque)
            @if ($cheque->movimentacoes->first()->status == 'Adiado')
                <tr>
                    <td>PRORROGAÇÃO</td>
                    <td>{{ $cheque->nome_cheque }}</td>
                    <td>@moeda($cheque->valor_parcela)</td>
                    <td>@data($cheque->data_parcela)</td>
                    <td>@data($cheque->adiamentos->nova_data)</td>
                </tr>
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
                <td colspan=5>Nenhum cheque adiado!</td>
            </tr>
        @endforelse
        </tbody>
    </x-table>
    <br>
@empty

@endforelse
</body>
</html>

