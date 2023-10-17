<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relação fornecedores (a partir de <?php echo date('d/m/Y', strtotime($data)); ?> )</title>
</head>
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
    thead th {
        background-color:black;
        color:white;
    }
    tbody tr:nth-child(even) {
        background-color: #a9acb0;
    }
    h3 {
        text-align: center;
    }
    .nome {
        font-size: 10px;
        text-align: left;
    }
</style>
<body>
    <h3>Relação fornecedores (a partir de <?php echo date('d/m/Y', strtotime($data)); ?> )</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>FORNECEDOR</th>
                <th>COMPRA</th>
                <th>FECHAMENTO</th>
                <th>DIFERENÇA</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $cc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cc_fornecedor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($cc_fornecedor->pessoa->nome); ?></td>
                    <td><?php echo number_format($cc_fornecedor->debito, 2, ',', '.'); ?></td>
                    <td><?php echo number_format($cc_fornecedor->credito, 2, ',', '.'); ?></td>
                    <td><?php echo number_format($cc_fornecedor->credito - $cc_fornecedor->debito, 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=5>Nenhum registro</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan=2>TOTAL</th>
                <th><?php echo number_format($cc->sum('debito'), 2, ',', '.'); ?></th>
                <th><?php echo number_format($cc->sum('credito'), 2, ',', '.'); ?></th>
                <th><?php echo number_format($cc->sum('credito') - $cc->sum('debito'), 2, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/relatorios/relacao_deb_cred_fornecedores.blade.php ENDPATH**/ ?>