@extends('layout')
@section('title')
Procurar cheque
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Procurar cheque</li>
    </ol>
</nav>
<h3>Procurar cheque</h3>
<form id="form_procura_cheque" method="POST" action="{{ route('consulta_cheque') }}">
    @csrf

    <div class="row">
        <div class="col-lg-3 col-sm-6 form-group">
            <x-select name="tipo_select" type="number" value="{{ old('tipo_select') }}">
                <option value="valor_parcela">Valor</option>
                <option value="numero_cheque">Número</option>
                <option value="nome_cheque">Titular</option>
                <option value="data_parcela">Data</option>
                <option value="representante_id">Representante</option>
                <option value="status">Status</option>
            </x-select>
        </div>

        <div class="col-lg-7 col-sm-6 form-group">
            <x-input name="texto_pesquisa"></x-input>
        </div>
        
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" value="1" name="todosCheques" id="todosCheques">
            <label class="form-check-label" for="todosCheques">
                Todos?
            </label>
        </div>

        <div class="form-group ml-2">
            <input type="submit" class='btn btn-dark' value='Procurar'>
        </div>
    </div>

</form>
<div id="table_div"></div>
@endsection
@section('script')
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
        url: "{{route('procurarContas')}}",
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
            <form id="formAdiamento" action="{{ route('adiamentos.store') }}">
                <meta name="csrf-token-2" content="{{ csrf_token() }}">
                <p>Titular: <b>${data.nome}</b></p>
                <p>Valor do cheque: <b>${data.valor}</b></p>
                <p>Data: <b>${transformaData(data.dia)}</b></p>
                <p>Dias adiados: <b><span id="diasAdiados">15</span></b></p>

                <x-input hidden type="date" value="${data.dia}" name="parcela_data"></x-input>
                <x-input hidden type="text" value="${data.id}" name="parcela_id"></x-input>

                <div class="form-group">
                    <label for="nova_data">Informe a nova data</label>
                    <x-input type="date" value="${novaData}" name="nova_data"></x-input>
                </div>
                <div class="form-group">
                    <label for="taxa_juros">Informe a taxa de juros (%)</label>
                    <x-input type="number" value="${TAXA}" name="taxa_juros"></x-input>
                </div>
                <div class="form-group">
                    <label for="juros_totais">Valor total de juros</label>
                    <x-input readonly type="number" value="${jurosTotais}" name="juros_totais"></x-input>
                </div>

                <div class="form-group">
                    <label for="observacao">Observação</label>
                    <x-textarea name="observacao"></x-textarea>
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
                <x-select id="texto_pesquisa" name="texto_pesquisa">
                    <option value="Devolvido" selected>Devolvido</option>
                    <option value="Pago">Pago</option>
                    <option value="Adiado">Adiado</option>
                    <option value="Depositado">Depositado</option>
                    <option value="Aguardando">Aguardando</option>
                    <option value="Aguardando">Aguardando Envio</option>
                </x-select>
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
                                    <td>
                                        <x-botao-editar target='_blank' href='cheques/${element.id}/edit'></x-botao-editar>
                                    </td>
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
                                        <x-botao-editar target='_blank' href='cheques/${element.id}/edit'></x-botao-editar>
                                        
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
                                        <x-botao-editar target='_blank' href='cheques/${element.id}/edit'></x-botao-editar>
                                        
                                    </td>
                                </tr>
                            `
                        }

                    })

                    $("#table_div").html(`
                        <x-table>
                            <x-table-header>
                                <tr>
                                    <th colspan=11>Número total de resultado: ${response.Cheques.length}</th>
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
                            </x-table-header>
                            <tbody>
                                ${tableBody}
                            </tbody>
                        </x-table>
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
                            <td>${element.conta.nome}</td>
                            <td>${element.forma_pagamento}</td>
                            <td>${element.confirmado ? 'Sim' : 'Não'}</td>
                            <td>${moeda.format(valorTratado)}</td>
                        <tr>
                    `
                    totalPago += valorTratado;
                })
                $("#modal-header2").html(`
                    <h3>Pagamentos</h3> 
                    <div class='btn btn-success' id='btn_adicionar_pagamento' data-id='${parcela_id}' data-totalPago='${totalPago}'>
                        <span class='fas fa-plus'></span>
                    </div>`)
                $("#modal-body2").html(`
                    <x-table>
                        <x-table-header>
                            <tr>
                                <th>Data</th>
                                <th>Conta</th>
                                <th>Forma do Pagamento</th>
                                <th>Confirmado?</th>
                                <th>Valor</th>
                            </tr>
                        </x-table-header>
                        <tbody>
                            ${tableBodyPagamentos}
                        </tbody>
                        <t-foot>
                            <tr>
                                <td colspan=4>Total pago</td>
                                <td>${moeda.format(totalPago)}</td>
                            </tr>
                        </t-foot>
                    </x-table>
                    
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
            <form id="formLancarRecebimento" action="{{ route('recebimentoCreateApi') }}">   
                <meta name="csrf-token-3" content="{{ csrf_token() }}">
                <input type='hidden' value='${parcela_id}' name='parcela_id' id='parcela_id'> 
                <div class="row">
                    <div class="col-6">
                        <x-form-group type="date" name="data">Data</x-form-group>
                    </div>
                    <div class="col-6">
                        <x-form-group name="valor">Valor</x-form-group>
                    </div>

                    <div class="col-6 form-group">
                        <label for="conta_id">Conta</label>
                        <x-select name="conta_id">
                            <option></option>
                            ${optionContas}
                        </x-select>
                    </div>
                    <div class="col-6 form-group">
                        <label for="forma_pagamento">Forma de Pagamento</label>
                        <x-select name="forma_pagamento">
                            <option></option>
                            ${optionFormasDePagamento}
                        </x-select>
                    </div>
                    <div class="col-6 form-group">
                        <label for="confirmado">Pagamento Confirmado?</label>
                        <x-select name="confirmado">
                            <option></option>
                            <option value=1> Sim </option>
                            <option value=0> Não </option>
                        </x-select>
                    </div>

                    <div class="col-12 form-group">
                        <label for="observacao">Observação</label>
                        <x-text-area name="observacao" type="text"></x-text-area>
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
                    <x-table>
                        <x-table-header>
                            <tr>
                                <th>Data</th>
                                <th>Descrição</th>
                            </tr>
                        </x-table-header>
                        <tbody>
                            ${tableBodyPagamentos}
                        </tbody>
                    </x-table>
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
@endsection
