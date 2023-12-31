<?php $__env->startSection('title'); ?>
Parceiros
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<div class='mb-2 d-flex justify-content-between'>
    <h3>Parceiros</h3>  
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
    <?php $__empty_1 = true; $__currentLoopData = $parceiros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parceiro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <li class='list-group-item d-flex justify-content-between'>
            <div class='mt-2'>
                <?php echo e($parceiro->pessoa->nome); ?>

                <span class="text-muted"><?php echo e($parceiro->porcentagem_padrao); ?>%</span>
            </div>
            <div class='d-flex'>
                <?php if(auth()->user()->is_admin && auth()->user()->id == 1): ?>
                    <a href="<?php echo e(route('configurar_cc_parceiros', $parceiro->id)); ?>" class="btn btn-dark mr-2">
                        <span class='fas fa-cog'></span>
                    </a>
                <?php endif; ?>
                
                <?php if (isset($component)) { $__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoImprimir::class, []); ?>
<?php $component->withName('botao-imprimir'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'mr-2','href' => ''.e(route('pdf_cc_parceiro', $parceiro->id)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5)): ?>
<?php $component = $__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5; ?>
<?php unset($__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal13702a75d66702067dad623af293364e28e151a7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoEditar::class, []); ?>
<?php $component->withName('botao-editar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'mr-2','href' => ''.e(route('parceiros.edit', $parceiro->id)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal13702a75d66702067dad623af293364e28e151a7)): ?>
<?php $component = $__componentOriginal13702a75d66702067dad623af293364e28e151a7; ?>
<?php unset($__componentOriginal13702a75d66702067dad623af293364e28e151a7); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoExcluir::class, []); ?>
<?php $component->withName('botao-excluir'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['action' => ''.e(route('parceiros.destroy', $parceiro->id)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247)): ?>
<?php $component = $__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247; ?>
<?php unset($__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247); ?>
<?php endif; ?>
            </div>
        </li>
        
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <li class='list-group-item list-group-item-danger'>Nenhum registro criado!</li>
    <?php endif; ?>
</ul>
        
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    <?php if(Session::has('message')): ?>
        toastr["success"]("<?php echo e(Session::get('message')); ?>")
    <?php endif; ?>

    $(".btn-zerar").each( (index, element) => {
        let parceiro_id = $(element).data('id')

        $(element).click( (e) => {
            let parceiro_id = $(e.currentTarget).data('id')
            console.log(parceiro_id);
            swal.fire({
                title: 'Tem certeza de que deseja baixar o conta corrente?',
                icon: 'warning',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    zerarContaCorrente(parceiro_id)

                    Swal.fire({
                        title: 'Sucesso!',
                        icon: 'success'
                    })

                } else {
                    Swal.fire({
                        title: 'Cancelado!',
                        icon: 'warning'
                    })
                }
            })
        })
    })

    function zerarContaCorrente (parceiro_id) {
        
    }
   
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/parceiro/index.blade.php ENDPATH**/ ?>