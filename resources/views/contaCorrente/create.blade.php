@extends('layout')
@section('title')
Adicionar nova conta
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('fornecedores.index') }}">Fornecedores</a></li>
        <li class="breadcrumb-item"><a href="{{ route('fornecedores.show', $fornecedor->id) }}">Conta Corrente</a></li>
        <li class="breadcrumb-item active" aria-current="page">Adicionar</li>
    </ol>
</nav>
<h3>Nova conta corrente - {{ $fornecedor->pessoa->nome }}</h3>
<form method="POST" action="{{ route('conta_corrente.store')}}" enctype="multipart/form-data">
    @csrf

    <input name="fornecedor_id" type="hidden" value="{{ $fornecedor->id }}" >

    <div class="row">
        <div class="col-6">
            <x-form-group name="data" type="date" value="{{ date('Y-m-d') }}" >Data</x-form-group>
        </div>
        <div class="col-6 form-group">
            <label for="balanco">Balanço</label>
            <x-select name="balanco" id="balanco" class="form-control" autofocus required>
                <option value='Débito' selected> Compra (Débito)</option>
                <option value='Crédito'> Fechamento (Crédito)</option>
            </x-select>
        </div>
        <div class="col-6" id="group-peso">
            <x-form-group name="peso" type="number" step="0.001" min="0">Peso (g)</x-form-group>
        </div>
        
        <div class="col-6" id="group-peso_bruto">
            <x-form-group name="peso_bruto" type="number" step="0.001" min="0">Peso Bruto (g)</x-form-group>
        </div>

        <div class="col-3" id="group-cotacao" style="display:none">
            <x-form-group name="cotacao" type="number" step="0.01" min="0">Cotação do dia (R$)</x-form-group>
        </div>
        <div class="col-3" id="group-valor" style="display:none">
            <x-form-group name="valor" type="number" step="0.01" min="0">Valor (R$)</x-form-group>
        </div>
        
    </div> 
    <div class="form-group">
        <label for="observacao">Observação</label>
        <x-textarea name="observacao" id="observacao" class="form-control"></x-textarea>
    </div>
    
    <div class="form-group">
        <label for="anexo">Anexo de Arquivo</label>
        <input type="file" id="anexo" name="anexo[]" class="form-control-file" multiple >
    </div>
        
    <input type="submit" class='btn btn-success'>
</form>
@endsection
@section('script')
<script>
    $(function() {
        $("#cotacao, #valor").change( () => {
            let cotacao = parseFloat($("#cotacao").val())
            let valor = parseFloat($("#valor").val())

            if (!cotacao || !valor) {
                return false
            }
            
            let peso = valor/cotacao;
            let pesoTratado = peso.toFixed(3).slice(0,-1);

            $("#peso").val(pesoTratado)
        })
        
        $("#balanco").change( (e) => {
            $("#group-cotacao").toggle()
            $("#group-valor").toggle()

            $("#group-peso_bruto").toggle()
        })
    });
</script>
@endsection