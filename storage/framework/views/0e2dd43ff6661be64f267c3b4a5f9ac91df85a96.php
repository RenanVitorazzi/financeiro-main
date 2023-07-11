<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório de Vendas </title>
</head>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        /* page-break-inside: avoid; */
        /* page-break-before: no; */
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    th {
        background-color:black;
        color:white;
    }
    /* tr:nth-child(even) {
        background-color: #a9acb0;
    } */
    h1, h3 {
        margin-top: 0;
        text-align: center;
    }
    .nome {
        font-size:10px;
    }
    .fator {
        background-color: #a9acb0;
    }
</style>
<body>
    <h3>
        Relatório Vendas - <?php echo e($representante->pessoa->nome); ?> 
    </h3>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Cliente</th>
                <th colspan=5>Venda</th>
                <th>Total Compra</th>
                <th>Total Pago</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $vendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td rowspan=2><?php echo date('d/m/Y', strtotime($venda->data_venda)); ?></td>
                    <td rowspan=2 class='nome'><?php echo e(substr($venda->cliente->pessoa->nome,0,25)); ?></td>
                    <td rowspan=2><?php echo e($venda->metodo_pagamento); ?></td>
                    <td>P</td>
                    <td><?php echo number_format($venda->peso, 2, ',', '.'); ?></td>
                    <td><?php echo 'R$ ' . number_format($venda->cotacao_peso, 2, ',', '.'); ?></td>
                    <td><?php echo 'R$ ' . number_format($venda->peso * $venda->cotacao_peso, 2, ',', '.'); ?></td>
                    <td rowspan=2><?php echo 'R$ ' . number_format(($venda->peso * $venda->cotacao_peso) + ($venda->fator * $venda->cotacao_fator), 2, ',', '.'); ?></td>
                    <td rowspan=2><?php echo 'R$ ' . number_format($cheques->where('venda_id', $venda->id)->sum('valor_parcela'), 2, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td class='fator'>F</td>
                    <td class='fator'><?php echo number_format($venda->fator, 1, ',', '.'); ?></td>
                    <td class='fator'><?php echo 'R$ ' . number_format($venda->cotacao_fator, 2, ',', '.'); ?></td>
                    <td class='fator'><?php echo 'R$ ' . number_format($venda->fator * $venda->cotacao_fator, 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=7>Nenhum registro</td>
                </tr>
            <?php endif; ?>
            <tfoot>
                <tr>
                    <td rowspan=2 colspan=3><b>Total</b></td>
                    <td>P</td>
                    <td colspan=3><b><?php echo number_format($vendas->sum('peso'), 2, ',', '.'); ?></b></td>
                    <td rowspan=2 ><b><?php echo 'R$ ' . number_format($vendas->sum('valor_total'), 2, ',', '.'); ?></b></td>
                    <td rowspan=2 ><b><?php echo 'R$ ' . number_format($cheques->sum('valor_parcela'), 2, ',', '.'); ?></b></td>
                </tr>
                <tr>
                    <td>F</td>
                    <td colspan=3><b><?php echo number_format($vendas->sum('fator'), 1, ',', '.'); ?></b></td>
                </tr>
            </tfoot>
        </tbody>
    </table>
    <br>

    <table>
        <thead>

            <tr>
                <th>Porcentagem</th>
                <th>Total de vendas (g)</th>
                <th>Total de comissão (g)</th>
                <th>Média de preço</th>
                <th>Valor comissão</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Peso (<?php echo e($comissaoRepresentante["porcentagem_peso"]); ?> %)</td>
                <td><?php echo number_format($vendas->sum('peso'), 2, ',', '.'); ?></td>
                <td><?php echo number_format($vendas->sum('peso') * ($comissaoRepresentante["porcentagem_peso"] / 100), 2, ',', '.'); ?></td>
                <td><?php echo 'R$ ' . number_format($totalVendaPesoAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('peso'), 2, ',', '.'); ?></td>
                <td>
                    <?php echo 'R$ ' . number_format(($totalVendaPesoAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('peso')) * $vendas->sum('peso') * ($comissaoRepresentante["porcentagem_peso"] / 100), 2, ',', '.'); ?>
                </td>
            </tr>
            <tr>
                <td>Fator (<?php echo e($comissaoRepresentante["porcentagem_fator"]); ?> %)</td>
                <td><?php echo number_format($vendas->sum('fator'), 1, ',', '.'); ?></td>
                <td><?php echo number_format($vendas->sum('fator') * ($comissaoRepresentante["porcentagem_fator"] / 100), 2, ',', '.'); ?></td>
                <td><?php echo 'R$ ' . number_format($totalVendaFatorAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('fator'), 2, ',', '.'); ?></td>
                <td>
                    <?php echo 'R$ ' . number_format(($totalVendaFatorAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('fator')) * $vendas->sum('fator') * ($comissaoRepresentante["porcentagem_fator"] / 100), 2, ',', '.'); ?>
                </td>
            </tr>
            <tr>
                <td colspan=4><b>Total</b></td>
                <td><b><?php echo 'R$ ' . number_format(($totalVendaPesoAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('peso')) * $vendas->sum('peso') * ($comissaoRepresentante["porcentagem_peso"] / 100)
                    +
                    ($totalVendaFatorAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('fator')) * $vendas->sum('fator') * ($comissaoRepresentante["porcentagem_fator"] / 100), 2, ',', '.'); ?></b></td>
            </tr>
        </tbody>
    </table>
    <br>
    <table>
        <thead>
            <tr>
                <th>Forma Pagamento</th>
                <th>Status</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pagamentosPorForma; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyFormaPagamento => $pagamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $__currentLoopData = $pagamento->groupBy('status'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyStatus => $teste): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($keyFormaPagamento); ?></td>  
                        <td><?php echo e($keyStatus); ?></td>
                        <td><?php echo 'R$ ' . number_format($teste->sum('valor_parcela'), 2, ',', '.'); ?></td>
                    </tr>  
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=3>Nenhum registro</td>
                </tr>
            <?php endif; ?>
            <tfoot>
                <tr>
                    <td colspan=2><b>Total</b></td>
                    <td><b><?php echo 'R$ ' . number_format($pagamentosTotal, 2, ',', '.'); ?></b></td>
                </tr>
            </tfoot>
        </tbody>
    </table>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/venda/pdf/pdf_conferencia_relatorio_vendas.blade.php ENDPATH**/ ?>