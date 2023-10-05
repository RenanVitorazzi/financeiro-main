<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($pessoa->nome); ?></title>
</head>
<style>
    * {
        text-transform: uppercase;
        font-size:8px;
        margin: 0 0 0 0; 
    }
    div {
        position: absolute;
	    bottom: 10px;
        margin-left: 10px;
    }
</style>
<body>
    <div>
        <p><?php echo e($pessoa->nome); ?></p>
        <p><?php echo e($pessoa->logradouro); ?>, <?php echo e($pessoa->numero); ?></p>
        <p><?php echo e($pessoa->bairro); ?> <?php echo e(!$pessoa->complemento ? '' : '- '. $pessoa->complemento); ?></p>
        <p><?php echo e($pessoa->municipio); ?> - <?php echo e($pessoa->estado); ?></p>
        <p><?php echo e($pessoa->cep); ?></p>
    </div>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/cliente/pdf/etiqueta_endereco.blade.php ENDPATH**/ ?>