<?php $__env->startSection('body'); ?>
<?php if(session('status')): ?>
    <div class="alert alert-success" role="alert">
        <?php echo e(session('status')); ?>

    </div>
<?php endif; ?>
<?php $__env->startSection('title'); ?>
Home
<?php $__env->stopSection(); ?>


<?php if(count($depositos) > 0): ?>
    <div class="table-responsive">
        <?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Tableheader::class, []); ?>
<?php $component->withName('tableheader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
                <th colspan=5>
                    Cheques para depósito
                    
                </th>
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
                <th>#</th>
                <th>Titular</th>
                <th>Data do cheque</th>
                <th>Valor</th>
                <th>Representante</th>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477)): ?>
<?php $component = $__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477; ?>
<?php unset($__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477); ?>
<?php endif; ?>
            <tbody>

            <?php $__empty_1 = true; $__currentLoopData = $depositos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cheque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($loop->index + 1); ?></td>
                    <td><?php echo e($cheque->nome_cheque); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></td>
                    <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                    <td><?php echo e($cheque->representante->pessoa->nome ?? '-'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=5>Nenhum cheque para depósito!</td>
                </tr>
            <?php endif; ?>
            </tbody>
            <?php if($depositos): ?>
            <tfoot class="thead-dark">
                <th >Total</th>
                <th colspan=4><?php echo 'R$ ' . number_format($depositos->sum('valor_parcela'), 2, ',', '.'); ?></th>
            </tfoot>
            <?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
    </div>
<?php endif; ?>

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
<?php $component->withAttributes([]); ?>
        <th colspan=8>Prorrogações
            <a style='float:right' class='btn btn-light' href="<?php echo e(route('pdf_prorrogacao', 0)); ?>" target='_blank'>
                Impresso
            </a>
        </th>
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
        <th>#</th>
        <th>Titular</th>
        <th>Valor</th>
        <th>Data</th>
        <th>Adiado para</th>
        <th>Número</th>
        <th>Representante</th>
        <th>Parceiro</th>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477)): ?>
<?php $component = $__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477; ?>
<?php unset($__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477); ?>
<?php endif; ?>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $adiamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cheque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><?php echo e($loop->index + 1); ?></td>
            <td><?php echo e($cheque->nome_cheque); ?></td>
            <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
            <td><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></td>
            <td><?php echo date('d/m/Y', strtotime($cheque->adiamentos->nova_data)); ?></td>
            <td><?php echo e($cheque->numero_cheque); ?></td>
            <td><?php echo e($cheque->representante->pessoa->nome); ?></td>
            <td><?php echo e($cheque->parceiro->pessoa->nome ?? 'Carteira'); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan=8>Nenhum cheque adiado!</td>
        </tr>
    <?php endif; ?>
    </tbody>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'ops']); ?>
    <?php if (isset($component)) { $__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Tableheader::class, []); ?>
<?php $component->withName('tableheader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
        <th colspan=6>Ordens de pagamentos dessa semana</th>
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
        <th>#</th>
        <th>Cliente</th>
        <th>Data</th>
        <th>Valor</th>
        <th>Representante</th>
        <th>Pagamentos</th>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477)): ?>
<?php $component = $__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477; ?>
<?php unset($__componentOriginalfb92ff36a55f0dcdf5fe1bf02e275a6bc7af5477); ?>
<?php endif; ?>
    <tbody>
    <?php $__empty_1 = true; $__currentLoopData = $ops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ordem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr <?php echo e($ordem->data_parcela < \Carbon\Carbon::now()->format('Y-m-d') ? "class=table-danger" : ''); ?>>
            <td><?php echo e($loop->index + 1); ?></td>
            <td><?php echo e($ordem->venda->cliente->pessoa->nome ?? $ordem->nome_cheque); ?></td>
            <td><?php echo date('d/m/Y', strtotime($ordem->data_parcela)); ?></td>
            <td><?php echo 'R$ ' . number_format($ordem->valor_parcela, 2, ',', '.'); ?></td>
            <td><?php echo e($ordem->representante->pessoa->nome); ?></td>
            <td>
                <?php $__empty_2 = true; $__currentLoopData = $ordem->pagamentos_representantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                    <div><?php echo date('d/m/Y', strtotime($pagamento->data)); ?> <?php echo 'R$ ' . number_format($pagamento->valor, 2, ',', '.'); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                    <?php echo 'R$ ' . number_format(0, 2, ',', '.'); ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan=8>Nenhuma ordem de pagamento</td>
        </tr>
    <?php endif; ?>
    </tbody>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
<?php if(auth()->user()->is_admin): ?>
    <a class="btn btn-dark" target="_blank" href="<?php echo e(route('pdf_diario2')); ?>">Impresso diário</a>
    <a class="btn btn-dark" target="_blank" href="<?php echo e(route('pdf_mov_diario', \Carbon\Carbon::now()->format('Y-m-d'))); ?>">Movimentação diária</a>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $("#copiarAdiamentos").click( () => {
        copyToClipboard()

        toastr.success('Adiamentos copiados')
    })

    function copyToClipboard() {
        let msg = '<b>PRORROGAÇÕES</b><br><br>';

        $("#adiamentos > tbody > tr").each( (index, element) => {

            var nome = $(element).children("td").eq(1).html()
            var valor = $(element).children("td").eq(2).html()
            var data = $(element).children("td").eq(3).html()
            var nova_data = $(element).children("td").eq(4).html()

            msg += `Titular: ${nome} <br>Valor: ${valor} <br>${data} para ${nova_data}<br><br>`
        });

        let aux = document.createElement("div");
        aux.setAttribute("contentEditable", true);
        aux.innerHTML = msg;
        aux.setAttribute("onfocus", "document.execCommand('selectAll', false, null)");
        document.body.appendChild(aux);
        aux.focus();
        document.execCommand("copy");
        document.body.removeChild(aux);
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/home.blade.php ENDPATH**/ ?>