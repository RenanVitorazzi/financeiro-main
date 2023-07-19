<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($cliente->pessoa->nome); ?></title>
</head>
<style>
    table {
        width:100%;
        border-collapse: collapse;
        font-size:12px;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    /* tr:nth-child(even) {
        background-color: #d9dde2;
    } */
    h1 {
        text-align: center;
    }
    .nome {
        font-size:10px;
    }
    .fator {
        background-color: #d9dde2;
    }
</style>
<body>
    <table>
        <thead>
            <tr>
                <th colspan = 7><?php echo e($cliente->pessoa->nome); ?></th>
            </tr>
            <tr>
                <td>Data</td>
                <td colspan=4>Compra</td>
                <td>Valor</td>
                <td>Forma de pagamento</td>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $compras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if(!$consignados->where('venda_id', $compra->id)->isEmpty()): ?>
                    <tr>
                        <td rowspan=2>
                            (<?php echo date('d/m/Y', strtotime($consignados->where('venda_id', $compra->id)->first()->data)); ?>) 
                            <?php echo date('d/m/Y', strtotime($compra->data_venda)); ?>
                        </td>
                        <td>P</td>
                        <td>
                            (<?php echo number_format($consignados->where('venda_id', $compra->id)->first()->peso, 2, ',', '.'); ?>) 
                            <?php echo number_format($compra->peso, 2, ',', '.'); ?>
                        </td>
                        <td><?php echo 'R$ ' . number_format($compra->cotacao_peso, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($compra->peso * $compra->cotacao_peso, 2, ',', '.'); ?></td>
                        <td rowspan=2><?php echo 'R$ ' . number_format(($compra->peso * $compra->cotacao_peso) + ($compra->fator * $compra->cotacao_fator), 2, ',', '.'); ?></td>
                        <td rowspan=2>
                            <?php $__currentLoopData = $parcelas->where('venda_id', $compra->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($parcela->forma_pagamento); ?>: <?php echo date('d/m/Y', strtotime($parcela->data_parcela)); ?> - <?php echo 'R$ ' . number_format($parcela->valor_parcela, 2, ',', '.'); ?></li>                                
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <li>Total: <?php echo 'R$ ' . number_format($parcelas->where('venda_id', $compra->id)->sum('valor_parcela'), 2, ',', '.'); ?></li>
                        </td>
                    </tr>
                    <tr>
                        <td class='fator'>F</td>
                        <td class='fator'>
                            (<?php echo number_format($consignados->where('venda_id', $compra->id)->first()->fator, 1, ',', '.'); ?>) 
                            <?php echo number_format($compra->fator, 1, ',', '.'); ?>
                        </td>
                        <td class='fator'><?php echo 'R$ ' . number_format($compra->cotacao_fator, 2, ',', '.'); ?></td>
                        <td class='fator'><?php echo 'R$ ' . number_format($compra->fator * $compra->cotacao_fator, 2, ',', '.'); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td rowspan=2><?php echo date('d/m/Y', strtotime($compra->data_venda)); ?></td>
                        <td>P</td>
                        <td><?php echo number_format($compra->peso, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($compra->cotacao_peso, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($compra->peso * $compra->cotacao_peso, 2, ',', '.'); ?></td>
                        <td rowspan=2><?php echo 'R$ ' . number_format(($compra->peso * $compra->cotacao_peso) + ($compra->fator * $compra->cotacao_fator), 2, ',', '.'); ?></td>
                        <td rowspan=2>
                            <?php $__currentLoopData = $parcelas->where('venda_id', $compra->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($parcela->forma_pagamento); ?>: <?php echo date('d/m/Y', strtotime($parcela->data_parcela)); ?> - <?php echo 'R$ ' . number_format($parcela->valor_parcela, 2, ',', '.'); ?></li>                                
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <li>Total: <?php echo 'R$ ' . number_format($parcelas->where('venda_id', $compra->id)->sum('valor_parcela'), 2, ',', '.'); ?></li>
                        </td>
                    </tr>
                    <tr>
                        <td class='fator'>F</td>
                        <td class='fator'><?php echo number_format($compra->fator, 1, ',', '.'); ?></td>
                        <td class='fator'><?php echo 'R$ ' . number_format($compra->cotacao_fator, 2, ',', '.'); ?></td>
                        <td class='fator'><?php echo 'R$ ' . number_format($compra->fator * $compra->cotacao_fator, 2, ',', '.'); ?></td>
                    </tr>
                <?php endif; ?>
                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=7>Nenhum registro</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan=6>Total</td>
                <td><?php echo 'R$ ' . number_format($parcelas->sum('valor_parcela'), 2, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>


</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/cliente/pdf/pdf_historico_cliente.blade.php ENDPATH**/ ?>