
<?php $__env->startSection('title'); ?>
Relatório PIX BRADESCO
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<style>
    .pointer{
        cursor: pointer;
    }
</style>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('import')); ?>">Importação</a></li>
        <li class="breadcrumb-item active" aria-current="page">PIX BRADESCO</li>
    </ol>
</nav>
<div class='mb-2 d-flex justify-content-between'>
    <h3> Relatório PIX BRADESCO </h3>
    <h5>Conta: <?php echo e($import->conta->nome); ?></h5>
</div>

<?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'table-light','id' => 'table-pix']); ?>
    <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>

        <tr>
            <th>#</th>
            <th>Data</th>
            <th>Descrição</th>
            <th>Débito</th>
            <th>Crédito</th>
            <th>Lançamentos</th>
        </tr>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $import->arrayDados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            
            <tr>
                <td><?php echo e($loop->iteration); ?></td>
                <td><?php echo date('d/m/Y', strtotime($item['data'])); ?></td>
                <td><?php echo e($item['nome']); ?></td>
                <?php if($item['tipo'] == 'Crédito'): ?>
                    <td></td>
                    <td><?php echo 'R$ ' . number_format($item['valor'], 2, ',', '.'); ?></td>
                    <td>
                        <?php $__empty_2 = true; $__currentLoopData = $item['pagamentosRepresentantes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            
                            <p>
                                
                                <?php if($pr->comprovante_id == $item['comprovante_id']): ?>
                                    <div class="alert alert-success pointer" >
                                        Pagamento relacionado pelo <b>PIX ID</b>
                                        <br>
                                        <i class='fas fa-check fa-lg mt-2'></i>
                                    </div>
                                <?php elseif($item['valor'] == $pr->valor && $item['data'] == $pr->data && $pr->comprovante_id == NULL): ?>
                                    <div class="alert alert-warning" >
                                        Pagamento relacionado pela <b>data</b> e pelo <b>valor</b>
                                        <br>
                                        <span class='btn btn-warning mt-2'>Relacionar por PIX ID</span>
                                        
                                    </div>
                                <?php endif; ?>
                            </p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <span class='btn btn-dark'>
                                
                                <span>Lançar pagamento <i class='fas fa-plus ml-2'></i></span>
                            </span>
                            <span class='btn btn-danger'>
                                <span>Ignorar <i class='fas fa-trash ml-2'></i></span>
                            </span>
                        <?php endif; ?>

                    </td>
                <?php elseif($item['tipo'] == 'Débito'): ?>
                    <td><?php echo 'R$ ' . number_format($item['valor'], 2, ',', '.'); ?></td>
                    <td></td>
                    <td>
                        <?php $__empty_2 = true; $__currentLoopData = $item['pagamentosParceiros']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php dd($pr); ?>
                            <p>
                                <?php if($pr->comprovante_id == $item['comprovante_id']): ?>
                                    <div class="alert alert-success pointer" >
                                        Pagamento relacionado pelo <b>PIX ID</b>
                                        <br>
                                        <i class='fas fa-check fa-lg mt-2'></i>
                                    </div>
                                <?php elseif($item['valor'] == $pr->valor && $item['data'] == $pr->data && $pr->comprovante_id == NULL): ?>
                                    <div class="alert alert-warning" >
                                        Pagamento relacionado pela <b>data</b> e pelo <b>valor</b>
                                        <br>
                                        <span class='btn btn-warning mt-2'>Relacionar por PIX ID</span>
                                        
                                    </div>
                                <?php endif; ?>
                            </p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            
                            <span class='btn btn-dark'>
                                <span>Lançar pagamento <i class='fas fa-plus ml-2'></i></span>
                            </span>
                            <span class='btn btn-danger'>
                                <span>Ignorar <i class='fas fa-trash ml-2'></i></span>
                            </span>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan=5>Nenhum registro</td>
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
    $(".botaoIgnorar").each( (index, element) => {
        $(element).click( (botao) => {
            $(botao.currentTarget).parent().parent().fadeOut( "slow" );
            console.log($(botao.currentTarget).parent().parent())
        })
    })

    $(".botaoCriarRegistro").each( (index, element) => {
        $(element).click( (elementoBotao) => {
            let botao = $(elementoBotao.currentTarget)

            console.log($(botao.currentTarget).parent().parent())
        })
    })
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/importacao/relatorioPixBradesco.blade.php ENDPATH**/ ?>