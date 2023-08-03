<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CLIENTES {{$representante->pessoa->nome}}</title>
</head>
<style>
    * {
        text-transform: uppercase;
    }
    table {
        width:100%;
        border-collapse: collapse;
        font-size:12px;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    tr:nth-child(even) {
        background-color: #d9dde2;
    }
    td  {
        height: 25px;
        font-size:10px;
    }
    h3 {
        margin: 2 2 2 2;
        text-align: center;
    }
</style>
<body>
    <h3> CLIENTES - {{$representante->pessoa->nome}}</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>UF</th>
                <th>Município</th>
                <th>Endereço</th>
                <th>Telefones</th>
                <th>Geo</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clientes as $cliente)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $cliente->nome }}</td>
                    <td>{{ $cliente->estado }}</td>
                    <td>{{ substr($cliente->municipio,0,15) }}</td>
                    
                    <td>
                        {{ $cliente->cep ? $cliente->cep.',' : ''}}
                        {{ $cliente->bairro ? $cliente->bairro.',' : ''}} 
                        {{ $cliente->logradouro ? $cliente->logradouro.',' : ''}}
                        {{ $cliente->numero ? $cliente->numero.',' : ''}}
                        {{ $cliente->complemento ? $cliente->complemento.',' : ''}}
                    </td>
                    
                    <td>
                        @if ($cliente->celular)
                            {{ $cliente->celular }}
                        @endif
                        
                        @if ($cliente->telefone)
                            <br>
                            {{ $cliente->telefone }}
                        @endif
                        @if ($cliente->telefone2)
                            <br>
                            {{ $cliente->telefone2 }}
                        @endif
                        @if ($cliente->celular2)
                            <br>
                            {{ $cliente->celular2 }}
                        @endif
                    </td>
                    <td>
                        @if ($cliente->lat && $cliente->lng)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $cliente->lat }},{{ $cliente->lng }}">Abrir</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan=7>Nenhum registro</td>
                </tr>
            @endforelse
        </tbody>
    </table>


</body>
</html>

