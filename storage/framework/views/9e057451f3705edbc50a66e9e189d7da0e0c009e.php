<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($troca->titulo); ?></title>
</head>
<style>
    * {
        margin-top: 10px;
    }
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
        background-color:black;
        color:white;
    }

    tr:nth-child(even) {
        background-color: #c9ced4;
    }

    h3 {
        text-align:center;
    }
    .nome {
        font-size: 10px;
        text-align: left;
        padding-left: 5px;
    }
</style>
<body>
<h3>
    <?php echo e($troca->titulo); ?> - <?php echo date('d/m/Y', strtotime($troca->data_troca)); ?> (Taxa: <?php echo e($troca->taxa_juros); ?>%)
</h3>


<?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
    <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
        <tr>
            <th>Nome</th>
            <th>Data</th>
            <th>Dias</th>
            <th>Valor Bruto</th>
            <th>Juros</th>
            <th>Valor líquido</th>
        </tr> 
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
    <tbody>
        <?php $__currentLoopData = $cheques; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cheque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class='nome'><?php echo e($cheque->nome_cheque); ?></td>
                <td><?php echo date('d/m/Y', strtotime($cheque->data)); ?></td>
                <td><?php echo e($cheque->dias); ?></td>
                <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                <td><?php echo 'R$ ' . number_format($cheque->valor_juros, 2, ',', '.'); ?></td>
                <td><?php echo 'R$ ' . number_format($cheque->valor_liquido, 2, ',', '.'); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan=3><b>Total</b></td>
            <td><b><?php echo 'R$ ' . number_format($troca->valor_bruto, 2, ',', '.'); ?></b></td>
            <td><b><?php echo 'R$ ' . number_format($troca->valor_juros, 2, ',', '.'); ?></b></td>
            <td><b><?php echo 'R$ ' . number_format($troca->valor_liquido, 2, ',', '.'); ?></b></td>
        </tr>
    </tfoot>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
    <tr>
        <td>Total Bruto</td>
        <td>Total Juros</td>
        <td>Prazo Médio</td>
        <td>Juros (%)</td>
        <td>Total Líquido</td>
    </tr>
    <tr>
        <td><b><?php echo 'R$ ' . number_format($troca->valor_bruto, 2, ',', '.'); ?></b></td>
        <td><b><?php echo 'R$ ' . number_format($troca->valor_juros, 2, ',', '.'); ?></b></td>
        <td><b><?php echo e(number_format($prazoMedio, 2)); ?></b></td>
        <td><b><?php echo e(number_format($troca->valor_juros/ $troca->valor_bruto, 4) * 100); ?>%</b></td>
        <td><b><?php echo 'R$ ' . number_format($troca->valor_liquido, 2, ',', '.'); ?></b></td>
    </tr>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
</body>
</html>
<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/troca_cheque/pdf/troca_cheque.blade.php ENDPATH**/ ?>