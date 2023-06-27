<?php $__env->startSection('title'); ?>
Novo Anexo
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <?php if(!auth()->user()->is_representante): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('representantes.index')); ?>">Representantes</a></li>
        <?php endif; ?>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('conta_corrente_representante.show', $contaCorrente->representante_id)); ?>">
                Conta Corrente <?php echo e($contaCorrente->representante->pessoa->nome); ?> 
            </a>
        </li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('ccr_anexo.index',['id' => $contaCorrente->id])); ?>">Anexos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Novo Anexo</li>
    </ol>
</nav>

<form method="POST" action="<?php echo e(route('ccr_anexo.store')); ?>" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <input type="hidden" name="conta_corrente_id" value="<?php echo e($contaCorrente->id); ?>">
    
    <div class="form-group">
        <label for="anexo">Anexo de Arquivo</label>
        <input type="file" id="anexo" name="anexo[]" class="form-control-file" multiple required>
    </div>
    
    <input type="submit" class='btn btn-success'>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/conta_corrente_representante_anexo/create.blade.php ENDPATH**/ ?>