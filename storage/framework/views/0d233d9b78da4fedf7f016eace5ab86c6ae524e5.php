
<?php $__env->startSection('title'); ?>
Relatório PIX BRADESCO
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<style>
    .pointer{
        cursor: pointer;
    }
</style>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('import')); ?>">Importação</a></li>
        <li class="breadcrumb-item active" aria-current="page">PIX BRADESCO</li>
    </ol>
</nav>
<div class='mb-2 d-flex justify-content-between'>
    <h3> Relatório PIX BRADESCO </h3>
    <h5>Conta: <?php echo e($import->conta->nome); ?></h5>
</div>

<?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['class' => 'table-light','id' => 'table-pix']); ?>
    <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>

        <tr>
            <th>#</th>
            <th>Data</th>
            <th>Descrição</th>
            <th>Débito</th>
            <th>Crédito</th>
            <th>Lançamentos</th>
        </tr>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $import->arrayDados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($loop->iteration); ?></td>
                <td><?php echo date('d/m/Y', strtotime($item['data'])); ?></td>
                <td><?php echo e($item['nome']); ?></td>
                <?php if($item['tipo'] == 'Crédito'): ?>
                    <td></td>
                    <td><?php echo 'R$ ' . number_format($item['valor'], 2, ',', '.'); ?></td>
                    <td>
                        <?php $__empty_2 = true; $__currentLoopData = $item['pagamentosRepresentantes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php if($pr->comprovante_id == $item['comprovante_id']): ?>
                                <div class="alert alert-success pointer border border-success relacionarPixId" 
                                        data-movimentacao_nome="<?php echo e($item['nome']); ?>"
                                        data-movimentacao_comprovante_id="<?php echo e($item['comprovante_id']); ?>"
                                        data-pr="<?php echo e($pr); ?>"
                                        data-tabela="pagamentos_representantes"
                                    >
                                    Pagamento relacionado pelo <b>PIX ID</b>
                                    <br>
                                    <i class='fas fa-check fa-lg mt-2'></i>
                                </div>
                            <?php elseif($item['valor'] == $pr->valor && $item['data'] == $pr->data && $pr->comprovante_id == NULL): ?>
                                <div class="alert alert-success" >
                                    Pagamento relacionado pela <b>data</b> e <b>valor</b>
                                    <br>
                                    <span class='btn btn-success mt-2 relacionarPixId'
                                        data-movimentacao_nome="<?php echo e($item['nome']); ?>"
                                        data-movimentacao_comprovante_id="<?php echo e($item['comprovante_id']); ?>"
                                        data-pr="<?php echo e($pr); ?>"
                                        data-tabela="pagamentos_representantes"
                                        >Relacionar por PIX ID
                                    </span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <a class="btn btn-dark" target="_blank"
                                href="<?php echo e(route('criarRecebimentoImportacao', [
                                    'data' => $item['data'],
                                    'descricao' => $item['nome'],
                                    'valor' => $item['valor'],
                                    'conta' => $import->conta->id,
                                    'forma_pagamento' => 'Pix',
                                    'confirmado' => 1,
                                    'tipo_pagamento' => 2,
                                    'comprovante_id' => $item['comprovante_id']
                                ])); ?>"
                            >
                                Lançar recebimento <i class='fas fa-plus ml-2'></i>
                            </a>
                            <span class='btn btn-danger botaoIgnorar'>
                                <span>Ignorar <i class='fas fa-trash ml-2'></i></span>
                            </span>
                        <?php endif; ?>

                    </td>
                <?php elseif($item['tipo'] == 'Débito'): ?>
                    <td><?php echo 'R$ ' . number_format($item['valor'], 2, ',', '.'); ?></td>
                    <td></td>
                    <td>
                        <?php if($item['pagamentosParceiros']->isNotEmpty()): ?>
                            <?php $__currentLoopData = $item['pagamentosParceiros']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p>
                                    <?php if($pr->comprovante_id == $item['comprovante_id']): ?>
                                        <div class="alert alert-success pointer border border-success relacionarPixId" 
                                            data-movimentacao_nome="<?php echo e($item['nome']); ?>"
                                            data-movimentacao_comprovante_id="<?php echo e($item['comprovante_id']); ?>"
                                            data-pr="<?php echo e($pr); ?>"
                                            data-tabela="pagamentos_parceiros"
                                        >
                                            Pagamento relacionado pelo <b>PIX ID</b>
                                            <br>
                                            <i class='fas fa-check fa-lg mt-2'></i>
                                        </div>
                                    <?php elseif($item['valor'] == $pr->valor && $item['data'] == $pr->data && $pr->comprovante_id == NULL): ?>
                                        <div class="alert alert-success" >
                                            Pagamento relacionado pela <b>data</b> e <b>valor</b>
                                            <br>
                                            <span class='btn btn-success mt-2 relacionarPixId'>Relacionar por PIX ID</span>
                                        </div>
                                    <?php endif; ?>
                                </p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php elseif($item['despesas']->isNotEmpty()): ?>
                        
                            <?php $__currentLoopData = $item['despesas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p>
                                    <?php if($pr->comprovante_id == $item['comprovante_id']): ?>
                                        <div class="alert alert-success pointer border border-success relacionarPixIdDespesa" 
                                            data-movimentacao_nome="<?php echo e($item['nome']); ?>"
                                            data-movimentacao_comprovante_id="<?php echo e($item['comprovante_id']); ?>"
                                            data-pr="<?php echo e($pr); ?>"
                                            data-tabela="despesas"
                                        >
                                            Despesa relacionada pelo <b>PIX ID</b>
                                            <br>
                                            <i class='fas fa-check fa-lg mt-2'></i>
                                        </div>
                                    <?php elseif($item['valor'] == $pr->valor && ($item['data'] == $pr->data_pagamento || $item['data'] == $pr->data_vencimento) && $pr->comprovante_id == NULL): ?>
                                        <div class="alert alert-success relacionarPixIdDespesa" 
                                            data-movimentacao_nome="<?php echo e($item['nome']); ?>"
                                            data-movimentacao_comprovante_id="<?php echo e($item['comprovante_id']); ?>"
                                            data-pr="<?php echo e($pr); ?>"
                                            data-tabela="despesas"
                                        >
                                            Despesa relacionada pela <b>data</b> e <b>valor</b>
                                            <br>
                                            <span class='btn btn-success mt-2 '>Relacionar por PIX ID</span>
                                        </div>
                                    <?php endif; ?>
                                </p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                        <?php if($item['pagamentosParceiros']->isEmpty() && $item['despesas']->isEmpty()): ?>
                            <a class="btn btn-dark" target="_blank"
                                href="<?php echo e(route('criarDespesaImportacao', [
                                    'data' => $item['data'],
                                    'descricao' => $item['nome'],
                                    'valor' => $item['valor'],
                                    'conta' => $import->conta->id,
                                    'forma_pagamento' => 'Pix',
                                    'comprovante_id' => $item['comprovante_id']
                                ])); ?>"
                            >
                                Despesa <i class='fas fa-plus ml-2'></i>
                            </a>
                            <a class="btn btn-dark" target="_blank"
                                href="<?php echo e(route('criarRecebimentoImportacao', [
                                    'data' => $item['data'],
                                    'descricao' => $item['nome'],
                                    'valor' => $item['valor'],
                                    'conta' => $import->conta->id,
                                    'forma_pagamento' => 'Pix',
                                    'confirmado' => 1,
                                    'tipo_pagamento' => 1,
                                    'comprovante_id' => $item['comprovante_id']
                                ])); ?>"
                            >
                                Pagamento parceiro<i class='fas fa-plus ml-2'></i>
                            </a>

                            <span class='btn btn-danger botaoIgnorar'>
                                <span>Ignorar <i class='fas fa-trash ml-2'></i></span>
                            </span>
                        <?php endif; ?>
                    </td>
                    
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan=5>Nenhum registro</td>
            </tr>
        <?php endif; ?>
    </tbody>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
<input type="hidden" value="<?php echo e($import->conta->id); ?>" id="conta_id">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    const CONTA_ID = $("#conta_id").val()
    const MODAL_LG = $("#modal2")
    const MODAL_TITLE = $("#modal-title2")
    const MODAL_BODY = $("#modal-body2")
    const MODAL_FOOTER = $("#modal-footer2")

    $(".botaoIgnorar").each( (index, element) => {
        $(element).click( (botao) => {
            // console.log(botao)
            $(botao.currentTarget).parent().parent().fadeOut( "slow" );
            // console.log($(botao.currentTarget).parent().parent())
        })
    })

    $(".botaoCriarRegistro").each( (index, element) => {
        $(element).click( (elementoBotao) => {
            let botao = $(elementoBotao.currentTarget)
            console.log($(botao.currentTarget).parent().parent())
        })
    })

    $(".relacionarPixId").each( (index, element) => {
        $(element).click( (elementoBotao) => {
            let data = $(elementoBotao.currentTarget).data();
            consultarInfos(data)
        })
    })

    $(".relacionarPixIdDespesa").each( (index, element) => {
        $(element).click( (elementoBotao) => {
            let data = $(elementoBotao.currentTarget).data();
            consultarInfosDespesa(data)
        })
    })

    function consultarInfosDespesa(data) {
        MODAL_LG.modal("show")
        MODAL_TITLE.text("INFORMAÇÕES DO PIX")
        console.log(data)
        let pr = data.pr;
        let pixId = data.movimentacao_comprovante_id;
        let alertPixId = '';

        pagamento_referente = criarHtmlRefPagamento(pr, pixId, data.tabela)
        $("#limitante").empty()

        if (pr.comprovante_id !== pixId) {
            MODAL_FOOTER.prepend(`
                <div id='limitante'>
                    <form id='formPixDespesa' action="<?php echo e(route('linkarPixIdDespesa')); ?>">
                        <meta name="csrf-token-pix" content="<?php echo e(csrf_token()); ?>">
                        <input type='hidden' name='comprovante_id' value=${pixId}> 
                        <input type='hidden' name='despesa_id' value=${pr.id}> 
                        <input type='hidden' name='conta_id' value=${CONTA_ID}> 
                        <button type='submit' class='btn btn-success'>Relacionar PIX ID</button>
                    </form>
                </div>
            `)
            alertPixId = `<div class='alert alert-success'>PIX ID: <b>${pixId}</b></div>`
        }

        let html = `
            <table class='table table-stripped'>
                <thead>
                    <tr>
                        <th>Nome</th>  
                        <th>Data</th>  
                        <th>Valor</th>   
                        <th>Conta</th>  
                        <th>Local</th>  
                        <th>Pix ID</th>  
                    </tr>  
                </thead>
                <tbody>
                    <tr>
                        <td>${data.movimentacao_nome}</td>  
                        <td>${pr.data_pagamento}</td>  
                        <td>${pr.valor}</td>  
                        <td class="${pr.conta_id != CONTA_ID ? 'table-danger': ''}">${pr.conta ? pr.conta.nome : 'Não informada'}</td>  
                        <td>${pr.local.nome}</td>  
                        <td>${pr.comprovante_id ?? 'Não informado'}</td>  
                    </tr>  
                </tbody>
            </table>
        `
        atualizarPagamentoDespesa()
        MODAL_BODY.html(html)
    }

    function consultarInfos (data) {
        MODAL_LG.modal("show")
        MODAL_TITLE.text("INFORMAÇÕES DO PIX")
        console.log(data)
        let pr = data.pr;
        let pixId = data.movimentacao_comprovante_id;
        let alertPixId = '';

        pagamento_referente = criarHtmlRefPagamento(pr, pixId, data.tabela)
        $("#limitante").empty()

        if (pr.comprovante_id !== pixId) {
            MODAL_FOOTER.prepend(`
                <div id='limitante'>
                    <form id='formPix' action="<?php echo e(route('linkarPixId')); ?>">
                        <meta name="csrf-token-pix" content="<?php echo e(csrf_token()); ?>">
                        <input type='hidden' name='comprovante_id' value=${pixId}> 
                        <input type='hidden' name='pr_id' value=${pr.id}> 
                        <input type='hidden' name='conta_id' value=${CONTA_ID}> 
                        <button type='submit' class='btn btn-success'>Relacionar PIX ID</button>
                    </form>
                </div>
            `)
            alertPixId = `<div class='alert alert-success'>PIX ID: <b>${pixId}</b></div>`
        }

        let html = `
            <table class='table table-stripped'>
                <thead>
                    <tr>
                        <th>Nome</th>  
                        <th>Data</th>  
                        <th>Valor</th>  
                        <th>Confirmado</th>  
                        <th>Conta</th>  
                        <th>Pix ID</th>  
                    </tr>  
                </thead>
                <tbody>
                    <tr>
                        <td>${data.movimentacao_nome}</td>  
                        <td>${pr.data}</td>  
                        <td>${pr.valor}</td>  
                        <td class="${pr.confirmado || 'table-danger'}">${pr.confirmado || 'Não'}</td>  
                        <td class="${pr.conta_id != CONTA_ID ? 'table-danger': ''}">${pr.conta.nome}</td>  
                        <td>${pr.comprovante_id ?? 'Não informado'}</td>  
                    </tr>  
                </tbody>
            </table>

            ${pagamento_referente}
            ${alertPixId}
        `
        atualizarPagamentoRepresentantes()
        MODAL_BODY.html(html)
    }

    function criarHtmlRefPagamento (pr, pixId, tabela) {
        
        if (pr.parcela_id) {
            return  ` 
                <hr>  
                <div>PAGAMENTO REFERENTE - <b>${pr.parcela.forma_pagamento}</b></div>
                <table class='table table-stripped mt-2'>
                    <thead>
                        <tr>
                            <th>Titular</th>  
                            <th>Data</th>  
                            <th>Valor</th>  
                            <th>Representante</th>  
                        </tr>  
                    </thead>
                    <tbody>
                        <tr>
                            <td>${pr.parcela.nome_cheque ?? ''}</td>  
                            <td>${pr.parcela.data_parcela}</td>  
                            <td>${pr.parcela.valor_parcela}</td>  
                            <td>${pr.parcela.representante_id}</td>  
                        </tr>  
                    </tbody>
                </table>
                <hr>
               
            `
        } else if (tabela == 'pagamentos_representantes'){
            return  ` 
                <hr>  
                <div >PAGAMENTO PARA <b>CRÉDITO CONTA CORRENTE</b></div>
                <table class='table table-stripped mt-2'>
                    <thead>
                        <tr>
                            <th>Descrição</th>  
                            <th>Representante ID</th>  
                        </tr>  
                    </thead>
                    <tbody>
                        <tr>
                            <td>${pr.observacao ?? ''} </td>  
                            <td>${pr.representante_id}</td>  
                        </tr>  
                    </tbody>
                </table>
            `
        } else if (tabela == 'pagamentos_parceiros'){
            return  ` 
                <hr>  
                <div><b>CRÉDITO CONTA CORRENTE</b></div>
                <table class='table table-stripped mt-2'>
                    <thead>
                        <tr>
                            <th>Descrição</th>  
                            <th>Parceiro ID</th>  
                        </tr>  
                    </thead>
                    <tbody>
                        <tr>
                            <td>${pr.observacao ?? ''}</td>  
                            <td>${pr.parceiro_id}</td>  
                        </tr>  
                    </tbody>
                </table>
            `
        }
    }
    
    function atualizarPagamentoRepresentantes()
    {
        $("#formPix").submit( (e) => {
            e.preventDefault()
            let dataForm = $(e.target).serialize()
            Swal.fire({
                title: 'Tem certeza?',
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token-pix"]').attr('content')
                        },
                        url: $('#formPix').attr('action'),
                        data: dataForm,
                        dataType: 'json',
                        beforeSend: () => {
                            swal.showLoading()
                        },
                        success: (response) => {
                            console.log(response);
                            location.reload()
                        },
                        error: (jqXHR, textStatus, errorThrown) => {
                
                            var response = JSON.parse(jqXHR.responseText)
                            var errorString = ''
                            $.each( response.errors, function( key, value) {
                                errorString += '<div>' + value + '</div>'
                            });
                    
                            Swal.fire({
                                title: 'Erro',
                                icon: 'error',
                                html: errorString
                            })
                        }
                    });

                    Swal.fire('Atualizado!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Cancelado', '', 'info')
                }
            })

        })
    }

    function atualizarPagamentoDespesa()
    {
        $("#formPixDespesa").submit( (e) => {
            e.preventDefault()
            let dataForm = $(e.target).serialize()
            Swal.fire({
                title: 'Tem certeza?',
                icon: 'warning',
                showDenyButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: 'Não'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token-pix"]').attr('content')
                        },
                        url: $('#formPixDespesa').attr('action'),
                        data: dataForm,
                        dataType: 'json',
                        beforeSend: () => {
                            swal.showLoading()
                        },
                        success: (response) => {
                            // console.log(response);
                            location.reload()
                        },
                        error: (jqXHR, textStatus, errorThrown) => {
                
                            var response = JSON.parse(jqXHR.responseText)
                            var errorString = ''
                            $.each( response.errors, function( key, value) {
                                errorString += '<div>' + value + '</div>'
                            });
                    
                            Swal.fire({
                                title: 'Erro',
                                icon: 'error',
                                html: errorString
                            })
                        }
                    });

                    Swal.fire('Atualizado!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Cancelado', '', 'info')
                }
            })

        })
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/importacao/relatorioPixBradesco.blade.php ENDPATH**/ ?>