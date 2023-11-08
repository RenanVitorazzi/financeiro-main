<?php $__env->startSection('title'); ?>
Vendas - <?php echo e($representante->pessoa->nome); ?> 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
<style>
    
</style>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <?php if(!auth()->user()->is_representante): ?>
            <li class="breadcrumb-item"><a href="<?php echo e(route('representantes.index')); ?>">Representantes</a></li>    
        <?php endif; ?>
        <li class="breadcrumb-item active" aria-current="page">Acertos</li>
    </ol>
</nav>
<div class='mb-2 d-flex justify-content-between'>
    <h3>Acertos - <?php echo e($representante->pessoa->nome); ?></h3> 
    <div> 
        <?php if (isset($component)) { $__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoImprimir::class, []); ?>
<?php $component->withName('botao-imprimir'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'mr-2','href' => ''.e(route('pdf_acerto_documento', ['representante_id' => $representante->id])).'']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5)): ?>
<?php $component = $__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5; ?>
<?php unset($__componentOriginale7af6f5f93c3f23c2bd6667675861a3352692bb5); ?>
<?php endif; ?>
        
    </div>
</div>
<div class="row ">
<?php $__currentLoopData = $acertos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acerto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class='col-6'>
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
                <th colspan=7><?php echo e($acerto->cliente); ?></th>
            </tr>
            <tr>
                <th class="vencimento">Vencimento</th>
                <th>Status</th>
                <th>Forma</th>
                <th>Valor</th>
                <th>Pagamentos</th>
                <th>Total Aberto</th>
                <th></th>
            </tr>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
        <tbody>
        <?php
            $sql = DB::select( "SELECT
                    p.data_parcela as vencimento,
                    p.valor_parcela AS valor,
                    p.status,
                    p.forma_pagamento,
                    (SELECT nome from pessoas WHERE id = r.pessoa_id) as representante,
                    SUM(pr.valor) AS valor_pago,
                    p.id as parcela_id
                FROM
                    vendas v
                        INNER JOIN
                    parcelas p ON p.venda_id = v.id
                        LEFT JOIN clientes c ON c.id = v.cliente_id
                        LEFT JOIN representantes r ON r.id = v.representante_id
                        LEFT JOIN pagamentos_representantes pr ON pr.parcela_id = p.id
                WHERE
                    p.deleted_at IS NULL
                    AND v.deleted_at IS NULL
                    AND r.id = ?
                    AND (
                    p.forma_pagamento like 'Cheque' AND p.status like 'Aguardando Envio'
                    OR
                    p.forma_pagamento != 'Cheque' AND p.status != 'Pago'
                    )
                    AND pr.deleted_at IS NULL
                    AND pr.baixado IS NULL
                    AND c.id = ?
                GROUP BY p.id
                ORDER BY c.pessoa_id, data_parcela , valor_parcela",
                [$representante_id, $acerto->cliente_id]
            );

            $cliente_valor = 0;
            $cliente_valor_pago = 0;
        ?>
            <?php $__currentLoopData = $sql; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $divida): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $pgtos = DB::select( "SELECT c.nome as conta_nome, pr.*
                        FROM pagamentos_representantes pr
                        INNER JOIN contas c ON c.id=pr.conta_id
                        WHERE pr.parcela_id = ?
                            AND pr.baixado IS NULL
                            AND pr.deleted_at IS NULL",
                        [$divida->parcela_id]
                    );
                    $cliente_valor += $divida->valor;
                    $cliente_valor_pago += $divida->valor_pago;

                    $total_divida_valor += $divida->valor;
                    $total_divida_valor_pago += $divida->valor_pago;
                ?>
                <tr class="<?php echo e($divida->vencimento < $hoje ? 'table-danger' : ''); ?>">
                    <td><?php echo date('d/m/Y', strtotime($divida->vencimento)); ?></td>
                    <td class='status'><?php echo e($divida->status); ?></td>
                    <td><?php echo e($divida->forma_pagamento == 'Transferência Bancária' ? 'Op' : $divida->forma_pagamento); ?></td>
                    <td><?php echo 'R$ ' . number_format($divida->valor, 2, ',', '.'); ?></td>
                    <td class='pagamentos'>
                        <?php $__currentLoopData = $pgtos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pgto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="linha-pagamento">
                                <?php echo date('d/m/Y', strtotime($pgto->data)); ?> - <?php echo 'R$ ' . number_format($pgto->valor, 2, ',', '.'); ?> (<?php echo e($pgto->conta_nome); ?>)
                                <b><?php echo e($pgto->confirmado ? '' : 'PAGAMENTO NÃO CONFIRMADO'); ?></b>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td><?php echo 'R$ ' . number_format($divida->valor - $divida->valor_pago, 2, ',', '.'); ?></td>
                    <td>
                        <?php if($divida->forma_pagamento == 'Transferência Bancária'): ?>
                            <a class="btn btn-dark" target='_blank' href="<?php echo e(route('criarRecebimentoPeloAcerto', $divida->parcela_id)); ?>">
                                <i class="fas fa-plus" ></i>
                            </a>
                        <?php elseif($divida->status == 'Aguardando Envio'): ?>
                            <a class="btn btn-dark" target='_blank' href="<?php echo e(route('cheques.edit', $divida->parcela_id)); ?>">
                                <i class="fas fa-pencil-alt" ></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan=3><b>TOTAL</b></td>
                <td><?php echo 'R$ ' . number_format($cliente_valor, 2, ',', '.'); ?></td>
                <td><?php echo 'R$ ' . number_format($cliente_valor_pago, 2, ',', '.'); ?></td>
                <td><b><?php echo 'R$ ' . number_format($cliente_valor - $cliente_valor_pago, 2, ',', '.'); ?></b></td>
            </tr>
        </tfoot>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
   
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/venda/acertos_representante.blade.php ENDPATH**/ ?>