<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conta Corrente - <?php echo e($representante->pessoa->nome); ?></title>
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
    th {
        /* background-color:black; */
        /* color:white; */
    }
    tr:nth-child(even) {
        background-color: #ebedf0;
    }
    h3 {
        text-align: center;
        margin: 0 0 0 0;
    }
</style>
<body>
    <h3>
        Conta Corrente - <?php echo e($representante->pessoa->nome); ?> 
    </h3>

    <br>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Observação</th>
                <th>Crédito</th>
                <th>Débito</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $contaCorrente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($registro->data)); ?></td> 
                    <td><?php echo e($registro->observacao); ?></td>
                    

                    <?php if($registro->balanco == 'Reposição'): ?>
                        <td>- <?php echo number_format($registro->peso, 2, ',', '.'); ?></td>
                        <td></td>
                    <?php elseif($registro->balanco == 'Venda'): ?>
                        <td></td>
                        <td><?php echo number_format($registro->peso, 2, ',', '.'); ?></td>
                    <?php endif; ?>
                    <td><?php echo number_format($registro->saldo_peso, 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5">Nenhum registro criado</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/conta_corrente_representante/pdf/impresso_terceiros.blade.php ENDPATH**/ ?>