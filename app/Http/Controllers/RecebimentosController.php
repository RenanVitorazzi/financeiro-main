<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcurarRecebimentoRequest;
use App\Http\Requests\RecebimentosApiRequestStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\Conta;
use App\Models\Parcela;
use App\Models\Parceiro;
use App\Models\Representante;
use App\Models\PagamentosParceiros;
use App\Models\MovimentacaoCheque;
use App\Models\ParcelaRepresentante;
use App\Models\PagamentosRepresentantes;
use App\Http\Requests\RecebimentosUpdateRequest;
use App\Http\Requests\RecebimentosRequestStore;

class RecebimentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $representantes = Representante::all();
        $contas = Conta::all();

        $pgtoRepresentante = PagamentosRepresentantes::with('parcela')
            ->orderBy('id', 'desc')
            ->take(25)
            ->get();

        return view('recebimento.index', compact('pgtoRepresentante', 'representantes', 'contas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contas = Conta::all();
        $parceiros = Parceiro::all();
        $representantes = Representante::all();
        $data = NULL;
        $descricao = NULL;
        $valor = NULL;
        $contaImportacao = NULL;
        $forma_pagamento = NULL;
        $confirmado = NULL;
        $tipo_pagamento = NULL;
        $comprovante_id = NULL;
        $parcela = NULL;

        return view('recebimento.create', 
            compact('contas', 
            'parceiros', 
            'representantes', 
            'data', 
            'descricao', 
            'valor', 
            'contaImportacao', 
            'forma_pagamento', 
            'confirmado', 
            'tipo_pagamento', 
            'parcela',
            'comprovante_id')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecebimentosRequestStore $request)
    {
        if ($request->parcela_id) {
            $parcela = Parcela::find($request->parcela_id);
        }

        if ($request->tipo_pagamento == 1) {
            $pagamentoParceiro = PagamentosParceiros::create($request->validated());
            if ($request->parcela_id) {
                Parcela::where('id', $parcela->id)->update(['status' => 'Pago']);
            }
        } else if ($request->tipo_pagamento == 2) {

            $pagamentoRepresentante = PagamentosRepresentantes::create(
                array_merge(
                    $request->validated(),
                    ['representante_id' => $request->representante_id]
                )
            );

            if ($request->parcela_id) {
                $valorTotalPago = PagamentosRepresentantes::where('parcela_id', $parcela->id)
                    ->sum('valor');

                if ($valorTotalPago >= $parcela->valor_parcela) {

                    Parcela::where('id', $parcela->id)->update(['status' => 'Pago']);
                    MovimentacaoCheque::create([
                        'parcela_id' => $parcela->id,
                        'status' => 'Pago representante',
                    ]);

                }
            }

        } else if ($request->tipo_pagamento == 3 || $request->tipo_pagamento == 4) {

            $pagamentoRepresentante = PagamentosRepresentantes::create($request->validated());

            $pagamentoParceiro = PagamentosParceiros::create($request->validated());
            
            if ($parcela->id) {
                $valorTotalPagoRepresentante = PagamentosRepresentantes::where('parcela_id', $parcela->id)
                    ->sum('valor');


                $valorTotalPagoParceiro = PagamentosParceiros::where('parcela_id', $parcela->id)
                    ->sum('valor');

                if ($valorTotalPagoRepresentante >= $parcela->valor_parcela) {
                    MovimentacaoCheque::create([
                        'parcela_id' => $parcela->id,
                        'status' => 'Pago representante',
                    ]);
                }

                if ($valorTotalPagoParceiro >= $parcela->valor_parcela) {
                    MovimentacaoCheque::create([
                        'parcela_id' => $parcela->id,
                        'status' => 'Pago parceiro',
                    ]);

                }
            }

        }

        $request
        ->session()
        ->flash(
            'message',
            'Recebimento cadastrado com sucesso!'
        );

        return redirect()->route('recebimentos.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($request)
    {
        $contas = Conta::all();
        $parceiros = Parceiro::all();
        $representantes = Representante::all();
        $pagamentosRepresentantes = PagamentosRepresentantes::findOrFail($request);
        $formasPagamento = ['Pix', 'TED', 'Depósito', 'DOC', 'Dinheiro', 'Cheque'];
        $outrosPagamentos =  PagamentosRepresentantes::where([
            ['parcela_id', $pagamentosRepresentantes->parcela_id], 
            ['representante_id', $pagamentosRepresentantes->representante_id]
        ])    
            // ->where('id', '=', $pagamentosRepresentantes->id)
            ->get();
        // dd($pagamentosRepresentantes);
        return view('recebimento.edit', compact('pagamentosRepresentantes', 'contas', 'parceiros', 'representantes', 'formasPagamento', 'outrosPagamentos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RecebimentosUpdateRequest $request, $recebimento_id)
    {
        PagamentosRepresentantes::where('id', $recebimento_id)
            ->update($request->validated());

        $request
        ->session()
        ->flash(
            'message',
            'Recebimento alterado com sucesso!'
        );

        return redirect()->route('recebimentos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        PagamentosRepresentantes::destroy($id);

        $request
        ->session()
        ->flash(
            'message',
            'Excluído com sucesso!'
        );

        return redirect()->route('recebimentos.index');
    }

    public function criarRecebimentoImportacao($data, $descricao, $valor, $contaImportacao, $forma_pagamento, $confirmado, $tipo_pagamento, $comprovante_id)
    {
        $contas = Conta::all();
        $parceiros = Parceiro::all();
        $representantes = Representante::all();
        
        $parcela = NULL;

        return view('recebimento.create', compact('contas', 
            'parceiros', 
            'representantes', 
            'data', 
            'descricao', 
            'valor', 
            'contaImportacao', 
            'forma_pagamento', 
            'confirmado', 
            'tipo_pagamento', 
            'parcela',
            'comprovante_id')
        );
    }

    public function criarRecebimentoPeloAcerto($parcela_id)
    {
        $contas = Conta::all();
        $parceiros = Parceiro::all();
        $representantes = Representante::all();
        $data = NULL;
        $descricao = NULL;
        $valor = NULL;
        $contaImportacao = NULL;
        $forma_pagamento = NULL;
        $confirmado = NULL;
        $tipo_pagamento = NULL;
        $comprovante_id = NULL;
        $parcela = Parcela::withSum('pagamentos_representantes','valor')->with('pagamentos_representantes')->findOrFail($parcela_id);
        // dd($parcela);
        return view('recebimento.create', compact('contas', 
            'parceiros', 
            'representantes', 
            'data', 
            'descricao', 
            'valor', 
            'contaImportacao', 
            'forma_pagamento', 
            'confirmado', 
            'tipo_pagamento', 
            'parcela',
            'comprovante_id')
        );
    }

    public function pdf_confirmar_depositos()
    {
        $depositos = PagamentosRepresentantes::where('confirmado', 0)
            ->whereNull('baixado')
            ->orderBy('conta_id')
            ->orderBy('data')
            ->orderBy('valor')
            ->get();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('recebimento.pdf.pdf_confirmar_depositos', compact('depositos'));
        return $pdf->stream();
    }

    public function recebimentoCreateApi (RecebimentosApiRequestStore $request) 
    {
        $parcela = Parcela::findOrFail($request->parcela_id);
        
        PagamentosRepresentantes::create(
            array_merge(
                $request->validated(),
                ['representante_id' => $parcela->representante_id]
            )
        );
        
        $valorTotalPago = PagamentosRepresentantes::where('parcela_id', $parcela->id)->sum('valor');
        
        if ($valorTotalPago >= $parcela->valor_parcela) {

            Parcela::where('id', $parcela->id)->update(['status' => 'Pago']);
            
            MovimentacaoCheque::create([
                'parcela_id' => $parcela->id,
                'status' => 'Pago representante',
            ]);

        }
        
    }

    public function linkarPixId(Request $request)
    {
        $pr = PagamentosRepresentantes::findOrFail($request->pr_id);

        $pr->update([
            'confirmado' => 1,
            'conta_id' => $request->conta_id,
            'comprovante_id' => $request->comprovante_id
        ]);

        return response()->json([
            'status' => 'success', 
            'data' => [], 
            'message' => 'Dados atualizados!'
        ], 200);
    }

    public function procurarRecebimento(ProcurarRecebimentoRequest $request)
    {
        // return json_encode($request->confirmado);
        $recebimentos = PagamentosRepresentantes::with('representante', 'representante.pessoa', 'conta');
            
        if ($request->valor) {
            $recebimentos = $recebimentos->where('valor', $request->valor);
        }

        if ($request->data) {
            $recebimentos = $recebimentos->whereDate('data', $request->data);
        }

        if ($request->representante_id) {
            $recebimentos = $recebimentos->where('representante_id', $request->representante_id);
        }

        if ($request->conta_id) {
            $recebimentos = $recebimentos->where('conta_id', $request->conta_id);
        }

        if (in_array($request->confirmado, [0, 1])) {
            $recebimentos = $recebimentos->where('confirmado', $request->confirmado);
        }

        // return json_encode([$recebimentos->dd()]); 
        return $recebimentos->orderBy('data')->take(50)->get()->toJson();

    }
    
}
