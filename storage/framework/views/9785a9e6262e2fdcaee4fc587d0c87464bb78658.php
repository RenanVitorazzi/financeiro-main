<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório Geral</title>
</head>
<style>
    * {
        margin: 5 15 1 5 ;
    }
    table {
        border-collapse: collapse;
        font-size: 12px;
        page-break-inside: avoid;
        width:100%;
    }
    td, th {
        border: 1px solid black;
        text-align: center;
    }
    tr:nth-child(even) {
        background-color: #d9dde2;
    }
    .tabela_invisivel {
        border: 2px solid rgb(255, 255, 255);
        width: 50%;
        padding-top:0px;
        margin-top: 0px;
        vertical-align: top;
    }
    #tabela_devolvidos {
        /* font-size:10px; !important */
    }
    h5 {
        text-align: center;
    }
</style>
<body>
    <h5>Consolidado - <?php echo e(\Carbon\Carbon::now()->format('d/m/Y')); ?></h5>
    
    <table>
        <tr>
            <td class='tabela_invisivel'>
                <table>
                    <thead>
                        <tr>
                            <th colspan = 2>Carteira</th>
                        </tr>
                        <tr>
                            <th>Mês</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $carteira; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $carteira_mensal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($carteira_mensal->month); ?>/<?php echo e($carteira_mensal->year); ?></td>
                                <td><?php echo 'R$ ' . number_format($carteira_mensal->total_mes, 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan=2>Nenhum registro</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><b>Total</b></td>
                            <td><b><?php echo 'R$ ' . number_format($totalCarteira[0]->totalCarteira, 2, ',', '.'); ?></b></td>
                        </tr>
                    </tfoot>
                </table>
                
            </td>
            <td class='tabela_invisivel'>
                <table>
                    <thead>
                        <tr>
                            <th colspan=5>Cheques devolvidos</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Conta Corrente</th>
                            <th>Escritório</th>
                            <th>Parceiros</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id='tabela_devolvidos'>
                        <?php $__empty_1 = true; $__currentLoopData = $representantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $representante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($representante->conta_corrente_sum_peso_agregado != 0 || $representante->conta_corrente_sum_fator_agregado != 0): ?>
                                <tr>
                                    <td><?php echo e($representante->pessoa->nome); ?></td>
                                    <td><?php echo 'R$ ' . number_format($chequesEmAberto[$representante->id]['contaCorrente'], 2, ',', '.'); ?></td>
                                    <td><?php echo 'R$ ' . number_format($chequesEmAberto[$representante->id]['escritorio'], 2, ',', '.'); ?></td>
                                    <td><?php echo 'R$ ' . number_format($chequesEmAberto[$representante->id]['parceiros'], 2, ',', '.'); ?></td>
                                    <td><?php echo 'R$ ' . number_format($chequesEmAberto[$representante->id]['totalGeral'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan=5>Nenhum registro</td>
                            </tr>
                        <?php endif; ?>
                        <tfoot>
                            <tr>
                                <td colspan=4><b>Total</b></td>
                                <td><b><?php echo 'R$ ' . number_format($totalGeralDeTodosChequesDevolvidos, 2, ',', '.'); ?></b></td>
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
                
            </td>
            
        </tr>
    </table>
    
    <table>
        <tr>
           
            <td class='tabela_invisivel'>
                <table>
                    <thead>
                        <tr>
                            <th colspan=2>Fornecedores (Débito)</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Saldo</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $fornecedores->where('conta_corrente_sum_peso_agregado', '<', 0); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fornecedor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($fornecedor->pessoa->nome); ?></td>
                                <td><?php echo number_format($fornecedor->conta_corrente_sum_peso_agregado, 2, ',', '.'); ?></td>
                                
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan=2>Nenhum registro</td>
                            </tr>
                        <?php endif; ?>
                        <tfoot>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b><?php echo number_format($fornecedores->where('conta_corrente_sum_peso_agregado', '<', 0)->sum('conta_corrente_sum_peso_agregado'), 2, ',', '.'); ?></b></td>
                            
                                
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
                <p></p>
                <table>
                    <thead>
                        <tr>
                            <th colspan=2>Fornecedores (Crédito)</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Saldo</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $fornecedores->where('conta_corrente_sum_peso_agregado', '>', 0); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fornecedor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($fornecedor->pessoa->nome); ?></td>
                                <td><?php echo number_format($fornecedor->conta_corrente_sum_peso_agregado, 2, ',', '.'); ?></td>
                                
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan=2>Nenhum registro</td>
                            </tr>
                        <?php endif; ?>
                        <tfoot>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b><?php echo number_format($fornecedores->where('conta_corrente_sum_peso_agregado', '>', 0.005)->sum('conta_corrente_sum_peso_agregado'), 2, ',', '.'); ?></b></td>
                            
                                
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
            </td>
            <td class='tabela_invisivel'>
                <table>
                    <thead>
                        <tr>
                            <th colspan=4>Representantes</th>
                        </tr>
                        <tr>
                            <th>Nome</th>
                            <th>Peso</th>
                            <th>Fator</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $representantes->where('conta_corrente_sum_peso_agregado', '<', 0); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $representante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($representante->pessoa->nome); ?></td>
                                    <td><?php echo number_format(abs($representante->conta_corrente_sum_peso_agregado), 2, ',', '.'); ?></td>
                                    <td><?php echo number_format(abs($representante->conta_corrente_sum_fator_agregado), 1, ',', '.'); ?></td>
                                    <td><?php echo number_format(abs($representante->conta_corrente_sum_peso_agregado + ($representante->conta_corrente_sum_fator_agregado / 32)), 2, ',', '.'); ?></td>
                                </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan=4>Nenhum registro</td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>ESTOQUE</td>
                            <td><?php echo number_format($estoque->sum('peso_agregado'), 2, ',', '.'); ?></td>
                            <td><?php echo number_format($estoque->sum('fator_agregado'), 1, ',', '.'); ?></td>
                            <td><?php echo number_format($estoque->sum('peso_agregado') + ($estoque->sum('fator_agregado') / 32), 2, ',', '.'); ?></td>
                        </tr>
                        <tfoot>
                            <tr>
                                <td><b>Total</b></td>
                                <td><b><?php echo number_format(abs($representantes->sum('conta_corrente_sum_peso_agregado')) + $estoque->sum('peso_agregado'), 2, ',', '.'); ?></b></td>
                                <td><b><?php echo number_format(abs($representantes->sum('conta_corrente_sum_fator_agregado'))+ $estoque->sum('fator_agregado'), 1, ',', '.'); ?></b></td>
                                <td>
                                    <b>
                                        <?php echo number_format(abs($representantes->sum('conta_corrente_sum_peso_agregado')) + $estoque->sum('peso_agregado') +
                                            (abs($representantes->sum('conta_corrente_sum_fator_agregado'))/32 + $estoque->sum('fator_agregado') / 32), 2, ',', '.'); ?>
                                    </b>
                                </td>
                            </tr>
                        </tfoot>
                    </tbody>
                   
                </table>
                
            </td>
        </tr>
    </table>
    
    <table>
        <tr>
            <td class='tabela_invisivel'>
            </td>
            <td class='tabela_invisivel'>
                
            </td>
        </tr>
    </table>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/fornecedor/pdf/diario4.blade.php ENDPATH**/ ?>