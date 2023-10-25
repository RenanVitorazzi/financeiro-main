<?php $__env->startSection('title'); ?>
Editar despesa
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('despesas.index')); ?>">Despesas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar cadastro</li>
    </ol>
</nav>

<form method="POST" action="<?php echo e(route('despesas.update', $despesa->id)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <div class="row">
        <div class="col-6 form-group">
            <label for="nome">Nome da despesa</label>
            <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'nome','type' => 'text','value' => ''.e($despesa->nome).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
        </div>
        <div class="col-6 form-group">
            <label for="data_vencimento">Data do vencimento</label>
            <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'data_vencimento','type' => 'date','value' => ''.e($despesa->data_vencimento).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
        </div>
        <div class="col-6 form-group">
            <label for="valor">Valor</label>
            <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'valor','type' => 'text','value' => ''.e($despesa->valor).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
        </div>

        <div class="col-6 form-group">
            <label for="data_pagamento">Data do Pagamento</label>
            <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'data_pagamento','type' => 'date','value' => ''.e($despesa->data_pagamento).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
        </div>

        <div class="col-6 form-group">
            <label for="local_id">Local</label>
            <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'local_id']); ?>
                <option></option>
                <?php $__currentLoopData = $locais; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $local): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($local->id); ?>" <?php echo e($despesa->local_id == $local->id ? 'selected' : ''); ?>><?php echo e($local->nome); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
        </div>
        <div class="col-6 form-group">
            <label for="forma_pagamento">Forma de pagamento</label>
            <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'forma_pagamento']); ?>
                
                <?php $__currentLoopData = $formasPagamento; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($pagamento); ?>" <?php echo e($despesa->forma_pagamento == $pagamento ? 'selected' : ''); ?>><?php echo e($pagamento); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
        </div>

        <div class="col-6 form-group">
            <label for="comprovante_id">Comprovante ID</label>
            <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'comprovante_id','type' => 'text','value' => ''.e($despesa->comprovante_id).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
        </div>
        <div class="col-6 form-group">
            <label for="conta_id">Conta do Pagamento</label>
            <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'conta_id']); ?>
                <option></option>
                <?php $__currentLoopData = $contas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($conta->id); ?>" <?php echo e($despesa->conta_id == $conta->id ? 'selected' : ''); ?>><?php echo e($conta->nome); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
        </div>
        
        <div class="col-12 form-group">
            <label for="observacao">Observação</label>
            <?php if (isset($component)) { $__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TextArea::class, []); ?>
<?php $component->withName('text-area'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'observacao','type' => 'text','value' => ''.e($despesa->observacao).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405)): ?>
<?php $component = $__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405; ?>
<?php unset($__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405); ?>
<?php endif; ?>
        </div>

    </div>
    
    <input type="submit" class='btn btn-success'>
</form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/despesa/edit.blade.php ENDPATH**/ ?>