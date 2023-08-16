<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($titulo); ?></title>
</head>
<style>
    table { 
        width:100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    th {
        background-color: #d9dde2;
    }
    h3 {
        margin: 0 0 0 0;
        text-align: center;
    }
    .titular {
        font-size:9px;
    }
</style>
<body>
    <h3><?php echo e($titulo); ?></h3>
    <?php if(!$cc_representante->isEmpty()): ?>
        <table>
            <thead>
                <tr>
                    <th colspan = 5>Representantes</th>
                </tr>
                <tr>
                    <th>Representante</th>
                    <th>Balanço</th>
                    <th>Peso</th>
                    <th>Fator</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $cc_representante; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $representante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($representante->representante->pessoa->nome); ?></td>
                        <td><?php echo e($representante->balanco); ?></td>
                        <td><?php echo number_format($representante->peso, 2, ',', '.'); ?></td>
                        <td><?php echo number_format($representante->fator, 1, ',', '.'); ?></td>
                        <td><?php echo e($representante->observacao); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan=5>Nenhum registro</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <br>
    <?php endif; ?>
    <?php if(!$cc_fornecedor->isEmpty()): ?>
        <table>
            <thead>
                <tr>
                    <th colspan = 4>Fornecedores</th>
                </tr>
                <tr>
                    <th>Fornecedor</th>
                    <th>Balanço</th>
                    <th>Peso</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $cc_fornecedor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro_cc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($registro_cc->fornecedor->pessoa->nome); ?></td>
                        <td><?php echo e($registro_cc->balanco == 'Débito' ? 'Compra' : 'Fechamento'); ?></td>
                        <td><?php echo number_format($registro_cc->peso, 2, ',', '.'); ?></td>
                        <td>
                            <?php echo e($registro_cc->observacao); ?>

                            
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan=4>Nenhum registro</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>    
    <?php endif; ?>
    <?php if($cheques->has('Adiado')): ?>
        <table>
            <thead>
                <tr>
                    <th colspan = 8>Prorrogações</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Para</th>
                    <th>Dias</th>
                    <th>Valor</th>
                    <th>Juros</th>
                    <th>Rep</th>
                    <th>Parceiro</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $cheques['Adiado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chequesAdiados): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class='titular'><?php echo e($chequesAdiados->nome_cheque); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($chequesAdiados->data_parcela)); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($chequesAdiados->adiamentos->nova_data)); ?></td>
                        <td><?php echo e($chequesAdiados->adiamentos->dias_totais); ?></td>
                        <td><?php echo 'R$ ' . number_format($chequesAdiados->valor_parcela, 2, ',', '.'); ?></td>
                        <td><?php echo 'R$ ' . number_format($chequesAdiados->adiamentos->juros_totais, 2, ',', '.'); ?></td>
                        <td><?php echo e($chequesAdiados->representante->pessoa->nome); ?></td>
                        <td><?php echo e($chequesAdiados->parceiro->pessoa->nome ?? 'Carteira'); ?></td>
                    </tr>
                   
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan=7>Nenhum registro</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            
        </table>
        <br>
    <?php endif; ?>
    <?php if($cheques->has('Devolvido')): ?>
        <table>
            <thead>
                <tr>
                    <th colspan = 6>Cheques Devolvidos/Resgatados</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Representante</th>
                    <th>Valor</th>
                    <th>Parceiro</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $cheques['Devolvido']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $devolvido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class='titular'><?php echo e($devolvido->nome_cheque); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($devolvido->data_parcela)); ?></td>
                        <td><?php echo e($devolvido->status); ?> <?php echo e($devolvido->motivo); ?></td>
                        <td><?php echo e($devolvido->representante->pessoa->nome); ?></td>
                        <td><?php echo 'R$ ' . number_format($devolvido->valor_parcela, 2, ',', '.'); ?></td>
                        <td><?php echo e($devolvido->parceiro->pessoa->nome); ?></td>
                    </tr>
                    
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan=6>Nenhum registro</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            
        </table>
        <br>
    <?php endif; ?>
    <?php if($cheques->has('Resgatado')): ?>
    <table>
        <thead>
            <tr>
                <th colspan = 6>Cheques Devolvidos/Resgatados</th>
            </tr>
            <tr>
                <th>Cliente</th>
                <th>Data</th>
                <th>Status</th>
                <th>Representante</th>
                <th>Valor</th>
                <th>Parceiro</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $cheques['Resgatado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resgatado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class='titular'><?php echo e($resgatado->nome_cheque); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($resgatado->data_parcela)); ?></td>
                    <td><?php echo e($resgatado->status); ?></td>
                    <td><?php echo e($resgatado->representante->pessoa->nome); ?></td>
                    <td><?php echo 'R$ ' . number_format($resgatado->valor_parcela, 2, ',', '.'); ?></td>
                    <td><?php echo e($resgatado->parceiro->pessoa->nome); ?></td>
                </tr>
                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=5>Nenhum registro</td>
                </tr>
            <?php endif; ?>
        </tbody>
        
    </table>
    <br>
<?php endif; ?>
    
    <?php if($cheques->has('Depositado')): ?>
        <table>
            <thead>
                <tr>
                    <th colspan = 4>Cheques Depositados</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Representante</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $cheques['Depositado']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $depositado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class='titular'><?php echo e($depositado->nome_cheque); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($depositado->data_parcela)); ?></td>
                        <td><?php echo e($depositado->representante->pessoa->nome); ?></td>
                        <td><?php echo 'R$ ' . number_format($depositado->valor_parcela, 2, ',', '.'); ?></td>
                    </tr>
                  
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan=4>Nenhum registro</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=3>TOTAL</td>
                    <td><?php echo 'R$ ' . number_format($cheques['Depositado']->sum('valor_parcela'), 2, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>
        <br>
    <?php endif; ?>
    <?php if(!$despesas->isEmpty()): ?>
        <table>
            <thead>
                <tr>
                    <th colspan = 3>Despesas</th>
                </tr>
                <tr>
                    <th>Nome</th>
                    <th>Local</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $despesas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $despesa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class='titular'><?php echo e($despesa->nome); ?></td>
                        <td><?php echo e($despesa->local->nome); ?></td>
                        <td><?php echo 'R$ ' . number_format($despesa->valor, 2, ',', '.'); ?></td>
                    </tr>
                    
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan=3>Nenhum registro</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=2>Total</th>
                    <th colspan=1><?php echo 'R$ ' . number_format($despesas->sum('valor'), 2, ',', '.'); ?></th>
                </tr>
            </tfoot>
        </table>
        <br>
    <?php endif; ?>
    <?php if(!$recebimentos->isEmpty()): ?>
        <table>
            <thead>
                <tr>
                    <th colspan = 4>Recebimentos</th>
                </tr>
                <tr>
                    <th>Cliente</th>
                    <th>Observação</th>
                    <th>Conta</th>
                    <th>Valor recebido</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recebimentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recebimento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if($recebimento->parcela()->exists() && $recebimento->parcela->forma_pagamento == 'Cheque'): ?>
                        <tr>
                            <td class='titular'><?php echo e($recebimento->parcela->nome_cheque); ?></td>
                            <td>Ref. <?php echo e($recebimento->parcela->forma_pagamento); ?> - <?php echo 'R$ ' . number_format($recebimento->parcela->valor_parcela, 2, ',', '.'); ?></td>
                            <td><?php echo e($recebimento->conta->nome ?? ''); ?></td>
                            <td><?php echo 'R$ ' . number_format($recebimento->valor, 2, ',', '.'); ?></td>
                        </tr>
                    <?php elseif(!$recebimento->parcela()->exists()): ?>
                    <tr>
                        <td class='titular'>
                            <?php echo e($recebimento->representante->pessoa->nome); ?> 
                        </td>
                        <td><?php echo e($recebimento->observacao); ?></td>
                        <td><?php echo e($recebimento->conta->nome ?? ''); ?></td>
                        <td><?php echo 'R$ ' . number_format($recebimento->valor, 2, ',', '.'); ?></td>
                    </tr>
                    <?php else: ?>
                        <tr>
                            <td class='titular'>
                                <?php echo e($recebimento->parcela->venda->cliente->pessoa->nome); ?> 
                            </td>
                            <td><?php echo e($recebimento->parcela->forma_pagamento); ?> - <?php echo 'R$ ' . number_format($recebimento->parcela->valor_parcela, 2, ',', '.'); ?></td>
                            <td><?php echo e($recebimento->conta->nome ?? ''); ?></td>
                            <td><?php echo 'R$ ' . number_format($recebimento->valor, 2, ',', '.'); ?></td>
                        </tr>
                    <?php endif; ?>
                   
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan=4>Nenhum registro</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=3>Total</th>
                    <th colspan=1><?php echo 'R$ ' . number_format($recebimentos->sum('valor'), 2, ',', '.'); ?></th>
                </tr>
            </tfoot>
        </table>
        <br>
    <?php endif; ?>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/fornecedor/pdf/mov_diario.blade.php ENDPATH**/ ?>