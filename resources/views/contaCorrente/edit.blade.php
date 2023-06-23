@extends('layout')
@section('title')
Editar conta
@endsection
@section('body') 
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('fornecedores.index') }}">Fornecedores</a></li>
        <li class="breadcrumb-item"><a href="{{ route('fornecedores.show', $contaCorrente->fornecedor_id) }}">Conta Corrente</a></li>
        <li class="breadcrumb-item active" aria-current="page">Adicionar</li>
    </ol>
</nav>
<h3>Editar Conta Corrente</h3>
<form method="POST" action="{{ route('conta_corrente.update', $contaCorrente->id)}}">
    @csrf
    @method('PUT')
    <input name="fornecedor_id" type="hidden" value="{{ $contaCorrente->fornecedor_id }}" >

    <div class="row">
        <div class="col-6">
            <x-form-group name="data" type="date" value="{{ $contaCorrente->data }}" >Data</x-form-group>
        </div>
        <div class="col-6 form-group">
            <label for="balanco">Balanço</label>
            <x-select name="balanco" id="balanco" class="form-control" autofocus required>
                <option value='Débito' {{ $contaCorrente->balanco == 'Débito' ? 'selected' : ''}}> Compra (Débito)</option>
                <option value='Crédito' {{ $contaCorrente->balanco == 'Crédito' ? 'selected' : ''}}> Fechamento (Crédito)</option>
            </x-select>
        </div>
        <div class="col-6" id="group-peso">
            <x-form-group name="peso" type="number" step="0.001" min="0" value="{{ $contaCorrente->peso }}">Peso (g)</x-form-group>
        </div>
        
        <div class="col-6" id="group-peso_bruto" style="{{ $contaCorrente->balanco == 'Débito' ?: 'display:none'}}">
            <x-form-group name="peso_bruto" type="number" step="0.001" min="0" value="{{ $contaCorrente->peso_bruto }}">Peso Bruto (g)</x-form-group>
        </div>
        
        <div class="col-3" id="group-cotacao" style="{{ $contaCorrente->balanco == 'Crédito' ?: 'display:none'}}">
            <x-form-group name="cotacao" type="number" step="0.01" min="0" value="{{ $contaCorrente->cotacao }}">Cotação do dia (R$)</x-form-group>
        </div>
        <div class="col-3" id="group-valor" style="{{ $contaCorrente->balanco == 'Crédito' ?: 'display:none'}}">
            <x-form-group name="valor" type="number" step="0.01" min="0" value="{{ $contaCorrente->valor }}">Valor (R$)</x-form-group>
        </div>
        
    </div> 
    <div class="form-group">
        <label for="observacao">Observação</label>
        <x-textarea name="observacao" class="form-control">{{ $contaCorrente->observacao }}</x-textarea>
    </div>
        
    <input type="submit" class='btn btn-success'>
</form>
@endsection
@section('script')
<script>
    $("#cotacao, #valor").change( () => {
        let cotacao = parseFloat($("#cotacao").val());
        let valor = parseFloat($("#valor").val());

        if (!cotacao || !valor) {
            return false;
        }

        let peso = valor/cotacao;
        $("#peso").val(peso.toFixed(2));
    })

    $("#balanco").change( (e) => {
        $("#group-cotacao").toggle()
        $("#group-valor").toggle()

        $("#group-peso_bruto").toggle()
    })
</script>
@endsection