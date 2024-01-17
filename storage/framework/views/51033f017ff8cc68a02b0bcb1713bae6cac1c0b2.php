
<?php $__env->startSection('title'); ?>
Nova despesa fixa
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('cadastros_auxiliares')); ?>">Cadastros Auxiliares</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('despesas_fixas.index')); ?>">Despesas Fixas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nova despesa fixa</li>
    </ol>
</nav>

<form method="POST" action="<?php echo e(route('despesas_fixas.store')); ?>">
    <?php echo csrf_field(); ?>
    <h5>Nova despesa fixa</h5>
    <div class="card mb-2">
        <div class="card-body">

            <div class="row">
                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'nome','autofocus' => true,'value' => ''.e(old('nome')).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'nome','autofocus' => true,'value' => ''.e(old('nome')).'']); ?>Nome <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>

                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'data_quitacao','type' => 'date','value' => ''.e(old('data_quitacao')).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'data_quitacao','type' => 'date','value' => ''.e(old('data_quitacao')).'']); ?>Data da quitação <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>

                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'dia_vencimento','type' => 'number','value' => ''.e(old('dia_vencimento')).'']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'dia_vencimento','type' => 'number','value' => ''.e(old('dia_vencimento')).'']); ?>Dia vencimento <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>

                <div class="col-4">
                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'valor','value' => ''.e(old('valor')).'','type' => 'number']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'valor','value' => ''.e(old('valor')).'','type' => 'number']); ?>Valor <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="local_id">Local da despesa</label>
                        <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'local_id','required' => true]); ?>
                            <option value=""></option>
                            <?php $__currentLoopData = $locais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $local): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($local->id); ?>" <?php echo e(old('local_id') == $local->id ? 'selected' : ''); ?>><?php echo e($local->nome); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
                    </div>
                </div>

                <div class="col-12">
                    <label for="observacao">Observação</label>
                    <?php if (isset($component)) { $__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TextArea::class, []); ?>
<?php $component->withName('text-area'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'observacao']); ?><?php echo e(old('observacao')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405)): ?>
<?php $component = $__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405; ?>
<?php unset($__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405); ?>
<?php endif; ?>
                </div>
                
            </div>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <input type="submit" class='btn btn-success'>
</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/despesas_fixas/create.blade.php ENDPATH**/ ?>