@extends('layout')
@section('title')
Cadastros Auxiliares
@endsection
@section('body')
<div class='mb-2 d-flex justify-content-between'>
    <h3>Cadastros Auxiliares</h3>  
    <div>
        <x-botao-novo href="{{ route('parceiros.create') }}"></x-botao-novo>
    </div>
</div>
<ul class="d-flex list-group list-group">
    @foreach ($modulos as $key => $modulo)
        <li class='list-group-item d-flex justify-content-between'>
            <div class='mt-2'>
                {{ $key }} 
            </div>
            <a href={{ route($modulo . '.index') }} class="btn btn-dark"><i class="fas fa-cog"></i></a>
        </li>
        
    @endforeach
 
</ul>

@endsection