<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório de Vendas </title>
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
        background-color:black;
        color:white;
    }
    tr:nth-child(even) {
        background-color: #a9acb0;
    }
    h1 {
        text-align: center;
    }
</style>
<body>
    <h1>Relatório Vendas - </h1>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Cliente</th>
                <th>Peso</th>
                <th>Fator</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $vendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($venda->data_venda)); ?></td>
                    <td><?php echo e($venda->nome_cliente); ?></td>
                    <td><?php echo number_format($venda->peso, 2, ',', '.'); ?></td>
                    <td><?php echo number_format($venda->fator, 1, ',', '.'); ?></td>
                    <td><?php echo 'R$ ' . number_format($venda->valor_total, 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=5>Nenhum registro</td>
                </tr>
            <?php endif; ?>
            <tfoot>
                <tr>
                    <td colspan=2><b>Total</b></td>
                    <td><b><?php echo number_format($totalVendas[0]->peso, 2, ',', '.'); ?></b></td>
                    <td><b><?php echo number_format($totalVendas[0]->fator, 1, ',', '.'); ?></b></td>
                    <td><b><?php echo 'R$ ' . number_format($totalVendas[0]->valor_total, 2, ',', '.'); ?></b></td>
                </tr>
            </tfoot>
        </tbody>
    </table>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/venda/pdf/relatorio_venda.blade.php ENDPATH**/ ?>