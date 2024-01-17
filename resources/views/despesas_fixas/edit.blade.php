@extends('layout')
@section('title')
Editar despesa fixa
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cadastros_auxiliares') }}">Cadastros Auxiliares</a></li>
        <li class="breadcrumb-item"><a href="{{ route('despesas_fixas.index') }}">Despesas Fixas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar despesa fixa</li>
    </ol>
</nav>

<form method="POST" action="{{ route('despesas_fixas.update', $despesa_fixa->id)}}">
    @csrf
    @method('PATCH')
    <h5>Editar despesa fixa</h5>
    <div class="card mb-2">
        <div class="card-body">

            <div class="row">
                <div class="col-4">
                    <x-form-group name='nome' autofocus value="{{ old('nome', $despesa_fixa->nome) }}">Nome</x-form-group>
                </div>

                <div class="col-4">
                    <x-form-group name='data_quitacao' type='date' value="{{ old('data_quitacao', $despesa_fixa->data_quitacao) }}">Data da quitação</x-form-group>
                </div>

                <div class="col-4">
                    <x-form-group name='dia_vencimento' type='number' value="{{ old('dia_vencimento', $despesa_fixa->dia_vencimento) }}">Dia vencimento</x-form-group>
                </div>

                <div class="col-4">
                    <x-form-group name='valor' value="{{ old('valor', $despesa_fixa->valor) }}" type='number'>Valor</x-form-group>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="local_id">Local da despesa</label>
                        <x-select name="local_id" required>
                            <option value=""></option>
                            @foreach ($locais as $local)
                                <option value="{{$local->id}}" {{ old('local_id', $despesa_fixa->local_id) == $local->id ? 'selected' : '' }}>{{$local->nome}}</option>
                            @endforeach 

                        </x-select>
                    </div>
                </div>

                <div class="col-12">
                    <label for="observacao">Observação</label>
                    <x-text-area name='observacao'>{{ old('observacao', $despesa_fixa->observacao) }}</x-text-area>
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
