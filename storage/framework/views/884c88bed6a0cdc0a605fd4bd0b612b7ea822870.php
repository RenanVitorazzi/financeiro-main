<?php $__env->startSection('title'); ?>
Procurar cheque
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Procurar cheque</li>
    </ol>
</nav>
<h3>Procurar cheque</h3>
<form id="form_procura_cheque" method="POST" action="<?php echo e(route('consulta_cheque')); ?>">
    <?php echo csrf_field(); ?>

    <div class="row">
        <div class="col-lg-2 col-md-6 col-sm-6 form-group">
            <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'tipo_select','type' => 'number','value' => ''.e(old('tipo_select')).'']); ?>
                <option value="valor_parcela">Valor</option>
                <option value="numero_cheque">Número</option>
                <option value="nome_cheque">Titular</option>
                <option value="data_parcela">Data</option>
                
                
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
        </div>

        <div class="col-lg-7 col-md-6 col-sm-6 form-group">
            <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'texto_pesquisa']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
        </div>
        
        <div class="form-check mt-2 col-lg-1 col-md-6 col-sm-6 form-group">
            <input class="form-check-input" type="checkbox" value="1" name="todosCheques" id="todosCheques">
            <label class="form-check-label" for="todosCheques">
                Todos?
            </label>
        </div>

        <div class="form-group col-lg-2 col-md-6 col-sm-6">
            <input type="submit" class='btn btn-dark' value='Procurar'>
        </div>
    </div>

</form>
<div id="table_div"></div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    const TAXA = 3;
    const MODAL = $("#modal")
    const MODAL_BODY = $("#modal-body")

    const ARRAY_FORMAS_DE_PAGAMENTO = ['Pix', 'Cheque', 'TED', 'Depósito', 'DOC', 'Dinheiro']
    let optionFormasDePagamento = ''
    ARRAY_FORMAS_DE_PAGAMENTO.forEach(element => {
        optionFormasDePagamento += `<option value='${element}'> ${element} </option>`
    })

    let optionContas  = ''

    $.ajax({
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token-2"]').attr('content')
        },
        url: "<?php echo e(route('procurarContas')); ?>",
        dataType: 'json',
        success: (response) => {

            response.forEach(element => {
                optionContas += `<option value='${element.id}'> ${element.nome} </option>`
            })
            
        }
    });

    procurarCheque()

    function adiarCheque(element) {

        let data = $(element).data()
        let novaData = addDays(data.dia, 15)
        let jurosTotais = calcularNovosJuros(element, 15)
        MODAL.modal("show")

        $("#modal-title").html("Prorrogação")

        MODAL_BODY.html(`
            <form id="formAdiamento" action="<?php echo e(route('adiamentos.store')); ?>">
                <meta name="csrf-token-2" content="<?php echo e(csrf_token()); ?>">
                <p>Titular: <b>${data.nome}</b></p>
                <p>Valor do cheque: <b>${data.valor}</b></p>
                <p>Data: <b>${transformaData(data.dia)}</b></p>
                <p>Dias adiados: <b><span id="diasAdiados">15</span></b></p>

                <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['hidden' => true,'type' => 'date','value' => '${data.dia}','name' => 'parcela_data']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['hidden' => true,'type' => 'text','value' => '${data.id}','name' => 'parcela_id']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>

                <div class="form-group">
                    <label for="nova_data">Informe a nova data</label>
                    <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'date','value' => '${novaData}','name' => 'nova_data']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="taxa_juros">Informe a taxa de juros (%)</label>
                    <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'number','value' => '${TAXA}','name' => 'taxa_juros']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="juros_totais">Valor total de juros</label>
                    <?php if (isset($component)) { $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Input::class, []); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['readonly' => true,'type' => 'number','value' => '${jurosTotais}','name' => 'juros_totais']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7)): ?>
<?php $component = $__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7; ?>
<?php unset($__componentOriginal11c02d5af8eef3b9ca8b54c54983d5cb581e68d7); ?>
<?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="observacao">Observação</label>
                    <?php if (isset($component)) { $__componentOriginalada24a059c331be0784ec187913c2ecfacd51890 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Textarea::class, []); ?>
<?php $component->withName('textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'observacao']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalada24a059c331be0784ec187913c2ecfacd51890)): ?>
<?php $component = $__componentOriginalada24a059c331be0784ec187913c2ecfacd51890; ?>
<?php unset($__componentOriginalada24a059c331be0784ec187913c2ecfacd51890); ?>
<?php endif; ?>
                </div>
            </form>
        `)

        $("#taxa_juros, #nova_data").change( () => {
            let dataNova = $("#nova_data").val()
            let diferencaDias = calcularDiferencaDias(data.dia, dataNova)

            let jurosNovos = calcularNovosJuros(element, diferencaDias)

            $("#diasAdiados").html(diferencaDias)
            $("#juros_totais").val(jurosNovos)
        })

    }

    $(".modal-footer > .btn-primary").click( () => {
        let dataForm = $("#formAdiamento").serialize()

        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token-2"]').attr('content')
            },
            url: $('#formAdiamento').attr('action'),
            data: dataForm,
            dataType: 'json',
            beforeSend: () => {
                swal.showLoading()
            },
            success: (response) => {

                Swal.fire({
                    title: response.title,
                    icon: response.icon,
                    text: response.text
                })

                MODAL.modal("hide")
                $("#form_procura_cheque").submit()
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
    })

    function addDays (date, days) {
        var result = new Date(date)
        result.setDate(result.getDate() + days)
        return result.toISOString().slice(0,10)
    }

    function calcularNovosJuros (element, dias) {
        let taxa = $("#taxa_juros").val();
        let valor_cheque = $(element).data("valor")
        let porcentagem = taxa / 100 || TAXA / 100 ;

        return ( ( (valor_cheque * porcentagem) / 30 ) * dias).toFixed(2)
    }

    function calcularDiferencaDias (dataAntiga, dataNova) {
        let date1 = new Date(dataAntiga)
        let date2 = new Date(dataNova)
        if (date1.getTime() > date2.getTime()) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'A data de adiamento deve ser maior que a data do cheque!'
            })
        }

        const diffTime = Math.abs(date2 - date1)
        return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    }

    $("#tipo_select").change( (e) => {
        if (e.target.value==='data_parcela') {
            $('#texto_pesquisa').get(0).type = 'date';
            return
        }
        if (e.target.value==='status') {
            $('#texto_pesquisa').replaceWith(`
                <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['id' => 'texto_pesquisa','name' => 'texto_pesquisa']); ?>
                    <option value="Devolvido" selected>Devolvido</option>
                    <option value="Pago">Pago</option>
                    <option value="Adiado">Adiado</option>
                    <option value="Depositado">Depositado</option>
                    <option value="Aguardando">Aguardando</option>
                    <option value="Aguardando">Aguardando Envio</option>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
            `);
            return
        }
        $('#texto_pesquisa').get(0).type = 'text';
    })

    function procurarCheque () {

        $("#form_procura_cheque").submit( (e) => {

            e.preventDefault()
            let dataForm = $(e.target).serialize()

            $.ajax({
                type: 'GET',
                url: e.target.action,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: dataForm,
                dataType: 'json',
                beforeSend: () => {
                    Swal.showLoading()
                },
                success: (response) => {
                    let usuario = response.user
                    let tableBody = ''
                    let arrayNomeBlackList = response.blackList[0].nome_cheque ? response.blackList[0].nome_cheque.split(',') : []
                    let arrayClienteIdBlackList = response.blackList[0].cliente_id ? response.blackList[0].cliente_id.split(',') : []

                    response.Cheques.forEach(element => {
                        let dataTratada = transformaData(element.data_parcela)
                        let ondeEstaCheque = carteiraOuParceiro(element.parceiro_id, element.nome_parceiro)
                        let numero_banco = tratarNulo(element.numero_banco)
                        let numero_cheque = tratarNulo(element.numero_cheque)
                        let representante = tratarNulo(element.nome_representante)

                        let botaoPagamentos = `
                            <div class="btn btn-dark btn-pagamentos" title="Pagamentos" data-id="${element.id}"> 
                                <i class="fas fa-money-bill"></i> 
                            </div>
                        `

                        let botaoHistorico = `
                            <div class="btn btn-dark btn-historico" title="Histórico" data-id="${element.id}"> 
                                <i class="fas fa-book"></i> 
                            </div>
                        `

                        let ClienteBlackList = arrayNomeBlackList.includes(element.nome_cheque) || arrayClienteIdBlackList.includes(element.cliente_id)

                        let botaoAdiar = ClienteBlackList ?
                            `<div class="btn btn-danger btn-adiar"
                                title="Adiou e o mesmo cheque foi devolvido"
                                data-id="${element.id}"
                                data-dia="${element.data_parcela}"
                                data-valor="${element.valor_parcela}"
                                data-nome="${element.nome_cheque}"
                            > <i class="fas fa-exclamation-triangle"></i> </div>`
                            :
                            `<div class="btn btn-dark btn-adiar"
                                data-id="${element.id}"
                                data-dia="${element.data_parcela}"
                                data-valor="${element.valor_parcela}"
                                data-nome="${element.nome_cheque}"
                            > <i class="far fa-clock"></i> </div>`

                        let botaoEditar = `<?php if (isset($component)) { $__componentOriginal13702a75d66702067dad623af293364e28e151a7 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\BotaoEditar::class, []); ?>
<?php $component->withName('botao-editar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['target' => '_blank','href' => 'cheques/${element.id}/edit']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal13702a75d66702067dad623af293364e28e151a7)): ?>
<?php $component = $__componentOriginal13702a75d66702067dad623af293364e28e151a7; ?>
<?php unset($__componentOriginal13702a75d66702067dad623af293364e28e151a7); ?>
<?php endif; ?>`
                            
                        if (usuario.is_representante) {
                            botaoEditar = ''
                            botaoAdiar = ''
                        }

                        if (element.status === 'PAGO' || element.status === 'DEPOSITADO') {
                            tableBody += `
                                <tr>
                                    <td>${element.nome_cheque}</td>
                                    <td>${dataTratada}</td>
                                    <td>${element.valor_parcela_tratado}</td>
                                    <td>${representante}</td>
                                    <td>${numero_banco}</td>
                                    <td>${numero_cheque}</td>
                                    <td>${ondeEstaCheque}</td>
                                    <td>${element.status}</td>
                                    <td>${botaoPagamentos}</td>
                                    <td>${botaoHistorico}</td>
                                    <td>${botaoEditar}</td>
                                </tr>
                            `
                        } else if (element.adiamento_id) {
                            tableBody += `
                                <tr>
                                    <td>${element.nome_cheque}</td>
                                    <td><span class="text-muted">(${dataTratada})</span> ${transformaData(element.nova_data)}</td>
                                    <td>${element.valor_parcela_tratado}</td>
                                    <td>${representante}</td>
                                    <td>${numero_banco}</td>
                                    <td>${numero_cheque}</td>
                                    <td>${ondeEstaCheque}</td>
                                    <td>${element.status}</td>
                                    <td>${botaoPagamentos}</td>
                                    <td>${botaoHistorico}</td>
                                    <td>
                                        ${botaoAdiar}
                                        ${botaoEditar}
                                    </td>
                                </tr>
                            `
                        } else {
                            tableBody += `
                                <tr>
                                    <td>${element.nome_cheque}</td>
                                    <td>${dataTratada}</td>
                                    <td>${element.valor_parcela_tratado}</td>
                                    <td>${representante}</td>
                                    <td>${numero_banco}</td>
                                    <td>${numero_cheque}</td>
                                    <td>${ondeEstaCheque}</td>
                                    <td>${element.status}</td>
                                    <td>${botaoPagamentos}</td>
                                    <td>${botaoHistorico}</td>
                                    <td>
                                        ${botaoAdiar}
                                        ${botaoEditar}
                                    </td>
                                </tr>
                            `
                        }

                    })

                    let imp = response.imprimir;
                    
                    $("#table_div").html(`
                        <?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
                            <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
                                <tr>
                                    <th colspan=11>
                                        Número total de resultado: ${response.Cheques.length}
                                        <a target='_blank'
                                            href="/pdf_imprimir_procura_cheque/${imp.tipo_select}/${imp.texto_pesquisa}/${imp.tudo}"
                                            class='btn btn-light float-right'>
                                            Imprimir 
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Titular</th>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Representante</th>
                                    <th>Banco</th>
                                    <th>Nº</th>
                                    <th>Parceiro</th>
                                    <th>Status</th>
                                    <th>Pagamentos</th>
                                    <th>Histórico</th>
                                    <th>Ações</th>
                                </tr>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
                            <tbody>
                                ${tableBody}
                            </tbody>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
                    `)

                    $(".btn-adiar").each( (index, element) => {
                        $(element).click( () => {
                            adiarCheque(element)
                        })
                    });

                    $(".btn-pagamentos").each( (index, element) => {
                        $(element).click( () => {
                            procurarPagamentos($(element).data('id'))
                        })
                    });

                    $(".btn-historico").each( (index, element) => {
                        $(element).click( () => {
                            procurarHistorico($(element).data('id'))
                        })
                    });

                    Swal.close()
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    console.error(jqXHR)
                    console.error(textStatus)
                    console.error(errorThrown)
                }
            });
        })
    }

    function transformaData (data) {
        var parts = data.split("-");
        return parts[2]+'/'+parts[1]+'/'+parts[0];
    }

    function carteiraOuParceiro (id, nome) {
        if (id === null) {
            return 'CARTEIRA'
        }

        return nome
    }

    function tratarNulo (valor) {
        if (valor === null) {
            return '';
        }
        return valor
    }

    function procurarPagamentos(parcela_id) {
        tableBodyPagamentos = ``;
        let totalPago = 0;
        $.ajax({
            type: 'GET',
            url: '/procurar_pagamento',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'parcela_id': parcela_id
            },
            dataType: 'json',
            beforeSend: () => {
                Swal.showLoading()
            },
            success: (response) => {
                
                response.forEach(element => {
                    let valorTratado = parseFloat(element.valor)

                    tableBodyPagamentos += `
                        <tr class = ${element.confirmado ? '' : 'table-danger'}>
                            <td>${transformaData(element.data)}</td>
                            <td>${element.conta ? element.conta.nome : 'NI'}</td>
                            <td>${element.forma_pagamento}</td>
                            <td>${element.confirmado ? 'Sim' : 'Não'}</td>
                            <td>${moeda.format(valorTratado)}</td>
                        <tr>
                    `
                    totalPago += valorTratado;
                })

                $("#modal-header2").html(`
                    <h3>Pagamentos</h3> 
                `)

                // $("#modal-header2").html(`
                //     <h3>Pagamentos</h3> 
                //     <div class='btn btn-success' id='btn_adicionar_pagamento' data-id='${parcela_id}' data-totalPago='${totalPago}'>
                //         <span class='fas fa-plus'></span>
                //     </div>
                // `)

                $("#modal-body2").html(`
                    <?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
                            <tr>
                                <th>Data</th>
                                <th>Conta</th>
                                <th>Forma do Pagamento</th>
                                <th>Confirmado?</th>
                                <th>Valor</th>
                            </tr>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
                        <tbody>
                            ${tableBodyPagamentos}
                        </tbody>
                        <t-foot>
                            <tr>
                                <td colspan=4>Total pago</td>
                                <td>${moeda.format(totalPago)}</td>
                            </tr>
                        </t-foot>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
                    
                    <div id='campo_pagamento' style='display:none' class='mt-2'></div>
                `)

                $("#modal2").modal('show')
                $("#btn_adicionar_pagamento").click((e) => {
                    adicionarPagamento(e.currentTarget)
                })
                Swal.close()
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.error(jqXHR)
                console.error(textStatus)
                console.error(errorThrown)
            }
        });
    } 

    let moeda = Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'BRL',
    });

    function adicionarPagamento (botaoAdicionar) {

        let parcela_id = $(botaoAdicionar).data('id')
        let campo_pagamento = $("#campo_pagamento")
        $(botaoAdicionar).fadeOut()
        
        campo_pagamento.html(`
            <form id="formLancarRecebimento" action="<?php echo e(route('recebimentoCreateApi')); ?>">   
                <meta name="csrf-token-3" content="<?php echo e(csrf_token()); ?>">
                <input type='hidden' value='${parcela_id}' name='parcela_id' id='parcela_id'> 
                <div class="row">
                    <div class="col-6">
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['type' => 'date','name' => 'data']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['type' => 'date','name' => 'data']); ?>Data <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    </div>
                    <div class="col-6">
                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.form-group','data' => ['name' => 'valor']]); ?>
<?php $component->withName('form-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'valor']); ?>Valor <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                    </div>

                    <div class="col-6 form-group">
                        <label for="conta_id">Conta</label>
                        <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'conta_id']); ?>
                            <option></option>
                            ${optionContas}
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
                    </div>
                    <div class="col-6 form-group">
                        <label for="forma_pagamento">Forma de Pagamento</label>
                        <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'forma_pagamento']); ?>
                            <option></option>
                            ${optionFormasDePagamento}
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
                    </div>
                    <div class="col-6 form-group">
                        <label for="confirmado">Pagamento Confirmado?</label>
                        <?php if (isset($component)) { $__componentOriginal9664ac210be45add4be058f3177c16028511e71a = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Select::class, []); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'confirmado']); ?>
                            <option></option>
                            <option value=1> Sim </option>
                            <option value=0> Não </option>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a)): ?>
<?php $component = $__componentOriginal9664ac210be45add4be058f3177c16028511e71a; ?>
<?php unset($__componentOriginal9664ac210be45add4be058f3177c16028511e71a); ?>
<?php endif; ?>
                    </div>

                    <div class="col-12 form-group">
                        <label for="observacao">Observação</label>
                        <?php if (isset($component)) { $__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TextArea::class, []); ?>
<?php $component->withName('text-area'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['name' => 'observacao','type' => 'text']); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405)): ?>
<?php $component = $__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405; ?>
<?php unset($__componentOriginal3d2c91b5536e3d54aed1822705c324a24f801405); ?>
<?php endif; ?>
                    </div>
                    
                </div>
                <div class='btn btn-success' id='btnEnviarFormRecebimento'>Enviar</div>
            </form>
        `)
        campo_pagamento.fadeIn()    

        enviarFormRecebimento()
    }

    function enviarFormRecebimento() {
        
        $('#btnEnviarFormRecebimento').click( () => {
    
            let dataFormRecebimento = $("#formLancarRecebimento").serialize()
            
            console.log(dataFormRecebimento);
            console.log($('#formLancarRecebimento').attr('action'))

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token-3"]').attr('content')
                },
                url: $('#formLancarRecebimento').attr('action'),
                data: dataFormRecebimento,
                beforeSend: () => {
                    swal.showLoading()
                },
                success: (response) => {
                    console.log(response)
                    Swal.fire({
                        title: 'Sucesso',
                        icon: 'success',
                        text: 'Pagamento cadastrado'
                    })
    
                    MODAL.modal("hide")
                    procurarPagamentos($("#parcela_id").val())
                    $("#form_procura_cheque").submit()
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    var response = JSON.parse(jqXHR.responseText)
                    var errorString = ''
                    $.each( response.errors, function( key, value) {
                        errorString += '<div>' + value + '</div>'
                    });
                    console.log(response)
                    Swal.fire({
                        title: 'Erro',
                        icon: 'error',
                        html: errorString
                    })
                }
            });
    
        }) 
    }
    
    function procurarHistorico(parcela_id) {

        tableBodyPagamentos = ``;

        $.ajax({
            type: 'GET',
            url: '/historico_parcela',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'parcela_id': parcela_id
            },
            dataType: 'json',
            beforeSend: () => {
                Swal.showLoading()
            },
            success: (response) => {
                
                response.forEach(element => {

                    tableBodyPagamentos += `
                        <tr>
                            <td>${transformaData(element.data)}</td>
                            <td>${element.desc}</td>
                        <tr>
                    `
                })
                $("#modal-header2").html('Histórico do cheque')
                $("#modal-body2").html(`
                    <?php if (isset($component)) { $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\Table::class, []); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
                        <?php if (isset($component)) { $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36 = $component; } ?>
<?php $component = $__env->getContainer()->make(App\View\Components\TableHeader::class, []); ?>
<?php $component->withName('table-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
                            <tr>
                                <th>Data</th>
                                <th>Descrição</th>
                            </tr>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36)): ?>
<?php $component = $__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36; ?>
<?php unset($__componentOriginalc30ad8c2a191ad4361a1cb232afac54beb39ce36); ?>
<?php endif; ?>
                        <tbody>
                            ${tableBodyPagamentos}
                        </tbody>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6)): ?>
<?php $component = $__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6; ?>
<?php unset($__componentOriginale53a9d2e6d6c51019138cc2fcd3ba8ac893391c6); ?>
<?php endif; ?>
                `)

                $("#modal2").modal('show')
                
                Swal.close()
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.error(jqXHR)
                console.error(textStatus)
                console.error(errorThrown)
            }
        });
    }

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\CAIXA\Desktop\Sistema\financeiro-main\resources\views/cheque/procura_cheque.blade.php ENDPATH**/ ?>