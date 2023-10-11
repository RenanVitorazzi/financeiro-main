<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$pessoa->nome}}</title>
</head>
<style>
    * {
        text-transform: uppercase;
    }
    div {
        margin-top:5cm;
	    margin-left:2cm;
    }
</style>
<body>
    <div>
        <p>{{$pessoa->nome}}</p>
        <p>{{$pessoa->logradouro}}, {{$pessoa->numero}}</p>
        <p>{{$pessoa->bairro}} {{!$pessoa->complemento ? '' : '- '. $pessoa->complemento}}</p>
        <p>{{$pessoa->municipio}} - {{$pessoa->estado}}</p>
        <p>{{$pessoa->cep}}</p>
    </div>
</body>
</html>

