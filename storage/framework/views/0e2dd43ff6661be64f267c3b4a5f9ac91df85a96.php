<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($representante->pessoa->nome); ?>  Relatório de Vendas </title>
</head>
<style>
    .marcatexto {
        box-shadow: 15px 0 0 0 #000, -5px 0 0 0 #000;
        background: #000;
        display: inline;
        padding: 3px 0 !important;
        position: relative;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        /* page-break-inside: avoid; */
        page-break-before: no;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    td {
        /* height: 1PX; */
        font-size: 10px;
    }
    th {
        background-color:rgb(216, 216, 216);
        /* color:white; */
    }
    /* tr:nth-child(even) {
        background-color: #a9acb0;
    } */
    h1, h3 {
        margin-top: 0;
        margin-bottom: 0;
        text-align: center;
    }
    .nome {
        font-size: 9px;
        text-align: left;
        padding-left: 3px;
    }
    .fator {
        background-color: #dfdfdf;
    }
    tfoot {
        font-weight: bolder;
    }

    .tabela_pix {
        width: 49%;
        float: right;
    }
    .tabela_dh {
        width: 49%;
        float: left;
    }
    /*
    .page_break { 
        page-break-before: always; 
    }
    */
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
                    <td rowspan=2 class='nome'><?php echo e($venda->cliente->pessoa->nome); ?></td>
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

    <table  class='page_break'>
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
                <td colspan=4><b>Total comissão - sem desconto</b></td>
                <td><b><?php echo 'R$ ' . number_format(($totalVendaPesoAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('peso')) * $vendas->sum('peso') * ($comissaoRepresentante["porcentagem_peso"] / 100)
                    +
                    ($totalVendaFatorAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('fator')) * $vendas->sum('fator') * ($comissaoRepresentante["porcentagem_fator"] / 100), 2, ',', '.'); ?></b></td>
            </tr>
            <tr>
                <td colspan=4><b>Descontos (Pagamento retirado do pedido)</b></td>
                <td><?php echo 'R$ ' . number_format($pagamentos->whereNotNull('recebido_representante')->sum('valor_parcela'), 2, ',', '.'); ?></td>
            </tr>
            <tr>
                <td colspan=4><b>Total</b></td>
                <td><b><?php echo 'R$ ' . number_format(($totalVendaPesoAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('peso')) * $vendas->sum('peso') * ($comissaoRepresentante["porcentagem_peso"] / 100)
                    +
                    ($totalVendaFatorAVista / $vendas->where('metodo_pagamento', 'À vista')->sum('fator')) * $vendas->sum('fator') * ($comissaoRepresentante["porcentagem_fator"] / 100)
                    -
                    ($pagamentos->whereNotNull('recebido_representante')->sum('valor_parcela') ), 2, ',', '.'); ?></b></td>
            </tr>
        </tbody>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th>Forma de pagamento</th>
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

    <br>

    <table>
        <thead>
            <tr>
                <th colspan=4>Descontos</th>
            </tr>
            
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pagamentos->whereNotNull('recebido_representante'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $descontos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($descontos->data_parcela)); ?></td>
                    <td><?php echo e($descontos->venda->cliente->pessoa->nome); ?></td>
                    <td><?php echo e($descontos->forma_pagamento); ?></td>
                    <td><?php echo 'R$ ' . number_format($descontos->valor_parcela, 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=4>Nenhum desconto</td>
                </tr>
            <?php endif; ?>
            <tfoot>
                <tr>
                    <td colspan=3>Total</td>
                    <td><?php echo 'R$ ' . number_format($pagamentos->whereNotNull('recebido_representante')->sum('valor_parcela'), 2, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </tbody> 
    </table>

    <br>

    <table class='tabela_dh'>
        <thead>
            <tr>
                <th colspan=3>Pagamentos em dinheiro</th>
            </tr>
            
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pagamentos->where('forma_pagamento', 'like', 'Dinheiro')->sortBy('data_parcela'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($dh->data_parcela)); ?></td>
                <td><?php echo e($dh->venda->cliente->pessoa->nome); ?></td>
                <td><?php echo 'R$ ' . number_format($dh->valor_parcela, 2, ',', '.'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=3>Nenhuma venda recebida com dinheiro</td>
                </tr>
            <?php endif; ?>
            <tfoot>
                <tr>
                    <td colspan=2>Total</td>
                    <td><?php echo 'R$ ' . number_format($pagamentos->where('forma_pagamento', 'like', 'Dinheiro')->sum('valor_parcela'), 2, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </tbody>
    </table>
    
    <table class='tabela_pix'>
        <thead>
            <tr>
                <th colspan=3>Pagamentos em pix</th>
            </tr>
            
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pagamentos->where('forma_pagamento', 'like', 'Pix')->sortBy('data_parcela'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pix): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($pix->data_parcela)); ?></td>
                <td><?php echo e($pix->venda->cliente->pessoa->nome); ?></td>
                <td><?php echo 'R$ ' . number_format($pix->valor_parcela, 2, ',', '.'); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=3>Nenhuma venda recebida com pix</td>
                </tr>
            <?php endif; ?>
            <tfoot>
                <tr>
                    <td colspan=2>Total</td>
                    <td><?php echo 'R$ ' . number_format($pagamentos->where('forma_pagamento', 'like', 'Pix')->sum('valor_parcela'), 2, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </tbody>


    </table>

</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/venda/pdf/pdf_conferencia_relatorio_vendas.blade.php ENDPATH**/ ?>