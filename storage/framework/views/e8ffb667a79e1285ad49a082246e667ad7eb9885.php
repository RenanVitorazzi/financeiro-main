
<?php $__env->startSection('title'); ?>
Dashboard <?php echo e($pessoa->nome); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<style>
    .card_dash>.card:hover {
        border-color:#007bff;
        background-color:#dfeefd;
        box-shadow: 2.5px 4px #888888;
    }
</style>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('representantes.index')); ?>">Representantes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard <?php echo e($pessoa->nome); ?></li>
    </ol>
</nav>


<div class="row">
    <div class="col-sm-6 mb-4 col-md-6 card_dash" data-tipo='CONTA_CORRENTE'>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Conta corrente</h5>
                <p class="card-text">
                    <div>Peso: <?php echo number_format($contaCorrente->sum('peso_agregado'), 2, ',', '.'); ?></div>
                    <div>Fator: <?php echo number_format($contaCorrente->sum('fator_agregado'), 1, ',', '.'); ?></div>
                </p>
                <a href="<?php echo e(route('conta_corrente_representante.show', $representante->id)); ?>" class="btn btn-primary">Conta Corrente</a>
                <a href="<?php echo e(route('impresso_ccr', $representante->id)); ?>" class="btn btn-primary">Impresso</a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 mb-4 col-md-6 card_dash"  data-tipo='CONSIGNADOS'>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Consignados</h5>
                <p class="card-text">
                    <div>Peso: <?php echo number_format($consignados->sum('peso'), 2, ',', '.'); ?></div>
                    <div>Fator: <?php echo number_format($consignados->sum('fator'), 1, ',', '.'); ?></div>
                </p>
                <a href="<?php echo e(route('consignado.index')); ?>" class="btn btn-primary">Consignados</a>
                <a href="<?php echo e(route('pdf_consignados', $representante->id)); ?>" class="btn btn-primary">Impresso</a>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 mb-4 col-md-6 card_dash"  data-tipo='ACERTOS'>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Acertos</h5>
                <p class="card-text">
                    <div>Cheques a serem enviados: <?php echo 'R$ ' . number_format($acertos->where('forma_pagamento', 'LIKE', 'Cheque')->sum('valor_parcela'), 2, ',', '.'); ?></div>
                    <div>OP em aberto: <?php echo 'R$ ' . number_format($acertos->where('forma_pagamento', 'LIKE', 'Transferência Bancária')->sum('valor_parcela'), 2, ',', '.'); ?></div>
                    <div>Total: <?php echo 'R$ ' . number_format($acertos->sum('valor_parcela'), 2, ',', '.'); ?></div>
                </p>
                <a href="<?php echo e(route('pdf_acerto_documento', $representante->id)); ?>" class="btn btn-primary">Relatório de Acertos</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 mb-4 col-md-6 card_dash"  data-tipo='ACERTOS_VENCIDOS'>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Acertos vencidos</h5>
                <p class="card-text">
                    <div>Cheques a serem enviados: <?php echo 'R$ ' . number_format($acertos->where('forma_pagamento', 'LIKE', 'Cheque')->where('data_parcela', '<', \Carbon\Carbon::now())->sum('valor_parcela'), 2, ',', '.'); ?></div>
                    <div>OP em aberto: <?php echo 'R$ ' . number_format($acertos->where('forma_pagamento', 'LIKE', 'Transferência Bancária')->where('data_parcela', '<', \Carbon\Carbon::now())->sum('valor_parcela'), 2, ',', '.'); ?></div>
                    <div>Total: <?php echo 'R$ ' . number_format($acertos->where('data_parcela', '<', \Carbon\Carbon::now())->sum('valor_parcela'), 2, ',', '.'); ?></div>
                </p>
                <a href="<?php echo e(route('pdf_acerto_documento', $representante->id)); ?>" class="btn btn-primary">Relatório de Acertos</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 mb-4 col-md-6 card_dash" >
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Cheques Devolvidos</h5>
                <p class="card-text">
                    <div>Conta corrente: <b class='text-danger'>TO DO </b> </div>
                    <div>Na empresa (<?php echo e($devolvidosNoEscritorio->count()); ?>): <?php echo 'R$ ' . number_format($devolvidosNoEscritorio->sum('valor_parcela'), 2, ',', '.'); ?> </div>
                    <div>Nos parceiros (<?php echo e($devolvidosComParceiros->count()); ?>): <?php echo 'R$ ' . number_format($devolvidosComParceiros->sum('valor_parcela'), 2, ',', '.'); ?></div>
                </p>
                <a href="<?php echo e(route('pdf_cc_representante', $representante->id)); ?>" class="btn btn-primary">Relatório de Vendas</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 mb-4 col-md-6 card_dash"  data-tipo='ULTIMO_RELATORIO'>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Último relatório de vendas</h5>
                <p class="card-text">
                    <div>Peso: <?php echo number_format($ultimoRelatorioVendas->sum('peso'), 2, ',', '.'); ?></div>
                    <div>Fator: <?php echo number_format($ultimoRelatorioVendas->sum('fator'), 1, ',', '.'); ?></div>
                    <div>Total: <?php echo 'R$ ' . number_format($ultimoRelatorioVendas->sum('valor_total'), 2, ',', '.'); ?></div>
                </p>
                <a href="<?php echo e(route('pdf_relatorio_vendas', $ultimoRelatorioVendas->first()->enviado_conta_corrente)); ?>" class="btn btn-primary">Relatório de Vendas</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    // const CONTA_CORRENTE = <?php echo json_encode($contaCorrente, 15, 512) ?>;
    // const CONSIGNADOS = <?php echo json_encode($consignados, 15, 512) ?>;
    // const ACERTOS = <?php echo json_encode($acertos, 15, 512) ?>;
    // const ACERTOS_ATRASADOS = <?php echo json_encode($acertos->where('data_parcela', '<', \Carbon\Carbon::now())) ?>;
    // const ULTIMO_RELATORIO = <?php echo json_encode($ultimoRelatorioVendas, 15, 512) ?>;

    // let arraySuprema = []
    // arraySuprema['CONTA_CORRENTE'] = CONTA_CORRENTE
    // arraySuprema['CONSIGNADOS'] = CONSIGNADOS
    // arraySuprema['ACERTOS'] = ACERTOS
    // arraySuprema['ACERTOS_ATRASADOS'] = ACERTOS_ATRASADOS
    // arraySuprema['ULTIMO_RELATORIO'] = ULTIMO_RELATORIO
    // // console.log(arraySuprema['CONTA_CORRENTE'])
    // const MODAL = $("#modal2")
    // const MODAL_HEADER = $("#modal-title2")
    // const MODAL_BODY = $("#modal-body2")
    // const MODAL_FOOTER = $("#modal-footer2")

    // $(".card_dash").click( (e) => {
    //     mostrarDetalhes($(e.currentTarget).data('tipo'))
    // })

    // function mostrarDetalhes(tipo) {
    //     console.log(tipo)
    //     MODAL.modal('show')
    //     MODAL_HEADER.text(tipo)
    //     let html = criarHtml(tipo)
    // }

    // function criarHtml(tipo) {
        
    //     console.log(tipo)
    //     arraySuprema[tipo].forEach(element => {
    //         console.log(element)
    //     }); 
    
    // }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/representante/dashboard.blade.php ENDPATH**/ ?>