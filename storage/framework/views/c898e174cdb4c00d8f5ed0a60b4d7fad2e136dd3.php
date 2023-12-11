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
        CONFERÊNCIA PARCELAS - <?php echo e($representante->pessoa->nome); ?> 
    </h3>

    <?php $__currentLoopData = $vendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <table >
        <thead>
            <tr>
                <th colspan=7><?php echo e($venda->cliente->pessoa->nome); ?></th>
            </tr>
            <tr>
                <th>Data</th>
                <th>Titular</th>
                <th>Forma pgto</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Rec Rep</th>
                <th>Pagamentos Rep</th>
            </tr>
        </thead>
        <?php $__currentLoopData = $parcelas->where('venda_id', $venda->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parcela): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tbody>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($parcela->data_parcela)); ?></td>
                    <td><?php echo e($parcela->nome_cheque); ?></td>
                    <td><?php echo e($parcela->forma_pagamento); ?></td>
                    <td><?php echo 'R$ ' . number_format($parcela->valor_parcela, 2, ',', '.'); ?></td>
                    <td><?php echo e($parcela->status); ?></td>

                    <td><?php echo e($parcela->recebido_representante ? 'X' : ''); ?></td>
                    <td>
                        <?php if($parcela->status != 'Aguardando'): ?>
                            <?php $__currentLoopData = $parcela->pagamentos_representantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pgto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p> <?php echo date('d/m/Y', strtotime($pgto->data)); ?> - <?php echo 'R$ ' . number_format($pgto->valor, 2, ',', '.'); ?> (<?php echo e($pgto->conta->nome); ?>)</p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            TOTAL PAGO: <?php echo 'R$ ' . number_format($parcela->pagamentos_representantes->sum('valor'), 2, ',', '.'); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
    <br>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html>

<?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/venda/pdf/pdf_conferencia_parcelas_relatorio_vendas.blade.php ENDPATH**/ ?>