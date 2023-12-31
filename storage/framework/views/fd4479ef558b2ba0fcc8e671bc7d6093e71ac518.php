<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Extrato <?php echo e($representante->pessoa->nome); ?></title>
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
        background-color:black;
        color:white;
    }
    /* tr:nth-child(even) {
        background-color: #a9acb0;
    } */
    h3 {
        text-align: center;
    }
    .titular {
        font-size: 10px;
        text-align: left;
    }
    .credito {
        background-color: rgb(173, 255, 173);
    }
</style>
<body>
    <h3>EXTRATO <?php echo e($representante->pessoa->nome); ?> - <?php echo date('d/m/Y', strtotime($hoje)); ?></h3>
    <table>
        <thead>
            <tr>
                <th>DATA</th>
                <th>DESCRIÇÃO</th>
                <th>DÉBITO</th>
                <th>CRÉDITO</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($data_inicio)); ?></td>
                <td colspan=3>SALDO ANTERIOR</td>
                <td><?php echo 'R$ ' . number_format($saldo_total, 2, ',', '.'); ?></td>
            </tr>
            <?php $__empty_1 = true; $__currentLoopData = $saldos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saldo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if($saldo->balanco == 'Débito'): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($saldo->data_entrega)); ?></td>
                        <td>
                            <a target='_blank' href='<?php echo e(route('pdf_cheques_entregues', ['representante_id' => $representante->id, 'data_entrega' => $saldo->data_entrega])); ?>'>
                                <?php echo e($saldo->descricao); ?> - <?php echo date('d/m/Y', strtotime($saldo->data_entrega)); ?>
                            </a>
                        </td>
                        <td><?php echo 'R$ ' . number_format(-$saldo->valor_total_debito, 2, ',', '.'); ?></td>
                        <td></td>
                        <?php
                            $saldo_total -= $saldo->valor_total_debito;
                        ?>
                        <td><?php echo 'R$ ' . number_format($saldo_total, 2, ',', '.'); ?></td>
                    </tr>
                <?php elseif($saldo->balanco == 'Crédito' && $saldo->valor_total_debito < 0): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($saldo->data_entrega)); ?></td>
                        <td><?php echo e($saldo->descricao); ?></td>
                        <td><?php echo 'R$ ' . number_format($saldo->valor_total_debito, 2, ',', '.'); ?></td>
                        <td></td>
                        <?php
                            $saldo_total += $saldo->valor_total_debito;
                        ?>
                        <td><?php echo 'R$ ' . number_format($saldo_total, 2, ',', '.'); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($saldo->data_entrega)); ?></td>
                        <td><?php echo e($saldo->descricao); ?></td>
                        <td></td>
                        <td><?php echo 'R$ ' . number_format($saldo->valor_total_debito, 2, ',', '.'); ?></td>
                        <?php
                            $saldo_total += $saldo->valor_total_debito;
                        ?>
                        <td><?php echo 'R$ ' . number_format($saldo_total, 2, ',', '.'); ?></td>
                    </tr>
                <?php endif; ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan=5>NENHUM REGISTRO</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <?php
            if ($saldo_total < 0) {
                $tfoot = 'debito';
            } else {
                $tfoot = 'credito';
            }
            ?>
            <tr>
                <td colspan=3><b>TOTAL</b></td>
                <td colspan=2><b><?php echo 'R$ ' . number_format($saldo_total, 2, ',', '.'); ?></b></td>
            </tr>
        </tfoot>
    </table>
    <br>
    <table>
        <tr>
            <td></td>
            <td>QUANTIDADE</td>
            <td>TOTAL DEVOLVIDO</td>
            <td>TOTAL PAGO</td>
            <td>TOTAL EM ABERTO</td>
        </tr>
        <tr>
            <td>
                <a  target='_blank' href='<?php echo e(route('pdf_cheques_devolvidos_escritorio', ['representante_id' => $representante->id])); ?>'> 
                    CHEQUES NA EMPRESA 
                </a>
            </td>
            <td><?php echo e($chequesNaoEntregues->count()); ?></td>
            <td><?php echo 'R$ ' . number_format($chequesNaoEntregues->sum('valor_parcela'), 2, ',', '.'); ?></td>
            <td><?php echo 'R$ ' . number_format($chequesNaoEntregues->sum('pagamentos_representantes_sum_valor'), 2, ',', '.'); ?></td>
            <td><?php echo 'R$ ' . number_format($chequesNaoEntregues->sum('valor_parcela') - $chequesNaoEntregues->sum('pagamentos_representantes_sum_valor'), 2, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>
                <a  target='_blank' href='<?php echo e(route('pdf_cheques_devolvidos_parceiros', ['representante_id' => $representante->id])); ?>'> 
                    CHEQUES COM PARCEIROS 
                </a>            
            </td>
            <td><?php echo e($chequesComParceiros->count()); ?></td>
            
            <td><?php echo 'R$ ' . number_format($chequesComParceiros->sum('valor_parcela'), 2, ',', '.'); ?></td>
            <td><?php echo 'R$ ' . number_format($chequesComParceiros->sum('pagamentos_representantes_sum_valor'), 2, ',', '.'); ?></td>
            <td><?php echo 'R$ ' . number_format(($chequesComParceiros->sum('valor_parcela') - $chequesComParceiros->sum('pagamentos_representantes_sum_valor')), 2, ',', '.'); ?></td>

        </tr>
        <tr>
            <td>TOTAL GERAL</td>
            <td><?php echo e($chequesNaoEntregues->count() + $chequesComParceiros->count()); ?></td>
            <td colspan=3> <?php echo 'R$ ' . number_format(($chequesNaoEntregues->sum('valor_parcela') - $chequesNaoEntregues->sum('pagamentos_representantes_sum_valor') ) +
                ($chequesComParceiros->sum('valor_parcela') - $chequesComParceiros->sum('pagamentos_representantes_sum_valor')), 2, ',', '.'); ?></td>
        </tr>
    </table>
</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/representante/pdf/pdf_cc_representante_novo.blade.php ENDPATH**/ ?>