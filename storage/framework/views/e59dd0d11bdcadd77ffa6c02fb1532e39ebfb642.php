<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cheques à vencer - <?php echo e($representante->pessoa->nome); ?></title>
</head>
<style>
    * {
        margin: 5 5 5 5;
    }
    table {
        margin: 0 0 0 0;
        width:100%;
        border-collapse: collapse;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
        font-size: 10px;
    }
    th {
        background-color:black;
        color:white;
    }
    tr:nth-child(even) {
        background-color: #a9acb0;
    }
    h5 {
        text-align: center;
        margin: 0 0 0 0;
    }
</style>
<body>
    
    <table>
        <thead>
            <tr>
                <th>Titular</th>
                <th>Banco</th>
                <th>Número</th>
                <th>Vencimento</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $parcelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <tr>
                    <td><?php echo e($parcela->nome_cheque); ?></td>
                    <td><?php echo e($parcela->numero_banco); ?></td>
                    <td><?php echo e($parcela->numero_cheque); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($parcela->data_parcela)); ?></td>
                    <td><?php echo 'R$ ' . number_format($parcela->valor_parcela, 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=3>Nenhum registro</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan=4><b>TOTAL</b></td>
                <td><b><?php echo 'R$ ' . number_format($parcelas->sum('valor_parcela'), 2, ',', '.'); ?></b></td>
            </tr>
        </tfoot>
    </table>
    
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/cheque/pdf/pdf_cheques.blade.php ENDPATH**/ ?>