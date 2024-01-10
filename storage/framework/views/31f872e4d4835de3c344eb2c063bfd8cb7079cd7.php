<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONFERÊNCIA PRORROGAÇÕES E RESGATES <?php echo date('d/m/Y', strtotime($dia)); ?></title>
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

    /* td:first-child {
        width:15%;
    }

    td:nth-child(2) {
        width:40%;
    }     */
</style>
<body>
    <h3> <?php echo e($parceiro->pessoa->nome); ?> - <?php echo date('d/m/Y', strtotime($dia)); ?> </h3>

    <table id="adiamentos">
        <thead>
            <th>TITULAR</th>
            <th>STATUS</th>
            <th>DATA</th>
            <th>VALOR</th>
            <th>PARA</th>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $cheques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cheque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($cheque->nome_cheque); ?></td>
                    <td><?php echo e($cheque->status); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></td>
                    <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($cheque->nova_data)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=5>Nenhum resultado!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/adiamento/pdf/pdf_prorrogacao_conferencia.blade.php ENDPATH**/ ?>