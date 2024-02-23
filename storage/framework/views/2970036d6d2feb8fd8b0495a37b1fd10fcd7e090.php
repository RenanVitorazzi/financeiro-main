
<?php $__env->startSection('title'); ?>
Relatórios
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<div class='mb-2 d-flex justify-content-between'>
    <h3>Relatórios</h3>  
</div>
<ul class="d-flex list-group list-group">
    <li class='list-group-item d-flex justify-content-between list-group-item-action'>
        <div class='mt-2' data-rota='pdf_cc_parceiro'>
            
            Conta corrente parceiro
        </div>
        <a class="btn btn-dark"><i class="fas fa-star"></i></a>
    </li>
</ul>

            'pdf_cheques_devolvidos_escritorio'
            'pdf_cheques_devolvidos_parceiros'
            'pdf_fornecedores'
            'pdf_fornecedor'
            'carteira_cheque_total'
            'pdf_diario'
            'pdf_diario2'
            'pdf_mov_diario'
            'pdf_movimentacao'
            'pdf_clientes'
            'adiamento_impresso'
            'cheques_devolvidos'
            'fechamento_representante'
            'pdf_relatorio_vendas'
            'pdf_relatorio_vendas_deflacao'
            'pdf_conferencia_relatorio_vendas'
            'pdf_conferencia_relatorio_vendas_sem_avista'
            'pdf_conferencia_parcelas_relatorio_vendas'
            'pdf_despesa_mensal'
            'pdf_estoque'
            'pdf_relatorio_mensal'
            'pdf_confirmar_depositos'
            'pdf_cc_representante_com_cheques_devolvidos'
            'pdf_historico_cliente'
            'pdf_consignados_geral'
            'pdf_prorrogacao'
            'relacao_deb_cred_fornecedores'
            'relacao_deb_cred_representantes'
            'etiqueta_endereco'
            'pdf_prorrogacao_conferencia'
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/relatorios.blade.php ENDPATH**/ ?>