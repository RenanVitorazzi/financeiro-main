<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HISTÓRICO <?php echo e($cliente->pessoa->nome); ?></title>
</head>
<style>
    table {
        width:100%;
        border-collapse: collapse;
        font-size:12px;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    /* tr:nth-child(even) {
        background-color: #d9dde2;
    } */
    h5 {
        text-align: center;
        margin-top: 0px;
        margin-bottom: 0px;
    }
    .nome {
        font-size:10px;
    }
    .fator {
        background-color: #d9dde2;
    }
</style>
<body>
    <h5>HISTÓRICO <?php echo e($cliente->pessoa->nome); ?></h5>
    <br>
    <table>
        <thead>
            <tr>
                <th colspan = 8>COMPRAS</th>
            </tr>
            <tr>
                <th>DATA</th>
                <th colspan=5>COMPRA</th>
                <th>VALOR</th>
                <th>PRAZO MÉDIO</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $compras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                
            <?php $__currentLoopData = $parcelas->where('venda_id', $compra->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $dias = \Carbon\Carbon::parse($parcela->data_parcela)->diffInDays($compra->data_venda);
                    $totalPrazo += $dias;
                ?>                                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
            <?php if(!$consignados->where('venda_id', $compra->id)->isEmpty()): ?>
                    <tr>
                        <td rowspan=2>(<?php echo date('d/m/Y', strtotime($consignados->where('venda_id', $compra->id)->first()->data)); ?>) <?php echo date('d/m/Y', strtotime($compra->data_venda)); ?></td>
                        <td>P</td>
                        <td>(<?php echo number_format($consignados->where('venda_id', $compra->id)->first()->peso, 2, ',', '.'); ?>) <?php echo number_format($compra->peso, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($compra->cotacao_peso, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($compra->peso * $compra->cotacao_peso, 2, ',', '.'); ?></td>
                        <td rowspan=2><?php echo 'R$ ' . number_format(($compra->peso * $compra->cotacao_peso) + ($compra->fator * $compra->cotacao_fator), 2, ',', '.'); ?></td>
                        <td rowspan=2><?php echo 'R$ ' . number_format($parcelas->where('venda_id', $compra->id)->sum('valor_parcela'), 2, ',', '.'); ?></td>
                        <td rowspan=2><?php echo e(number_format($totalPrazo / $parcelas->where('venda_id', $compra->id)->count(), 2)); ?></td>
                    </tr>
                    <tr>
                        <td class='fator'>F</td>
                        <td class='fator'>(<?php echo number_format($consignados->where('venda_id', $compra->id)->first()->fator, 1, ',', '.'); ?>) <?php echo number_format($compra->fator, 1, ',', '.'); ?></td>
                        <td class='fator'><?php echo 'R$ ' . number_format($compra->cotacao_fator, 2, ',', '.'); ?></td>
                        <td class='fator'><?php echo 'R$ ' . number_format($compra->fator * $compra->cotacao_fator, 2, ',', '.'); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td rowspan=2><?php echo date('d/m/Y', strtotime($compra->data_venda)); ?></td>
                        <td>P</td>
                        <td><?php echo number_format($compra->peso, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($compra->cotacao_peso, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($compra->peso * $compra->cotacao_peso, 2, ',', '.'); ?></td>
                        <td rowspan=2><?php echo 'R$ ' . number_format(($compra->peso * $compra->cotacao_peso) + ($compra->fator * $compra->cotacao_fator), 2, ',', '.'); ?></td>   
                        <td rowspan=2> <?php echo 'R$ ' . number_format($parcelas->where('venda_id', $compra->id)->sum('valor_parcela'), 2, ',', '.'); ?></td>
                        <td rowspan=2><?php echo e(number_format($totalPrazo / $parcelas->where('venda_id', $compra->id)->count(), 2)); ?></td>
                        <?php
                            $totalPrazo = 0;
                        ?>
                    </tr>
                    <tr>
                        <td class='fator'>F</td>
                        <td class='fator'><?php echo number_format($compra->fator, 1, ',', '.'); ?></td>
                        <td class='fator'><?php echo 'R$ ' . number_format($compra->cotacao_fator, 2, ',', '.'); ?></td>
                        <td class='fator'><?php echo 'R$ ' . number_format($compra->fator * $compra->cotacao_fator, 2, ',', '.'); ?></td>
                    </tr>
                <?php endif; ?>
                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=8>NENHUM REGISTRO</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan=6>TOTAL</td>
                <td><?php echo 'R$ ' . number_format($parcelas->sum('valor_parcela'), 2, ',', '.'); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <br>
    <table>
        <thead>
            <tr>
                <th colspan=5>CHEQUES</th>
            </tr>
            <tr>
                <th>NOME</th>
                <th>BANCO</th>
                <th>NÚMERO</th>
                <th>DATA</th>
                <th>VALOR</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $parcelas->where('forma_pagamento', 'LIKE', 'Cheque')->sortBy('data_parcela'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($parcela->nome_cheque); ?></td>
                    <td><?php echo e($parcela->numero_banco); ?></td>
                    <td><?php echo e($parcela->numero_cheque); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($parcela->data_parcela)); ?></td>
                    <td><?php echo 'R$ ' . number_format($parcela->valor_parcela, 2, ',', '.'); ?></td>
                </tr>                        
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=5>NENHUM REGISTRO</td>    
                </tr>   
            <?php endif; ?>
        </tbody>
    </table>
    <br>
    <table>
        <thead>
            <tr>
                <th colspan=4>OUTROS PAGAMENTOS</th>
            </tr>
            <tr>
                <th>NOME</th>
                <th>FORMA PGTO</th>
                <th>DATA</th>
                <th>VALOR</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $parcelas->where('forma_pagamento', '<>', 'Cheque')->sortBy('data_parcela'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($parcela->nome_cheque); ?></td>
                    <td><?php echo e($parcela->forma_pagamento); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($parcela->data_parcela)); ?></td>
                    <td><?php echo 'R$ ' . number_format($parcela->valor_parcela, 2, ',', '.'); ?></td>
                </tr>                        
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=4>NENHUM REGISTRO</td>    
                </tr>   
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/cliente/pdf/pdf_historico_cliente.blade.php ENDPATH**/ ?>