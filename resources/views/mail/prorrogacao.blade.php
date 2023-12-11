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
        background-color:black;
        color:white;
    }
    tr:nth-child(even) {
        background-color: #a9acb0;
    }
</style>

<table>
    <thead>
        <th>STATUS</th>
        <th>TITULAR</th>
        <th>VALOR</th>
        <th>DATA</th>
        <th>PARA</th>
    </thead>
    <tbody>
    @foreach ($chequesProrrogados as $cheque)
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
        

    @endforeach
    </tbody>
</table>