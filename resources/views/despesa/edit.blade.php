@extends('layout')
@section('title')
Editar despesa
@endsection
@section('body')
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('despesas.index') }}">Despesas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar cadastro</li>
    </ol>
</nav>

<form method="POST" action="{{ route('despesas.update', $despesa->id)}}">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-6 form-group">
            <label for="nome">Nome da despesa</label>
            <x-input name="nome" type="text" value="{{ $despesa->nome }}"></x-input>
        </div>
        <div class="col-6 form-group">
            <label for="data_vencimento">Data do vencimento</label>
            <x-input name="data_vencimento" type="date" value="{{ $despesa->data_vencimento }}"></x-input>
        </div>
        <div class="col-6 form-group">
            <label for="valor">Valor</label>
            <x-input name="valor" type="text" value="{{ $despesa->valor }}"></x-input>
        </div>

        <div class="col-6 form-group">
            <label for="data_pagamento">Data do Pagamento</label>
            <x-input name="data_pagamento" type="date" value="{{ $despesa->data_pagamento }}"></x-input>
        </div>

        <div class="col-6 form-group">
            <label for="local_id">Local</label>
            <x-select name="local_id">
                <option></option>
                @foreach ($locais as $local)
                    <option value="{{ $local->id }}" {{  $despesa->local_id == $local->id ? 'selected' : '' }}>{{ $local->nome }}</option>
                @endforeach
            </x-select>
        </div>
        <div class="col-6 form-group">
            <label for="forma_pagamento">Forma de pagamento</label>
            <x-select name="forma_pagamento">
                {{-- <option></option> --}}
                @foreach ($formasPagamento as $pagamento)
                    <option value="{{ $pagamento }}" {{ $despesa->forma_pagamento == $pagamento ? 'selected' : '' }}>{{ $pagamento }}</option>
                @endforeach
            </x-select>
        </div>

        <div class="col-6 form-group">
            <label for="comprovante_id">Comprovante ID</label>
            <x-input name="comprovante_id" type="text" value="{{ $despesa->comprovante_id }}"></x-input>
        </div>
        <div class="col-6 form-group">
            <label for="conta_id">Conta do Pagamento</label>
            <x-select name="conta_id">
                <option></option>
                @foreach ($contas as $conta)
                    <option value="{{ $conta->id }}" {{ $despesa->conta_id == $conta->id ? 'selected' : '' }}>{{ $conta->nome }}</option>
                @endforeach
            </x-select>
        </div>
        
        <div class="col-12 form-group">
            <label for="observacao">Observação</label>
            <x-text-area name="observacao" type="text" value="{{  $despesa->observacao }}"></x-text-area>
        </div>

    </div>
    {{-- <p>@json($fixas)</p> --}}
    <input type="submit" class='btn btn-success'>
</form>
@endsection
@section('script')
<script>
    
</script>
@endsection