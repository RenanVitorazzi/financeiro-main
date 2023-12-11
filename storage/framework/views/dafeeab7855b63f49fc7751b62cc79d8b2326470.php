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
    tr:nth-child(even) {
        background-color: #a9acb0;
    }
</style>

<table>
    <thead>
        <th>STATUS</th>
        <th>TITULAR</th>
        <th>VALOR</th>
        <th>DATA</th>
        <th>PARA</th>
    </thead>
    <tbody>
    <?php $__currentLoopData = $chequesProrrogados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cheque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($cheque->movimentacoes->first()->status == 'Adiado'): ?>
            <?php if($antigosAdiamentos->where('parcela_id', $cheque->id)->first()): ?>
                <tr>
                    <td>PRORROGAÇÃO</td>
                    <td><?php echo e($cheque->nome_cheque); ?></td>
                    <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                    <td>
                        <s><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></s>
                        <p><?php echo date('d/m/Y', strtotime($antigosAdiamentos->where('parcela_id', $cheque->id)->first()->data)); ?></p>
                    </td>
                    <td><?php echo date('d/m/Y', strtotime($cheque->adiamentos->nova_data)); ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td>PRORROGAÇÃO</td>
                    <td><?php echo e($cheque->nome_cheque); ?></td>
                    <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($cheque->adiamentos->nova_data)); ?></td>
                </tr>
            <?php endif; ?>
        <?php elseif($cheque->movimentacoes->first()->status == 'Resgatado'): ?>
            <tr>
                <td>RESGATE</td>
                <td><?php echo e($cheque->nome_cheque); ?></td>
                <td><?php echo 'R$ ' . number_format($cheque->valor_parcela, 2, ',', '.'); ?></td>
                <td><?php echo date('d/m/Y', strtotime($cheque->data_parcela)); ?></td>
                <td>-</td>
            </tr>
        <?php endif; ?>
        

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/mail/prorrogacao.blade.php ENDPATH**/ ?>