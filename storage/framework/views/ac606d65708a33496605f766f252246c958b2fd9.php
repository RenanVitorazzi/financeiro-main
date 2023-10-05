<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PRORROGAÇÕES E RESGATES <?php echo date('d/m/Y', strtotime($dia)); ?></title>
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

    td:first-child {
        width:15%;
    }

    td:nth-child(2) {
        width:40%;
    }    
</style>
<body>

<?php $__empty_1 = true; $__currentLoopData = $cheques->groupBy('parceiro_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parceiro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

    <?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'adiamentos']); ?>
        <?php if (isset($component)) { $__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Tableheader::class, []); ?>
<?php $component->withName('tableheader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'copiarAdiamentos']); ?>
            <th colspan=5><?php echo e($parceiro->where('parceiro_id', $parceiro->first()->parceiro_id)->first()->parceiro->pessoa->nome); ?></th>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477)): ?>
<?php $component = $__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477; ?>
<?php unset($__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Tableheader::class, []); ?>
<?php $component->withName('tableheader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
            <th>STATUS</th>
            <th>TITULAR</th>
            <th>VALOR</th>
            <th>DATA</th>
            <th>PARA</th>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477)): ?>
<?php $component = $__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477; ?>
<?php unset($__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477); ?>
<?php endif; ?>
        <tbody>
        <?php $__empty_2 = true; $__currentLoopData = $cheques->where('parceiro_id', $parceiro->first()->parceiro_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cheque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
            <?php if($cheque->movimentacoes->first()->status == 'Adiado'): ?>
                <?php if($antigosAdiamentos->where('parcela_id', $cheque->id)->first()): ?>
                    <tr>
                        <td>PRORROGAÇÃO</td>
                        <td><?php echo e($cheque->nome_cheque); ?></td>
                        <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                        <td>
                            <s><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></s>
                            <p><?php echo date('d/m/Y', strtotime($antigosAdiamentos->where('parcela_id', $cheque->id)->first()->data)); ?></p>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($cheque->adiamentos->nova_data)); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td>PRORROGAÇÃO</td>
                        <td><?php echo e($cheque->nome_cheque); ?></td>
                        <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($cheque->adiamentos->nova_data)); ?></td>
                    </tr>
                <?php endif; ?>
            <?php elseif($cheque->movimentacoes->first()->status == 'Resgatado'): ?>
                <tr>
                    <td>RESGATE</td>
                    <td><?php echo e($cheque->nome_cheque); ?></td>
                    <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></td>
                    <td>-</td>
                </tr>
            <?php endif; ?>
            
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
            <tr>
                <td colspan=5>Nenhuma prorrogação!</td>
            </tr>
        <?php endif; ?>
        </tbody>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
    <br>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<h5>Nenhuma prorrogação ou resgate!</h5>
<?php endif; ?>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/adiamento/pdf/pdf_prorrogacao.blade.php ENDPATH**/ ?>