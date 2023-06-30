<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relação de cheques nos parceiros</title>
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
    th {
        background-color: #d6d8db;
    }
    h3 {
        text-align: center;
        margin: 0px;
    }
    .titular {
        font-size: 10px;
        text-align: left;
        padding-left: 5px
    }
    p {
        margin: 0;
    }
</style>
<body>
    <h3>CHEQUES NOS PARCEIROS - <?php echo e($representante->pessoa->nome); ?> <?php echo date('d/m/Y', strtotime($hoje)); ?></h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Titular</th>
                <th>Número</th>
                <th>Valor cheque</th>
                <th>Parceiro</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $cheques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cheque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <?php if($cheque->adiamentos): ?>
                            <s><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></s><br>
                            <?php echo date('d/m/Y', strtotime($cheque->adiamentos->nova_data)); ?>
                        <?php else: ?>
                            <?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?>
                        <?php endif; ?>
                    </td>
                    <td class='titular'><?php echo e($cheque->nome_cheque); ?></td>
                    <td><?php echo e($cheque->numero_cheque); ?></td>
                    <td><?php echo e($cheque->parceiro->pessoa->nome ?? 'Carteira'); ?></td>
                    <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                    
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=5>Nenhum registro</td>
                </tr>
            <?php endif; ?>
            <tfoot>
                <tr>
                    <td colspan=4><b>Total</b></td>
                    <td><b><?php echo 'R$ ' . number_format($cheques->sum('valor_parcela'), 2, ',', '.'); ?></b></td>
                </tr>
            </tfoot>
        </tbody>

    </table>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/representante/pdf/pdf_cheques_devolvidos_parceiros.blade.php ENDPATH**/ ?>