<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carteira de cheques</title>
</head>
<style>
    table {
        width:50%;
        border-collapse: collapse;
        font-size:9px;
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
        background-color: #dadada;
    }
    h1 {
        text-align: center;
    }
</style>
<table class="grande">
    <thead>
        <tr>
            <th colspan = 2>Carteira <?php echo e(\Carbon\Carbon::now()->format('d/m/Y')); ?></th>
        </tr>
        <tr>
            <th>Mês</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $carteira; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $carteira_mensal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          
            <tr>
                <td><?php echo e($carteira_mensal->month); ?>/<?php echo e($carteira_mensal->year); ?></td>
                <td><?php echo 'R$ ' . number_format($carteira_mensal->total_mes, 2, ',', '.'); ?></td>
            </tr>
        
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan=2>Nenhum registro</td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td><b>Total</b></td>
            <td><b><?php echo 'R$ ' . number_format($totalCarteira[0]->totalCarteira, 2, ',', '.'); ?></b></td>
        </tr>
    </tfoot>
</table>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/cheque/pdf/carteira.blade.php ENDPATH**/ ?>