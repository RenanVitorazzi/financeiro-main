@extends('layout')
@section('title')
Relatório PIX BRADESCO
@endsection
@section('body')
<style>
    .pointer{
        cursor: pointer;
    }
</style>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('import') }}">Importação</a></li>
        <li class="breadcrumb-item active" aria-current="page">PIX BRADESCO</li>
    </ol>
</nav>
<div class='mb-2 d-flex justify-content-between'>
    <h3> Relatório PIX BRADESCO </h3>
    <h5>Conta: {{ $import->conta->nome }}</h5>
</div>

<x-table class="table-light" id='table-pix'>
    <x-table-header>

        <tr>
            <th>#</th>
            <th>Data</th>
            <th>Descrição</th>
            <th>Débito</th>
            <th>Crédito</th>
            <th>Lançamentos</th>
        </tr>
    </x-table-header>
    <tbody>
        @forelse ($import->arrayDados as $index => $item)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>@data($item['data'])</td>
                <td>{{$item['nome']}}</td>
                @if ($item['tipo'] == 'Crédito')
                    <td></td>
                    <td>@moeda($item['valor'])</td>
                    <td>
                        @forelse ($item['pagamentosRepresentantes'] as $pr)
                            @if($pr->comprovante_id == $item['comprovante_id'])
                                <div class="alert alert-success pointer border border-success relacionarPixId" 
                                        data-movimentacao_nome="{{$item['nome']}}"
                                        data-movimentacao_comprovante_id="{{$item['comprovante_id']}}"
                                        data-pr="{{$pr}}"
                                        data-tabela="pagamentos_representantes"
                                    >
                                    Pagamento relacionado pelo <b>PIX ID</b>
                                    <br>
                                    <i class='fas fa-check fa-lg mt-2'></i>
                                </div>
                            @elseif ($item['valor'] == $pr->valor && $item['data'] == $pr->data && $pr->comprovante_id == NULL)
                                <div class="alert alert-success" >
                                    Pagamento relacionado pela <b>data</b> e <b>valor</b>
                                    <br>
                                    <span class='btn btn-success mt-2 relacionarPixId'
                                        data-movimentacao_nome="{{$item['nome']}}"
                                        data-movimentacao_comprovante_id="{{$item['comprovante_id']}}"
                                        data-pr="{{$pr}}"
                                        data-tabela="pagamentos_representantes"
                                        >Relacionar por PIX ID
                                    </span>
                                    {{-- <i class='fas fa-check fa-lg mt-2'></i> --}}
                                </div>
                            @endif
                        @empty
                            <span class='btn btn-dark'>
                                <span>Lançar recebimento <i class='fas fa-plus ml-2'></i></span>
                            </span>
                            <span class='btn btn-danger botaoIgnorar'>
                                <span>Ignorar <i class='fas fa-trash ml-2'></i></span>
                            </span>
                        @endif

                    </td>
                @elseif ($item['tipo'] == 'Débito')
                    <td>@moeda($item['valor'])</td>
                    <td></td>
                    <td>
                        @forelse ($item['pagamentosParceiros'] as $pr)
                            {{-- @dd($pr) --}}
                            <p>
                                @if($pr->comprovante_id == $item['comprovante_id'])
                                    <div class="alert alert-success pointer border border-success relacionarPixId" 
                                        data-movimentacao_nome="{{$item['nome']}}"
                                        data-movimentacao_comprovante_id="{{$item['comprovante_id']}}"
                                        data-pr="{{$pr}}"
                                        data-tabela="pagamentos_parceiros"
                                    >
                                        Pagamento relacionado pelo <b>PIX ID</b>
                                        <br>
                                        <i class='fas fa-check fa-lg mt-2'></i>
                                    </div>
                                @elseif ($item['valor'] == $pr->valor && $item['data'] == $pr->data && $pr->comprovante_id == NULL)
                                    <div class="alert alert-success" >
                                        Pagamento relacionado pela <b>data</b> e <b>valor</b>
                                        <br>
                                        <span class='btn btn-success mt-2 relacionarPixId'>Relacionar por PIX ID</span>
                                        {{-- <i class='fas fa-check fa-lg mt-2'></i> --}}
                                    </div>
                                @endif
                            </p>
                        @empty
                            <a class="btn btn-dark" target="_blank"
                                href="{{ route('criarDespesaImportacao', [
                                    'data' => $item['data'],
                                    'descricao' => $item['nome'],
                                    'valor' => $item['valor'],
                                    'conta' => $import->conta->id
                                ])}}"
                            >
                                Despesa <i class='fas fa-plus ml-2'></i>
                            </a>
                            <a class="btn btn-dark" target="_blank"
                                href="{{route('criarRecebimentoImportacao', [
                                    'data' => $item['data'],
                                    'descricao' => $item['nome'],
                                    'valor' => $item['valor'],
                                    'conta' => $import->conta->id,
                                    'forma_pagamento' => 'Pix',
                                    'confirmado' => 1,
                                    'tipo_pagamento' => 1,
                                    'comprovante_id' => $item['comprovante_id']
                                ])}}"
                            >
                                Pagamento parceiro<i class='fas fa-plus ml-2'></i>
                            </a>

                            <span class='btn btn-danger botaoIgnorar'>
                                <span>Ignorar <i class='fas fa-trash ml-2'></i></span>
                            </span>
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan=5>Nenhum registro</td>
            </tr>
        @endforelse
    </tbody>
</x-table>
<input type="hidden" value="{{$import->conta->id}}" id="conta_id">

@endsection
@section('script')
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
                    <form id='formPix' action="{{ route('linkarPixId') }}">
                        <meta name="csrf-token-pix" content="{{ csrf_token() }}">
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
</script>
@endsection
