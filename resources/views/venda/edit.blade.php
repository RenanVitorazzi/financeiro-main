@extends('layout')
@section('title')
Editar vendas
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        @if (!auth()->user()->is_representante)
        <li class="breadcrumb-item"><a href="{{ route('representantes.index') }}">Representantes</a></li>
        @endif
        <li class="breadcrumb-item"><a href="{{ route('venda.show', $venda->representante_id) }}">Vendas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar</li>
    </ol>
</nav>
    <form method="POST" action="{{ route('venda.update', $venda->id)}}" id='formUpdateVenda'>
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <x-form-group name="data_venda" type="date" autofocus value="{{ $venda->data_venda }}" >Data</x-form-group>
            </div>
            <div class="col-md-6 form-group">
                <label for="cliente_id">Cliente</label>
                <select name="cliente_id" id="cliente_id" class="form-control">
                    <option></option>
                    @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $venda->cliente_id) == $cliente->id ? 'selected': '' }} >
                        {{ $cliente->pessoa->nome }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="representante_id">Representante</label>
                <select name="representante_id" id="representante_id" class="form-control" required>
                    <option></option>
                    @foreach ($representantes as $representante)
                        <option value="{{ $representante->id }}"
                            {{ old('representante_id', $venda->representante_id) == $representante->id ? 'selected': '' }} >
                            {{ $representante->pessoa->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <x-table class="table-striped table-bordered table-dark">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Quantidade</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Peso</td>
                        <td><x-input type="number" name="peso" step="0.001" value="{{ old('peso', $venda->peso)}}"></x-input></td>
                        <td><x-input type="number" name="cotacao_peso" step="0.01" value="{{ old('cotacao_peso', $venda->cotacao_peso)}}"></x-input></td>
                    </tr>
                    <tr>
                        <td>Fator</td>
                        <td><x-input type="number" name="fator" step="0.01" value="{{ old('fator', $venda->fator)}}"></x-input></td>
                        <td><x-input type="number" name="cotacao_fator" step="0.01" value="{{ old('cotacao_fator', $venda->cotacao_fator)}}"></x-input></td>
                    </tr>
                    <tr>
                        <td colspan='2'>Total</td>
                        <td><x-input name="valor_total" type="number" step="0.01" value="{{ old('valor_total', $venda->valor_total) }}" ></x-input></td>
                    </tr>
                </tbody>
            </x-table>

            <div class="col-sm-6 col-md-4 col-lg-3 form-group">
                <label for="metodo_pagamento">Método de Pagamento</label>
                <x-select name="metodo_pagamento" required>
                    <option value=""></option>
                    @foreach ($metodo_pagamento as $metodo)
                        <option {{ $venda->metodo_pagamento == $metodo ? 'selected' : '' }} value="{{ $metodo }}">{{ $metodo }}</option>
                    @endforeach
                </x-select> 
            </div>
        </div> 
        {{-- <div id="campoQtdParcelas" class="row">
            @if ($venda->metodo_pagamento === 'Cheque')
                <div class='form-group col-md-6'>
                    <label for="parcelas">Informe o período de prazo</label>
                    <input class="form-control" id="prazo" type="number" value="30">
                </div>
                <div class='form-group col-md-6'>
                    <label for="parcelas">Informe a quantidade de parcelas</label>
                    <input class="form-control" name="parcelas" id="parcelas" type="number" value="{{ $venda->parcelas }}">
                </div>
            @endif
        </div> --}}
        <div id="infoCheques" class="row">
           
            @foreach ($venda->parcela as $parcela)
                <div class="col-4">
                    <div class="card mb-4 card-hover">
                        <div class="card-body">
                            <input type="hidden" value='{{$parcela->id}}' name='parcela_id[{{$loop->index}}]'>
                            <h5 class="card-title mb-4"> 
                                <div class="d-flex justify-content-between">
                                    <div>{{ $loop->iteration }}ª Parcela</div>
                                </div>
                            </h5>
                            <div class='form-group'>
                                <label for="forma_pagamento[{{$loop->index}}]">Informe a forma de pagamento</label>
                                <x-select class="form-control" name="forma_pagamento[{{$loop->index}}]" data-index="{{$loop->index}}" >
                                    <option value=""></option>
                                    @foreach ($forma_pagamento as $forma)
                                        <option value="{{ $forma }}" {{ $forma == old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) ? 'selected' : '' }}>
                                            {{ $forma }}
                                        </option>
                                    @endforeach
                                </x-select>
                                @error('forma_pagamento.'.$loop->index)
                                    <div class="invalid-feedback d-inline">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group {{old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Cheque' ? '' : 'd-none'}}" id="groupNome_{{$loop->index}}">
                                <label for="nome_cheque[{{$loop->index}}]">Nome</label>
                                <div class="d-flex">
                                    <x-input type="text" name="nome_cheque[{{$loop->index}}]" 
                                        id="nome_cheque[{{$loop->index}}]" 
                                        class="form-control primeiroInputNome {{ $errors->has('nome_cheque.'.$loop->index) ? 'is-invalid' : ''}}"
                                        value="{{old('nome_cheque.'.$loop->index, $parcela->nome_cheque)}}"
                                    ></x-input>
                                    @error('nome_cheque.'.$loop->index)
                                        <div class="invalid-feedback d-inline">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group {{old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Cheque' ? '' : 'd-none'}}" id="groupBanco_{{$loop->index}}">
                                <label for="numero_banco[{{$loop->index}}]">Número do Banco</label>
                                <div class="d-flex">
                                    <x-input type="text" 
                                        name="numero_banco[{{$loop->index}}]" 
                                        id="numero_banco[{{$loop->index}}]" 
                                        class="form-control {{ $errors->has('numero_banco.'.$loop->index) ? 'is-invalid' : ''}}" 
                                        value="{{ old('numero_banco.'.$loop->index, $parcela->numero_banco) }}"
                                    ></x-input>
                                    @error('numero_banco.'.$loop->index)
                                        <div class="invalid-feedback d-inline">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group {{old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Cheque' ? '' : 'd-none'}}" id="groupNumero_{{$loop->index}}">
                                <label for="numero_cheque[{{$loop->index}}]">Número do Cheque</label>
                                <div class="d-flex">
                                    <x-input type="text" 
                                        name="numero_cheque[{{$loop->index}}]" 
                                        id="numero_cheque[{{$loop->index}}]" 
                                        class="form-control {{ $errors->has('numero_cheque.'.$loop->index) ? 'is-invalid' : ''}}"
                                        value="{{old('numero_cheque.'.$loop->index, $parcela->numero_cheque)}}"
                                    ></x-input>
                                    @error('numero_cheque.'.$loop->index)
                                        <div class="invalid-feedback d-inline">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="data_parcela[{{$loop->index}}]">Data da Parcela</label>
                                <x-input type="date" 
                                    name="data_parcela[{{$loop->index}}]" 
                                    id="data_parcela[{{$loop->index}}]" 
                                    class="form-control {{ $errors->has('data_parcela.'.$loop->index) ? 'is-invalid' : ''}}" 
                                    value="{{old('data_parcela.'.$loop->index, $parcela->data_parcela)}}"
                                ></x-input>
                                @error('data_parcela.'.$loop->index)
                                    <div class="invalid-feedback d-inline">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="valor_parcela[{{$loop->index}}]">Valor</label>
                                <x-input type="number" 
                                    name="valor_parcela[{{$loop->index}}]" 
                                    id="valor_parcela[{{$loop->index}}]" 
                                    class="form-control primeiroInputValor {{ $errors->has('valor_parcela.'.$loop->index) ? 'is-invalid' : ''}}" 
                                    value="{{old('valor_parcela.'.$loop->index, $parcela->valor_parcela)}}"
                                ></x-input>
                                @error('valor_parcela.'.$loop->index)
                                    <div class="invalid-feedback d-inline">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="status[{{$loop->index}}]">Status</label>
                                <x-select name="status[{{$loop->index}}]" id="status[{{$loop->index}}]" class="{{ $errors->has('status.'.$loop->index) ? 'is-invalid' : ''}}">
                                    <option value=""></option>
                                    @if(old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Cheque')
                                        <option value="Aguardando" {{ old('status.'.$loop->index, $parcela->status) == 'Aguardando' ? 'selected' : ''}}>Aguardando Depósito</option>
                                        <option value="Aguardando Envio" {{ old('status.'.$loop->index, $parcela->status) == 'Aguardando Envio' ? 'selected' : ''}}>Aguardando Envio</option>
                                    @elseif(old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Pix' || old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Dinheiro')
                                        <option value="Pago">Pago</option>
                                    @elseif(old('forma_pagamento.'.$loop->index, $parcela->forma_pagamento) == 'Transferência Bancária')  
                                        <option value="Pago" {{ old('status.'.$loop->index, $parcela->status) == 'Pago' ? 'selected' : ''}}>Pago</option>
                                        <option value="Aguardando Pagamento" {{ old('status.'.$loop->index, $parcela->status) == 'Aguardando Pagamento' ? 'selected' : ''}}>Aguardando Pagamento</option>
                                    @endif
                                </x-select>

                                @error('status.'.$loop->index)
                                    <div class="invalid-feedback d-inline">
                                        {{ $message }}
                                    </div>
                                @enderror
                                
                            </div>
                            <div class="form-group">
                                <label for="recebido_representante[{{$loop->index}}]">Recebido pelo representante</label>
                                <input type="checkbox" name="recebido_representante[{{$loop->index}}]" id="recebido_representante[{{$loop->index}}]" class="form-control" value=1
                                {{old('recebido_representante', $parcela->recebido_representante) == 1 ? 'checked' : ''}}>
                            </div>

                            <div class="form-group">
                                <label for="observacao[{{$loop->index}}]">Observação</label>
                                <x-textarea name="observacao[{{$loop->index}}]" id="observacao[{{$loop->index}}]" 
                                    class="form-control">
                                    {{old('observacao.'.$loop->index, $parcela->observacao)}}
                                </x-textarea>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
        </div>
       
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class='mt-2'>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <input type="submit" class='btn btn-success'>
    </form>
@endsection
@section('script')
<script>
    // $("#metodo_pagamento").change( (e) => {
    //     let metodo = $(e.target).val()

    //     if (metodo !== 'Cheque') {
    //         $("#campoQtdParcelas").html("");
    //         $("#infoCheques").html("");
    //         return false;
    //     }
    //     if (!$("#valor_total").val()) {
    //         return false;
    //     }

    //     $("#campoQtdParcelas").html(`
    //         <div class='form-group col-md-6'>
    //             <label for="parcelas">Informe o período de prazo</label>
    //             <input class="form-control" id="prazo" type="number" value=30>
    //         </div>
    //         <div class='form-group col-md-6'>
    //             <label for="parcelas">Informe a quantidade de parcelas</label>
    //             <input class="form-control" id="parcelas" type="number">
    //         </div>
    //     `)

    //     $("#parcelas").change ( (e) => {
    //         let parcelas = $(e.target).val()
    //         let prazo = $("#prazo").val()
    //         let dataVenda = $("#data_venda").val()
    //         let valorTotal = $("#valor_total").val()
    //         let proximaData;
    //         let campoValor;
    //         let html = "";

    //         if (valorTotal) {
    //             campoValor = valorTotal / parcelas
    //             campoValorTratado = campoValor.toFixed(2)
    //         }

    //         if (parcelas < 0 && parcelas > 9) {
    //             $("#cheques").html(`
    //                 <div class='alert alert-danger'>Número de parcelas não aceito ${parcelas}!</div>
    //             `);
    //             return false;
    //         } else if (!dataVenda) {
    //             $("#cheques").html(`
    //                 <div class='alert alert-danger'>Informe a data da venda!</div>
    //             `);
    //             return false;
    //         }
            
    //         for (let index = 0; index < parcelas; index++) {
    //             if (dataVenda && prazo) {
    //                 if (!proximaData) {
    //                     proximaData = addDays(dataVenda, prazo);
    //                 } else {
    //                     proximaData = addDays(proximaData, prazo);
    //                 }
    //             }
                
    //             html += `
    //                 <div class="col-md-2 form-group">
    //                     Cheque ${index+1}
    //                 </div>
    //                 <div class="col-md-5 form-group">
    //                     <input type="date" name="data_parcela[]" class="form-control" value="${proximaData}">
    //                 </div>
    //                 <div class="col-md-5 form-group">
                        
    //                     <input type="number" name="valor_parcela[]" class="form-control" value="${campoValorTratado}">
    //                 </div>
    //             `;

    //         }
    //         $("#infoCheques").html(html);
    //     })
    // })

    const FORMA_PAGAMENTO = ['Dinheiro', 'Cheque', 'Transferência Bancária', 'Pix']

    $("#cotacao_fator, #fator, #cotacao_peso, #peso").change( (e) => {
        let cotacao_fator = $("#cotacao_fator").val()
        let cotacao_peso = $("#cotacao_peso").val()
        let fator = $("#fator").val()
        let peso = $("#peso").val()

        calcularTotalVenda(cotacao_fator, cotacao_peso, fator, peso)

    })

    function addDays(date, days) {
        let arrDate = date.split("-")
        let daysFiltered = parseInt(days)

        var result = new Date(arrDate[0], arrDate[1]-1, arrDate[2])
        result.setDate(result.getDate() + daysFiltered);
    
        return result.getFullYear() + '-' 
        + (adicionaZero(result.getMonth()+1).toString()) + "-"
        + adicionaZero(result.getDate().toString());
    }

    function adicionaZero(numero){
        if (numero <= 9) 
            return "0" + numero;
        else
            return numero; 
    }

    function calcularTotalVenda (cotacao_fator, cotacao_peso, fator, peso) {
        if (!cotacao_fator || !fator || !peso || !cotacao_peso) {
            return false;
        }

        let totalFator = cotacao_fator * fator;
        let totalPeso = cotacao_peso * peso;
        let totalCompra = totalFator + totalPeso;
        // parseFloat(totalCompra);

        $("#valor_total").val(totalCompra)
    }

    function listenerFormaPagamentoParcela () {
        $("select[name^='forma_pagamento']").each( (index, element) => {
            $(element).change( (e) => {
                let select = $(e.target)
                let valorSelect = select.val()
                let indexSelect = $(e.target).data('index')

                if (valorSelect == 'Cheque') {
                    $("#groupNumero_" + indexSelect).removeClass('d-none')
                    $("#groupNome_" + indexSelect).removeClass('d-none')
                    $("#groupBanco_" + indexSelect).removeClass('d-none')
                    
                    listenerNomes()
                    tratarCampoStatus(indexSelect, valorSelect)
                } else if (valorSelect == 'Dinheiro' || valorSelect == 'Pix') {
                    $("#groupNumero_" + indexSelect).addClass('d-none')
                    $("#groupNome_" + indexSelect).addClass('d-none')
                    $("#groupBanco_" + indexSelect).addClass('d-none')
                    
                    tratarCampoStatus(indexSelect, valorSelect)
                } else if (valorSelect == 'Transferência Bancária') {
                    $("#groupNumero_" + indexSelect).addClass('d-none')
                    $("#groupNome_" + indexSelect).addClass('d-none')
                    $("#groupBanco_" + indexSelect).addClass('d-none')

                    tratarCampoStatus(indexSelect, valorSelect)
                }

            });
            
        })
    }
    listenerFormaPagamentoParcela()

    function tratarCampoStatus(index, valorSelect) {
        let opcoesDisponiveis = []
        let element = $("select[name^='status']:eq(" + index + ")")

        if (valorSelect == 'Cheque') {
            opcoesDisponiveis = {
                '': '',
                'Aguardando Depósito': 'Aguardando',
                'Aguardando Envio': 'Aguardando Envio' 
            }
        } else if (valorSelect == 'Pix' || valorSelect == 'Dinheiro') {
            opcoesDisponiveis = {
                'Pago': 'Pago'
            }
        } else if (valorSelect == 'Transferência Bancária') {
            opcoesDisponiveis = {
                '': '',
                'Pago': 'Pago', 
                'Aguardando Pagamento': 'Aguardando Pagamento'
            }
        }
        
        element.empty()

        $.each(opcoesDisponiveis, function(key,value) {
            element.append($("<option></option>")
            .attr("value", value).text(key));
        });
    }

    $("#formUpdateVenda").submit( (element) => {
        element.preventDefault();
        
        let valorTotalPago = 0;
        $("input[name^='valor_parcela']").each( (index, element) => {
            valorTotalPago += parseFloat($(element).val())
            // console.log({index, element})
        })

        let valorTotalPedido = parseFloat($("#valor_total").val())
        let form = element.target;

        if (valorTotalPago < valorTotalPedido) {
           
            Swal.fire({
                title: 'Atenção!',
                html: 
                    "O valor pago é <b>menor</b> que o valor do pedido!" + 
                    "<br> Valor total pago: " + moeda.format(valorTotalPago) + 
                    "<br> Valor total do pedido: " + moeda.format(valorTotalPedido) ,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    return
                }
            })
        } else {
            form.submit();
        }
    })

    let moeda = Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: 'BRL',
    });

    function listenerNomes () {
        $(".titularCheque").focus( (e) => {
            $(e.target).autocomplete({
                minLength: 0,
                source: titularDoUltimoCheque,
                autoFocus: true,
            });
        })
    }
</script>
@endsection