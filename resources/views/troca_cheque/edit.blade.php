@extends('layout')
@section('title')
Editar Troca
@endsection
@section('body')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('troca_cheques.index') }}">Troca de cheques</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $troca->titulo }}</li>
    </ol>
</nav>
    <div class="container">
        <div class="d-flex justify-content-between">
            <h3>Trocar cheques</h3>
        </div>
        <form action="{{ route('troca_cheques.update', $troca->id) }}" method="POST" id="formTrocaCheques">
            
            @csrf
            @method('PUT')

            <div class="row">
                <div class="form-group col-6">
                    <label for="titulo">Título</label>
                    <x-input name="titulo" value="{{ $troca->titulo }}"></x-input>
                </div>
                <div class="col-6">
                    <x-form-group type='date' name="data_troca" value="{{ $troca->data_troca }}">Data da troca</x-form-group>
                </div>
            </div>            

            <div class="row">
                
                <div class="col-6">
                    <label for="parceiro_id">Parceiro</label>
                    <x-select name="parceiro_id">
                        <option value=""></option>
                        @foreach ($parceiros as $parceiro)
                            <option data-porcentagem="{{ $parceiro->porcentagem_padrao }}" value="{{ $parceiro->id }} "
                                {{ $parceiro->id == $troca->parceiro_id ? 'selected' : '' }}
                            > 
                                {{ $parceiro->pessoa->nome }} ({{ $parceiro->porcentagem_padrao }}%)
                            </option>
                        @endforeach
                    </x-select>
                </div>
                
                <div class="form-group col-6">
                    <label for="taxa_juros">Taxa (%)</label>
                    <x-input name="taxa_juros" type="number" value="{{ $troca->taxa_juros }}" max="100" step="0.01"></x-input>
                </div>

                <x-table id="tabelaChequesTroca">
                    <x-table-header>
                        <tr>
                            <th colspan=6>Cheques Troca</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Data</th>
                            <th>Titular</th>
                            <th>Número</th>
                            <th>Valor</th>
                        </tr>
                    </x-table-header>
                    <tbody>
                        @forelse  ($chequesTroca as $chequeTroca)
                        <tr>
                            <td>
                                <input type="checkbox" name="cheque_troca_id[]" value={{ $chequeTroca->id }} checked>
                            </td>
                            <td>@data($chequeTroca->data_parcela)</td>
                            <td>{{ $chequeTroca->nome_cheque }}</td>
                            <td>{{ $chequeTroca->numero_cheque }}</td>
                            <td>@moeda($chequeTroca->valor_parcela)</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan=6>Nenhum registro</td>
                        </tr>
                        @endforelse
                    </tbody>
                </x-table>

                <x-table id="tabelaChequesCarteira">
                    <x-table-header>
                        <tr>
                            <th colspan=6>Cheques Carteira - Cheques que serão adicionados a troca</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Data</th>
                            <th>Titular</th>
                            <th>Número</th>
                            <th>Valor</th>
                        </tr>
                    </x-table-header>
                    <tbody>
                        @forelse  ($chequesCarteira as $chequeCarteira)
                        <tr>
                            <td>
                                <input type="checkbox" name="cheque_carteira_id[]" value={{ $chequeCarteira->id }}>
                            </td>
                            <td>@data($chequeCarteira->data_parcela)</td>
                            <td>{{ $chequeCarteira->nome_cheque }}</td>
                            <td>{{ $chequeCarteira->numero_cheque }}</td>
                            <td>@moeda($chequeCarteira->valor_parcela)</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan=6>Nenhum registro</td>
                        </tr>
                        @endforelse
                    </tbody>
                </x-table>

                <div class="form-group col-12">
                    <label for="observacao">Observação</label>
                    <x-text-area name="observacao">{{ old('observacao') }}</x-text-area>
                </div>
                
            </div>
           
            <input class="btn btn-success" type="submit">
            
        </form>
    </div>
@endsection
@section('script')
<script>
    $("#parceiro_id").change( (e) => {
        let porcentagem_troca = $(e.target).find('option:selected').data("porcentagem") 
        console.log(porcentagem_troca);
        $("#taxa_juros").val(porcentagem_troca)
    })
</script>
@endsection