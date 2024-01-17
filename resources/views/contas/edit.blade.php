@extends('layout')
@section('title')
Editar conta
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contas.index') }}">Contas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar conta</li>
    </ol>
</nav>

<form method="POST" action="{{ route('contas.update', $conta)}}">
    @csrf
    @method('PATCH')
    <h5>Nova conta</h5>
    <div class="card mb-2">
        <div class="card-body">

            <div class="row">
                <div class="col-4">
                    <x-form-group name='nome' autofocus value="{{ old('nome', $conta->nome) }}">Nome</x-form-group>
                </div>

                <div class="col-4">
                    <x-form-group name='pix' value="{{ old('pix', $conta->pix) }}">Pix</x-form-group>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="conta_corrente">Tipo de conta</label>
                        <x-select name="conta_corrente" required>
                            <option value="Conta Corrente" {{ old('conta_corrente', $conta->conta_corrente) == "Conta Corrente" ? 'selected' : '' }}>Conta Corrente</option>
                            <option value="Poupança" {{ old('conta_corrente', $conta->conta_corrente) == "Poupança" ? 'selected' : '' }}>Poupança</option>
                        </x-select>
                    </div>
                </div>

                <div class="col-4">
                    <x-form-group name='numero_banco' value="{{ old('numero_banco', $conta->numero_banco) }}">Número do Banco</x-form-group>
                </div>

                <div class="col-4">
                    <x-form-group name='agencia' value="{{ old('agencia', $conta->agencia) }}">Agência</x-form-group>
                </div>

                <div class="col-4">
                    <x-form-group name='conta' value="{{ old('conta', $conta->conta) }}">Conta</x-form-group>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <input type="checkbox" name='inativo' id='inativo'  {{ old('inativo', $conta->inativo) == 1 ? 'checked' : '' }} value=1>
                        <label for="inativo">Inativo</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <input type="submit" class='btn btn-success'>
</form>

@endsection
