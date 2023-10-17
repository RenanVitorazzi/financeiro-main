@extends('layout')
@section('title')
Parceiros
@endsection
@section('body')
<div class='mb-2 d-flex justify-content-between'>
    <h3>Parceiros</h3>  
    <div>
        <x-botao-novo href="{{ route('parceiros.create') }}"></x-botao-novo>
    </div>
</div>
<ul class="d-flex list-group list-group">
    @forelse ($parceiros as $parceiro)
        <li class='list-group-item d-flex justify-content-between'>
            <div class='mt-2'>
                {{ $parceiro->pessoa->nome }}
                <span class="text-muted">{{ $parceiro->porcentagem_padrao }}%</span>
            </div>
            <div class='d-flex'>
                @if(auth()->user()->is_admin && auth()->user()->id == 1)
                    <a href="{{ route('configurar_cc_parceiros', $parceiro->id) }}" class="btn btn-dark mr-2">
                        <span class='fas fa-cog'></span>
                    </a>
                @endif
                {{-- <a class="btn btn-dark mr-2" href="{{ route('pdf_cc_parceiro', $parceiro->id) }}">Conta Corrente</a> --}}
                <x-botao-imprimir class='mr-2' href="{{ route('pdf_cc_parceiro', $parceiro->id) }}"></x-botao-imprimir>
                <x-botao-editar class="mr-2" href="{{ route('parceiros.edit', $parceiro->id) }}"></x-botao-editar>
                <x-botao-excluir action="{{ route('parceiros.destroy', $parceiro->id) }}"></x-botao-excluir>
            </div>
        </li>
        
    @empty
        <li class='list-group-item list-group-item-danger'>Nenhum registro criado!</li>
    @endforelse
</ul>
        
@endsection
@section('script')
<script>
    @if(Session::has('message'))
        toastr["success"]("{{ Session::get('message') }}")
    @endif

    $(".btn-zerar").each( (index, element) => {
        let parceiro_id = $(element).data('id')

        $(element).click( (e) => {
            let parceiro_id = $(e.currentTarget).data('id')
            console.log(parceiro_id);
            swal.fire({
                title: 'Tem certeza de que deseja baixar o conta corrente?',
                icon: 'warning',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    zerarContaCorrente(parceiro_id)

                    Swal.fire({
                        title: 'Sucesso!',
                        icon: 'success'
                    })

                } else {
                    Swal.fire({
                        title: 'Cancelado!',
                        icon: 'warning'
                    })
                }
            })
        })
    })

    function zerarContaCorrente (parceiro_id) {
        
    }
   
</script>
@endsection