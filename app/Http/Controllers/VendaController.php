<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\SalvarVendaRequest;
use App\Http\Requests\UpdateVendaRequest;
use App\Models\Consignado;
use App\Models\ContaCorrenteRepresentante;
use App\Models\Parcela;
use App\Models\Venda;
use App\Models\Representante;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VendaController extends Controller
{
    public function create(Request $request)
    {
        $representante_id = $request->id;

        $clientes = DB::select('SELECT
                                    UPPER(p.nome) AS nome, c.id
                                FROM
                                    clientes c
                                        INNER JOIN
                                    pessoas p ON p.id = c.pessoa_id
                                WHERE
                                    c.representante_id = ?
                                        AND c.deleted_at IS NULL
                                ORDER BY p.nome',
                                [$representante_id]
        );
        
        $metodo_pagamento = ['À vista', 'Parcelado'];
        $forma_pagamento = ['Dinheiro', 'Cheque', 'Transferência Bancária', 'Pix'];

        return view('venda.create', compact('representante_id', 'clientes', 'metodo_pagamento', 'forma_pagamento'));
    }

    public function store(SalvarVendaRequest $request)
    {
        $resultadoVenda = DB::transaction(function () use ($request) {
            $venda = Venda::create([
                'data_venda' => $request->data_venda,
                'cliente_id' => $request->cliente_id,
                'representante_id' => $request->representante_id,
                'peso' => $request->peso,
                'fator' => $request->fator,
                'cotacao_fator' => $request->cotacao_fator,
                'cotacao_peso' => $request->cotacao_peso,
                'valor_total' => $request->valor_total,
                'metodo_pagamento' => $request->metodo_pagamento,
            ]);

            foreach ($request->data_parcela as $key => $value) {
                Parcela::create([
                    'venda_id' => $venda->id,
                    'forma_pagamento' => $request->forma_pagamento[$key],
                    'numero_banco' => $request->numero_banco[$key],
                    'nome_cheque' => $request->nome_cheque[$key],
                    'numero_cheque' => $request->numero_cheque[$key],
                    'data_parcela' => $value,
                    'status' => $request->status[$key],
                    'valor_parcela' => $request->valor_parcela[$key],
                    'recebido_representante' => $request->recebido_representante[$key] ?? NULL,
                    'observacao' => $request->observacao[$key],
                    'representante_id' => $venda->representante_id,
                ]);
            }

            if ($request->baixar) {
                Consignado::where('id', $request->baixar)->update([
                    'baixado' => Carbon::now(),
                    'venda_id' => $venda->id
                ]);
            }
            return $venda;
        });
        return redirect()->route('venda.show', $resultadoVenda->representante_id);
    }

    public function show(Request $request, $id)
    {
        if (auth()->user()->is_representante && auth()->user()->is_representante != $id) {
            abort(403);
        }

        $vendas = Venda::with('parcela')
            ->where('representante_id', $id)
            ->where('enviado_conta_corrente', null)
            // ->latest()
            ->orderBy('data_venda')
            ->get();
        // dd($vendas);
        $representante = Representante::findOrFail($id);

        return view('venda.show', compact('vendas', 'representante'));
    }

    public function edit($id)
    {
        $venda = Venda::with('parcela')->findOrFail($id);
        $representantes = Representante::with('pessoa')->get();
        $clientes = Cliente::with('pessoa')->get();
        $metodo_pagamento = ['À vista', 'Parcelado'];
        $forma_pagamento = ['Dinheiro', 'Cheque', 'Transferência Bancária', 'Pix'];
        
        return view('venda.edit', compact('representantes', 'venda', 'clientes', 'forma_pagamento', 'metodo_pagamento'));
    }

    public function update(UpdateVendaRequest $request, $id)
    {
        $venda = Venda::findOrFail($id);
        $parcelas = Parcela::where('venda_id', $id)->pluck('id')->toArray();
        
        DB::transaction(function () use ($request, $parcelas) {
            foreach($request->parcela_id as $key => $parcela_id) {
                if (in_array($parcela_id, $parcelas)) {
                    $parcela = Parcela::findOrFail($parcela_id);
                    
                    $parcela->update([
                        'forma_pagamento' => $request->forma_pagamento[$key],
                        'numero_banco' => $request->numero_banco[$key],
                        'nome_cheque' => $request->nome_cheque[$key],
                        'numero_cheque' => $request->numero_cheque[$key],
                        'data_parcela' =>  $request->data_parcela[$key],
                        'status' => $request->status[$key],
                        'valor_parcela' => $request->valor_parcela[$key],
                        'recebido_representante' => $request->recebido_representante[$key] ?? NULL,
                        'observacao' => $request->observacao[$key],
                        'representante_id' => $request->representante_id,
                    ]);
                } 
            } 
        });
        
        $venda
            ->fill($request->all())
            ->save();

        $request
            ->session()
            ->flash(
                'message',
                'Venda atualizada com sucesso!'
            );
        return redirect("/venda/{$request->representante_id}");
    }

    public function destroy(Request $request, $id)
    {
        $venda = Venda::findOrFail($id);
        $venda->delete();

        $request
            ->session()
            ->flash(
                'message',
                'Venda excluída com sucesso!'
            );

        return redirect()->route('venda.show', $venda->representante_id);
    }

    public function enviarContaCorrente (Request $request) {
        $vendas = Venda::find($request->vendas_id);

        $contaCorrente = ContaCorrenteRepresentante::create([
            'fator' => $vendas->sum('fator'),
            'peso' => $vendas->sum('peso'),
            'fator_agregado' => $vendas->sum('fator'),
            'peso_agregado' => $vendas->sum('peso'),
            'data' => date('Y-m-d'),
            'balanco' => 'Venda',
            'representante_id' => $vendas->first()->representante_id
        ]);

        Venda::whereIn('id', $request->vendas_id)->update([
            'enviado_conta_corrente' => $contaCorrente->id
        ]);

        // return redirect()->route('venda.show', $vendas->first()->representante_id);
        return json_encode([
           'contaCorrente' => $contaCorrente->id,
           'route' => route('conta_corrente_representante.show', $contaCorrente->representante_id)
        ]);
    }

    public function pdf_relatorio_vendas($relatorio_venda_id)
    {

        $vendas = Venda::with('cliente')
            ->where('enviado_conta_corrente', $relatorio_venda_id)
            ->orderBy('data_venda')
            ->get();
        $representante_id = $vendas->first()->representante_id;

        $cheques = Parcela::whereIn('venda_id', $vendas->pluck('id'))->get();
        
        $pagamentos = Parcela::whereHas('venda', function (Builder $query) use ($representante_id, $relatorio_venda_id) {
                $query->where('enviado_conta_corrente', $relatorio_venda_id);
            })
        ->get();
            // dd($pagamentos->where('forma_pagamento', 'like', 'Dinheiro')->first()->venda->cliente->pessoa->nome);
        $pagamentosPorForma = $pagamentos->groupBy('forma_pagamento');

        $pagamentosTotal = $pagamentos->sum('valor_parcela');

        $representante = Representante::findOrFail($representante_id);

        $totalVendaPesoAVista = 0;
        $totalVendaFatorAVista = 0;

        foreach( $vendas->where('metodo_pagamento', 'À vista') as $venda) {
            $totalVendaPesoAVista += ($venda->peso * $venda->cotacao_peso);
            $totalVendaFatorAVista += ($venda->fator * $venda->cotacao_fator);
        }

        // $pesoComissao = $totalVendaPesoAVista / ;
        // $fatorComissao = ;

        $comissao_json = Storage::disk('public')
            ->get('comissao_representantes/porcentagem.json');
        $comissao_array = json_decode($comissao_json, true);

        $comissaoRepresentante = $comissao_array[$representante->id] ?? $comissao_array["Default"];

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('venda.pdf.pdf_conferencia_relatorio_vendas',
            compact(
                'vendas',
                'representante',
                'pagamentos',
                'pagamentosTotal',
                'pagamentosPorForma',
                'totalVendaPesoAVista',
                'totalVendaFatorAVista',
                'comissaoRepresentante',
                'cheques'
            )
        );

        return $pdf->stream();
    }

    public function pdf_relatorio_vendas_deflacao($relatorio_venda_id)
    {

        $vendas = Venda::with('cliente')
            ->where('enviado_conta_corrente', $relatorio_venda_id)
            ->orderBy('data_venda')
            ->get();
        
        $representante_id = $vendas->first()->representante_id;

        $cheques = Parcela::whereIn('venda_id', $vendas->pluck('id'))->get();

        $pagamentos = Parcela::whereHas('venda', function (Builder $query) use ($representante_id, $relatorio_venda_id) {
                $query->where('enviado_conta_corrente', $relatorio_venda_id);
            })
        ->get();

        $pagamentosPorForma = $pagamentos->groupBy('forma_pagamento');

        $pagamentosTotal = $pagamentos->sum('valor_parcela');

        $representante = Representante::findOrFail($representante_id);

        $totalVendaPesoAVista = 0;
        $totalVendaFatorAVista = 0;

        foreach( $vendas->where('metodo_pagamento', 'À vista') as $venda) {
            $totalVendaPesoAVista += ($venda->peso * $venda->cotacao_peso);
            $totalVendaFatorAVista += ($venda->fator * $venda->cotacao_fator);
        }

        $comissao_json = Storage::disk('public')
            ->get('comissao_representantes/porcentagem.json');
        $comissao_array = json_decode($comissao_json, true);

        $comissaoRepresentante = $comissao_array[$representante->id] ?? $comissao_array["Default"];
        // dd($comissaoRepresentante);
        $arrayDeflacao = [];
        $taxaDeflacao = 0.025;
        $valorGeralComissao = 0;
        $valorGeralPesoComissao = 0;
        $valorGeralFatorComissao = 0;
        $somaPrazoMedio = 0;

        foreach($vendas as $key => $venda) {

            $arrayVendaDeflacaoPrazoMedio = [];

            $dataInicio = new DateTime($venda->data_venda);
            $totalJuros = 0;
            $totalVenda = 0;
            $totalDias = 0;

            foreach($cheques->where('venda_id', $venda->id) as $key => $cheque) {
                
                $dataFim = new DateTime($cheque->data_parcela);

                if ($dataFim <= $dataInicio) {
                    $diferencaDias = 0;
                } else {
                    $diferencaDias = $dataInicio->diff($dataFim)->days;
                }

                $juros = ( ($cheque->valor_parcela * $taxaDeflacao) / 30 ) * $diferencaDias;
                
                $totalVenda += $cheque->valor_parcela;
                $totalJuros += $juros;
                $totalDias += $diferencaDias;
            }

            $porcentagemDeflacao = $totalJuros / $totalVenda;
            $quantidadeParcelas = $cheques->where('venda_id', $venda->id)->count();

            $prazoMedio = $totalDias / $quantidadeParcelas;
            
            $comissaoPeso = $comissaoRepresentante['porcentagem_peso'] * $venda->peso / 100;
            $valorPesoLiquido = $venda->cotacao_peso - ($venda->cotacao_peso * $porcentagemDeflacao);
            $valorComissaoPeso = $comissaoPeso * $valorPesoLiquido;

            $comissaoFator = $comissaoRepresentante['porcentagem_fator'] * $venda->fator / 100;
            $valorFatorLiquido = $venda->cotacao_fator - ($venda->cotacao_fator * $porcentagemDeflacao);
            $valorComissaoFator = $comissaoFator * $valorFatorLiquido;

            $totalComissaoLiquido = $valorComissaoPeso + $valorComissaoFator; 
            $valorGeralComissao += $totalComissaoLiquido;
            $valorGeralPesoComissao += $valorComissaoPeso;
            $valorGeralFatorComissao += $valorComissaoFator;
            $somaPrazoMedio += $prazoMedio;

            $arrayDeflacao[$venda->id] = [
                'totalDias' => $totalDias, 
                'quantidadeParcelas' => $quantidadeParcelas,
                'prazoMedio' => $prazoMedio, 
                'totalJuros' => $totalJuros,

                'comissaoPeso' => $comissaoPeso,
                'valorPesoLiquido' => $valorPesoLiquido,
                'valorComissaoPeso' => $valorComissaoPeso,

                'comissaoFator' => $comissaoFator,
                'valorFatorLiquido' => $valorFatorLiquido,
                'valorComissaoFator' => $valorComissaoFator,

                'totalComissaoLiquido' => $totalComissaoLiquido,
                'totalVenda' => $totalVenda,
                'porcentagemDeflacao' => $porcentagemDeflacao * 100, 
            ];
        }

        $prazoMedioRelatorio = $somaPrazoMedio / $vendas->count();
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('venda.pdf.pdf_relatorio_vendas_deflacao',
            compact(
                'vendas',
                'representante',
                'pagamentos',
                'pagamentosTotal',
                'pagamentosPorForma',
                'totalVendaPesoAVista',
                'totalVendaFatorAVista',
                'comissaoRepresentante',
                'cheques',
                'arrayDeflacao',
                'valorGeralComissao',
                'valorGeralPesoComissao',
                'valorGeralFatorComissao',
                'prazoMedioRelatorio'
            )
        );

        return $pdf->stream();
    }

    public function pdf_conferencia_relatorio_vendas($representante_id)
    {
        $vendas = Venda::with('cliente')
            ->whereNull('enviado_conta_corrente')
            ->where('representante_id', '=', $representante_id)
            ->orderBy('data_venda')
            ->get();
            
        $cheques = Parcela::whereIn('venda_id', $vendas->pluck('id'))->get();
        
        $pagamentos = Parcela::whereHas('venda', function (Builder $query) use ($representante_id) {
                $query->whereNull('enviado_conta_corrente')
                    ->where('representante_id', '=', $representante_id);
            })
        ->get();
            // dd($pagamentos->where('forma_pagamento', 'like', 'Dinheiro')->first()->venda->cliente->pessoa->nome);
        $pagamentosPorForma = $pagamentos->groupBy('forma_pagamento');

        $pagamentosTotal = $pagamentos->sum('valor_parcela');

        $representante = Representante::findOrFail($representante_id);

        $totalVendaPesoAVista = 0;
        $totalVendaFatorAVista = 0;

        foreach( $vendas->where('metodo_pagamento', 'À vista') as $venda) {
            $totalVendaPesoAVista += ($venda->peso * $venda->cotacao_peso);
            $totalVendaFatorAVista += ($venda->fator * $venda->cotacao_fator);
        }

        // $pesoComissao = $totalVendaPesoAVista / ;
        // $fatorComissao = ;

        $comissao_json = Storage::disk('public')
            ->get('comissao_representantes/porcentagem.json');
        $comissao_array = json_decode($comissao_json, true);

        $comissaoRepresentante = $comissao_array[$representante->id] ?? $comissao_array["Default"];

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('venda.pdf.pdf_conferencia_relatorio_vendas',
            compact(
                'vendas',
                'representante',
                'pagamentos',
                'pagamentosTotal',
                'pagamentosPorForma',
                'totalVendaPesoAVista',
                'totalVendaFatorAVista',
                'comissaoRepresentante',
                'cheques'
            )
        );

        return $pdf->stream();
    }

    public function pdf_acerto_documento($representante_id)
    {
        $acertos = DB::select( "SELECT DISTINCT
                c.id as cliente_id,
                (SELECT UPPER(nome) from pessoas WHERE id = c.pessoa_id) as cliente
            FROM
                vendas v
                    INNER JOIN parcelas p ON p.venda_id = v.id
                    LEFT JOIN clientes c ON c.id = v.cliente_id
                    LEFT JOIN representantes r ON r.id = v.representante_id
            WHERE
                p.deleted_at IS NULL
                AND v.deleted_at IS NULL
                AND r.id = ?
                AND (
                p.forma_pagamento like 'Cheque' AND p.status like 'Aguardando Envio'
                OR
                p.forma_pagamento != 'Cheque' AND p.status != 'Pago'
                )
                
            ORDER BY 2, data_parcela , valor_parcela",
            [$representante_id]
        );
        // dd($acertos);
        $representante = Representante::findOrFail($representante_id);
        $hoje = date('Y-m-d');
        $total_divida_valor = 0;
        $total_divida_valor_pago = 0;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'venda.pdf.pdf_acerto_documento',
            compact('representante_id',
            'acertos',
            'representante',
            'total_divida_valor',
            'total_divida_valor_pago',
            'hoje')
        );

        return $pdf->stream();
    }

    public function acertosRepresentante($representante_id)
    {
        $representante = Representante::findOrFail($representante_id);

        $acertos = DB::select( "SELECT DISTINCT
                c.id as cliente_id,
                (SELECT UPPER(nome) from pessoas WHERE id = c.pessoa_id) as cliente
            FROM
                vendas v
                    INNER JOIN parcelas p ON p.venda_id = v.id
                    LEFT JOIN clientes c ON c.id = v.cliente_id
                    LEFT JOIN representantes r ON r.id = v.representante_id
            WHERE
                p.deleted_at IS NULL
                AND v.deleted_at IS NULL
                AND r.id = ?
                AND (
                p.forma_pagamento like 'Cheque' AND p.status like 'Aguardando Envio'
                OR
                p.forma_pagamento != 'Cheque' AND p.status != 'Pago'
                )
                
            ORDER BY 2, data_parcela , valor_parcela",
            [$representante->id]
        );
        
        $hoje = date('Y-m-d');
        $total_divida_valor = 0;
        $total_divida_valor_pago = 0;

        return view('venda.acertos_representante', 
            compact('representante_id', 'acertos', 'representante', 'total_divida_valor', 'total_divida_valor_pago', 'hoje')
        );
    }

    public function pdf_conferencia_parcelas_relatorio_vendas($representante_id)
    {
        $representante = Representante::with('pessoa:id,nome')->findOrFail($representante_id);

        $parcelas = Parcela::whereHas('venda', function (Builder $query) {
            $query->whereNull('enviado_conta_corrente');
        })
        ->with('pagamentos_representantes')
        ->where('representante_id', $representante_id)
        ->get();

        $vendas = Venda::with('cliente.pessoa:id,nome')
            ->whereIn('id', $parcelas->pluck('venda_id'))
            ->get();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'venda.pdf.pdf_conferencia_parcelas_relatorio_vendas',compact('representante', 'parcelas', 'vendas')
        );
        return $pdf->stream();
    } 

    public function pdf_conferencia_relatorio_vendas_sem_avista($representante_id)
    {
        $vendas = Venda::with('cliente')
            ->whereNull('enviado_conta_corrente')
            ->where('representante_id', '=', $representante_id)
            ->orderBy('data_venda')
            ->get();
            
        $cheques = Parcela::whereIn('venda_id', $vendas->pluck('id'))->get();
        
        $pagamentos = Parcela::whereHas('venda', function (Builder $query) use ($representante_id) {
                $query->whereNull('enviado_conta_corrente')
                    ->where('representante_id', '=', $representante_id);
            })
        ->get();
    
        $pagamentosPorForma = $pagamentos->groupBy('forma_pagamento');

        $pagamentosTotal = $pagamentos->sum('valor_parcela');

        $representante = Representante::findOrFail($representante_id);


        $comissao_json = Storage::disk('public')
            ->get('comissao_representantes/porcentagem.json');
        $comissao_array = json_decode($comissao_json, true);

        $comissaoRepresentante = $comissao_array[$representante->id] ?? $comissao_array["Default"];

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('venda.pdf.pdf_conferencia_relatorio_vendas_sem_avista',
            compact(
                'vendas',
                'representante',
                'pagamentos',
                'pagamentosTotal',
                'pagamentosPorForma',
                'comissaoRepresentante',
                'cheques'
            )
        );

        return $pdf->stream();
    }
}
