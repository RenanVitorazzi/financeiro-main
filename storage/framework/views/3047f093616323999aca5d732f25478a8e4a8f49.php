<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CLIENTES <?php echo e($representante->pessoa->nome); ?></title>
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
    <h3> CLIENTES - <?php echo e($representante->pessoa->nome); ?></h3>
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
            <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($cliente->nome); ?></td>
                    <td><?php echo e($cliente->estado); ?></td>
                    <td><?php echo e(substr($cliente->municipio,0,15)); ?></td>
                    
                    <td>
                        <?php echo e($cliente->cep ? $cliente->cep.',' : ''); ?>

                        <?php echo e($cliente->bairro ? $cliente->bairro.',' : ''); ?> 
                        <?php echo e($cliente->logradouro ? $cliente->logradouro.',' : ''); ?>

                        <?php echo e($cliente->numero ? $cliente->numero.',' : ''); ?>

                        <?php echo e($cliente->complemento ? $cliente->complemento.',' : ''); ?>

                    </td>
                    
                    <td>
                        <?php if($cliente->celular): ?>
                            <?php echo e($cliente->celular); ?>

                        <?php endif; ?>
                        
                        <?php if($cliente->telefone): ?>
                            <br>
                            <?php echo e($cliente->telefone); ?>

                        <?php endif; ?>
                        <?php if($cliente->telefone2): ?>
                            <br>
                            <?php echo e($cliente->telefone2); ?>

                        <?php endif; ?>
                        <?php if($cliente->celular2): ?>
                            <br>
                            <?php echo e($cliente->celular2); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($cliente->lat && $cliente->lng): ?>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo e($cliente->lat); ?>,<?php echo e($cliente->lng); ?>">Abrir</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=7>Nenhum registro</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>


</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/cliente/pdf/pdf_cliente.blade.php ENDPATH**/ ?>