
<?php $__env->startSection('title'); ?>
Contas
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('cadastros_auxiliares')); ?>">Cadastros Auxiliares</a></li>
        <li class="breadcrumb-item active" aria-current="page">Despesas Fixas</li>
    </ol>
</nav>

<div class='mb-2 d-flex justify-content-between'>
    <h3> Despesas Fixas </h3>
    <?php if (isset($component)) { $__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoNovo::class, []); ?>
<?php $component->withName('botao-novo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['href' => ''.e(route('despesas_fixas.create')).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd)): ?>
<?php $component = $__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd; ?>
<?php unset($__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd); ?>
<?php endif; ?>
</div>

<?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'myTable']); ?>
    <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
        <tr>
            <th>Nome</th>
            <th>Data da quitação</th>
            <th>Dia do vencimento</th>
            <th>Valor</th>
            <th>Local</th>
            <th>Ações</th>
        </tr>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $despesas_fixas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $despesa_fixa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($despesa_fixa->nome); ?></td>
            <td><?php echo e($despesa_fixa->data_quitacao ? \Carbon\Carbon::parse($despesa_fixa->data_quitacao)->format('d/m/Y') : '-'); ?></td>
            <td><?php echo e($despesa_fixa->dia_vencimento); ?></td>
            <td><?php echo 'R$ ' . number_format($despesa_fixa->valor, 2, ',', '.'); ?></td>
            <td><?php echo e($despesa_fixa->local->nome); ?></td>
            <td><?php if (isset($component)) { $__componentOriginal13702a75d66702067dad623af293364e28e151a7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoEditar::class, []); ?>
<?php $component->withName('botao-editar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['href' => ''.e(route('despesas_fixas.edit', $despesa_fixa)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal13702a75d66702067dad623af293364e28e151a7)): ?>
<?php $component = $__componentOriginal13702a75d66702067dad623af293364e28e151a7; ?>
<?php unset($__componentOriginal13702a75d66702067dad623af293364e28e151a7); ?>
<?php endif; ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan=6>Nenhum registro!</td>
        </tr>
        <?php endif; ?>
    </tbody>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    <?php if(Session::has('message')): ?>
        toastr["success"]("<?php echo e(Session::get('message')); ?>")
    <?php endif; ?>

    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/despesas_fixas/index.blade.php ENDPATH**/ ?>