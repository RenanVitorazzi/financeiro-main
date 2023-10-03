<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carteira de cheques</title>
</head>
<style>
    table {
        width:50%;
        border-collapse: collapse;
        font-size:9px;
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
        background-color: #dadada;
    }
    h1 {
        text-align: center;
    }
</style>
<table class="grande">
    <thead>
        <tr>
            <th colspan = 2>Carteira {{\Carbon\Carbon::now()->format('d/m/Y')}}</th>
        </tr>
        <tr>
            <th>Mês</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($carteira as $carteira_mensal)
          
            <tr>
                <td>{{ $carteira_mensal->month }}/{{ $carteira_mensal->year }}</td>
                <td>@moeda($carteira_mensal->total_mes)</td>
            </tr>
        
        @empty
            <tr>
                <td colspan=2>Nenhum registro</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td><b>Total</b></td>
            <td><b>@moeda($totalCarteira[0]->totalCarteira)</b></td>
        </tr>
    </tfoot>
</table>
</html>

