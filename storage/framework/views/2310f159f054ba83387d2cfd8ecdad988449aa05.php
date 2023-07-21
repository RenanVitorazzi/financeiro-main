<?php $__env->startSection('title'); ?>
<?php echo e($cliente->pessoa->nome); ?> 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('clientes.index')); ?>">Clientes</a></li>
        <li class="breadcrumb-item active"><a href="<?php echo e(route('clientes.show', $cliente->id)); ?>"><?php echo e($cliente->pessoa->nome); ?></a></li>
        
    </ol>
</nav>
<div class='mb-2 d-flex justify-content-between'>
    <h3 class='d-inline' style="color:#212529">Histórico - <?php echo e($cliente->pessoa->nome); ?> </h3> 
    <?php if (isset($component)) { $__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoImprimir::class, []); ?>
<?php $component->withName('botao-imprimir'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['href' => ''.e(route('pdf_historico_cliente', $cliente->id)).'','target' => '_blank']); ?>
    
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5)): ?>
<?php $component = $__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5; ?>
<?php unset($__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5); ?>
<?php endif; ?>
</div>
    <?php if(Session::has('message')): ?>
        <p class="alert alert-success"><?php echo e(Session::get('message')); ?></p>
    <?php endif; ?>
  
    
    
  
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
            <th>Data</th>
            <th>Pagamento</th>
            <th>Compra</th>
            <th>Cotação</th>
            <th>Valor da Venda</th>
            <th>Pagamento</th>
            <th>Prazo Médio</th>
            <th>Ações</th>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $vendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e(date('d/m/Y', strtotime($venda->data_venda))); ?></td>
                <td><?php echo e($venda->metodo_pagamento); ?></td>   
                <td>
                    Peso: <?php echo number_format($venda->peso, 2, ',', '.'); ?>
                    <br>
                    Fator: <?php echo number_format($venda->fator, 1, ',', '.'); ?>
                </td>
                <td>
                    Peso: <?php echo 'R$ ' . number_format($venda->cotacao_peso, 2, ',', '.'); ?>
                    <br>
                    Fator: <?php echo 'R$ ' . number_format($venda->cotacao_fator, 2, ',', '.'); ?>
                </td>   
                <td><?php echo 'R$ ' . number_format($venda->valor_total, 2, ',', '.'); ?></td>
                <td>
                    <?php $__currentLoopData = $venda->parcela; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <?php echo e($parcela->forma_pagamento); ?> -
                            <?php echo date('d/m/Y', strtotime($parcela->data_parcela)); ?> (<?php echo e(\Carbon\Carbon::parse($parcela->data_parcela)->diffInDays($venda->data_venda)); ?>)- 
                            <?php echo 'R$ ' . number_format($parcela->valor_parcela, 2, ',', '.'); ?> 
                            
                        </div>
                        <?php
                            $dias = \Carbon\Carbon::parse($parcela->data_parcela)->diffInDays($venda->data_venda);
                            $totalPrazo += $dias;
                        ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td>
                    <div><?php echo e(number_format($totalPrazo / $venda->parcela->count(), 2)); ?></div>
                    <?php
                        $totalPrazo = 0;    
                    ?>
                </td>
                <td>
                    <?php if (isset($component)) { $__componentOriginal13702a75d66702067dad623af293364e28e151a7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoEditar::class, []); ?>
<?php $component->withName('botao-editar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['href' => ''.e(route("venda.edit", $venda->id)).'']); ?> <?php echo $__env->renderComponent(); ?>
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
<?php $component->withAttributes(['action' => ''.e(route("venda.destroy", $venda->id)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247)): ?>
<?php $component = $__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247; ?>
<?php unset($__componentOriginalc7dfdfe339a23ddfcb22882c80952c28748ef247); ?>
<?php endif; ?>
                </td>
            </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="8" class="table-danger">Nenhum registro criado</td></tr>
            <?php endif; ?>
        </tbody>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
<script>
    $(document).ready( function () {
        $('table').DataTable();
    } );
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/cliente/show.blade.php ENDPATH**/ ?>