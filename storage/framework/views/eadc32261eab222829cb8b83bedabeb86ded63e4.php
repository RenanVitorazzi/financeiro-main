<?php $__env->startSection('title'); ?>
Estoque
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>

<div class='mb-2 d-flex justify-content-between'>
    <h3> Estoque </h3>
    <div>

        <?php if (isset($component)) { $__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoImprimir::class, []); ?>
<?php $component->withName('botao-imprimir'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'mr-2','href' => ''.e(route('pdf_estoque', ['tipo' => 1])).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5)): ?>
<?php $component = $__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5; ?>
<?php unset($__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoNovo::class, []); ?>
<?php $component->withName('botao-novo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['href' => ''.e(route('estoque.create')).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd)): ?>
<?php $component = $__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd; ?>
<?php unset($__componentOriginale4c265d4ffee8fab925ff5f69279324cd3ba69cd); ?>
<?php endif; ?>
    </div>
</div>
<?php if(count($lancamentos_pendentes) > 0): ?>
    <h5 class="alert alert-warning">
        Você tem <?php echo e(count($lancamentos_pendentes)); ?> lançamentos pendentes
        <div class="btn btn-warning btn-lancar">Lançar</div>
    </h5>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'tabelaBalanco']); ?>
    <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
        <tr>
            <th>Data</th>
            <th>Balanço</th>
            <th>Peso/Fator</th>
            <th>Fornecedor/Representante</th>
            <th>Saldo</th>
            <th>Ações</th>
        </tr>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $lancamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lancamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($lancamento->data)); ?></td>

                <td class=<?php echo e($lancamento->balanco_estoque == 'Débito' ? 'text-danger': 'text-success'); ?>>
                    <b><?php echo e($lancamento->balanco_estoque); ?></b>
                </td>
                <td><?php echo number_format($lancamento->peso, 2, ',', '.'); ?> <br> <?php echo number_format($lancamento->fator, 1, ',', '.'); ?></td>
                <td>
                    <?php if($lancamento->representante_id): ?>
                        <?php echo e($lancamento->balanco_representante); ?>

                        <?php echo e($lancamento->nome_representante); ?>

                        <?php echo e($lancamento->observacao_representante); ?>

                    <?php elseif($lancamento->fornecedor_id): ?>
                        <?php echo e($lancamento->balanco_fornecedor); ?>

                        <?php echo e($lancamento->nome_fornecedor); ?>

                        <?php echo e($lancamento->observacao_fornecedor); ?>

                    <?php else: ?>
                        <?php echo e($lancamento->observacao); ?>

                    <?php endif; ?>
                </td>
                <td><?php echo number_format($lancamento->saldo_peso, 2, ',', '.'); ?> <br> <?php echo number_format($lancamento->saldo_fator, 1, ',', '.'); ?></td>
                <td>
                    <?php if($lancamento->representante_id != NULL && $lancamento->representante_atacado != NULL): ?>
                        <?php if (isset($component)) { $__componentOriginal13702a75d66702067dad623af293364e28e151a7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoEditar::class, []); ?>
<?php $component->withName('botao-editar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'mr-2','href' => ''.e(route('estoque.edit', $lancamento->id)).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal13702a75d66702067dad623af293364e28e151a7)): ?>
<?php $component = $__componentOriginal13702a75d66702067dad623af293364e28e151a7; ?>
<?php unset($__componentOriginal13702a75d66702067dad623af293364e28e151a7); ?>
<?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan=6>Nenhum registro</td>
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
    const LANCAMENTOS_PENDENTES = <?php echo json_encode($lancamentos_pendentes, 15, 512) ?>;

    let tbody = ``;

    $(LANCAMENTOS_PENDENTES).each( (index, element) => {
        let conta_corrente_id = element.id
        let tabela = element.tabela
        let route = "<?php echo e(route('lancar_cc_estoque', [':conta_corrente_id',':tabela'])); ?>"
        route = route.replace(':conta_corrente_id', conta_corrente_id);
        route = route.replace(':tabela', tabela);

        tbody += `
            <tr>
                <td>${element.data_tratada}</td>
                <td>${element.nome}</td>
                <td>${element.peso}</td>
                <td>
                    <a class="btn btn-dark" href="${route}">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
        `;
    })

    $(".modal-body").html(`
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
                <tr>
                    <th>Data</th>
                    <th>Nome</th>
                    <th>Lançamento (g)</th>
                    <th></th>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
            <tbody>
                ${tbody}
            </tbody>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
    `);

    $(document).ready( function () {
        $('#tabelaBalanco').dataTable( {
            "ordering": false
        } );
    } );

    $('.btn-lancar').click( () => {
        $("#modal2").modal('show')

        $(".modal-header").text(`Lançamentos Pendentes`)
        $(".modal-footer > .btn-primary").remove()
    })
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/estoque/index.blade.php ENDPATH**/ ?>