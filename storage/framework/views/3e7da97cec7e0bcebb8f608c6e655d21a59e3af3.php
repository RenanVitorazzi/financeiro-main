
<?php $__env->startSection('title'); ?>
Parceiros
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <?php if(auth()->user()->is_admin): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('parceiros.index')); ?>">Parceiros</a></li>
        <?php endif; ?>
        <li class="breadcrumb-item active">Extrato <?php echo e($parceiro->pessoa->nome); ?> </li>
    </ol>
</nav>
<div class='mb-2 d-flex justify-content-between'>
    <h3>Extrato <?php echo e($parceiro->pessoa->nome); ?></h3>  
    
</div>
<form action="<?php echo e(route('atualizar_conta_corrente', $parceiro->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('POST'); ?>
    <?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'tabela_parceiro']); ?>
        <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
            <tr>
                <th><input type='checkbox' id='selecionar_tudo'></th>
                <th>Data</th>
                <th>Titular</th>
                <th>Status</th>
                <th>Crédito</th>
                <th>Débito</th>
                <th>Saldo</th>
            </tr>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $saldos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saldo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    if($saldo->status == 'Crédito') {
                        $tr = 'credito';
                        $saldo_total = $saldo_total + $saldo->valor;
                    } else {
                        $tr = '';
                        $saldo_total -= $saldo->valor;
                    }
                    
                ?> 
                <?php if($saldo->status == 'Crédito'): ?>
                    <tr>
                        <td><input type='checkbox' name='<?php echo e($saldo->tabela); ?>[]' value='<?php echo e($saldo->id); ?>'></td>
                        <td><?php echo date('d/m/Y', strtotime($saldo->rank2)); ?></td>
                        <td class='nome'><?php echo e($saldo->nome_cheque); ?></td>
                        <td><?php echo e($saldo->status); ?></td>
                        <td><?php echo 'R$ ' . number_format($saldo->valor, 2, ',', '.'); ?></td>     
                        <td></td>      
                        <td><?php echo 'R$ ' . number_format($saldo_total, 2, ',', '.'); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td><input type='checkbox' name='<?php echo e($saldo->tabela); ?>[]' value='<?php echo e($saldo->id); ?>'></td>
                        <td><?php echo date('d/m/Y', strtotime($saldo->rank2)); ?></td>
                        <td class='nome'><?php echo e($saldo->nome_cheque); ?> - Nº <?php echo e($saldo->numero_cheque); ?>

                        </td>
                        <td><?php echo e($saldo->status); ?></td>
                        <td></td>    
                        <td><?php echo 'R$ ' . number_format($saldo->valor, 2, ',', '.'); ?></td>       
                        <td><?php echo 'R$ ' . number_format($saldo_total, 2, ',', '.'); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=6>Nenhum registro</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <?php
                if ($saldo_total < 0) {
                    $tfoot = 'debito';
                } else {
                    $tfoot = 'credito';
                }
            ?>
            <tr>
                <td colspan=4><b>TOTAL</b></td>
                <td colspan=2 class=<?php echo e($tfoot); ?>><b><?php echo 'R$ ' . number_format($saldo_total, 2, ',', '.'); ?></b></td>
            </tr>
        </tfoot>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>

    <button type='submit' class='btn btn-success'>
        Atualizar conta corrente
    </button>
</form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
$( document ).ready(function() {

    $("#selecionar_tudo").click( (e) => {
        
        let status = $(e.currentTarget).is(':checked') ? 'selected' : ''

        $('input[type="checkbox"]:not("#selecionar_tudo")').each(function () {    
            this.checked = status
        });
    })

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/parceiro/configurar_cc_parceiros.blade.php ENDPATH**/ ?>