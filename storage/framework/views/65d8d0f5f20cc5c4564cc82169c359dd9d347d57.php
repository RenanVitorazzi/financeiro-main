
<?php $__env->startSection('title'); ?>
Cadastros Auxiliares
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<div class='mb-2 d-flex justify-content-between'>
    <h3>Cadastros Auxiliares</h3>  
    <div>
        <?php if (isset($component)) { $__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoNovo::class, []); ?>
<?php $component->withName('botao-novo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['href' => ''.e(route('parceiros.create')).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd)): ?>
<?php $component = $__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd; ?>
<?php unset($__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd); ?>
<?php endif; ?>
    </div>
</div>
<ul class="d-flex list-group list-group">
    <?php $__currentLoopData = $modulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class='list-group-item d-flex justify-content-between'>
            <div class='mt-2'>
                <?php echo e($key); ?> 
            </div>
            <a href=<?php echo e(route($modulo . '.index')); ?> class="btn btn-dark"><i class="fas fa-cog"></i></a>
        </li>
        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
 
</ul>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/cadastros_auxiliares.blade.php ENDPATH**/ ?>