<?php

namespace App\Http\Controllers;

use App\Http\Requests\baixarDebitosRepresentantesRequest;
use App\Http\Requests\RequestFormPessoa;
use App\Http\Requests\UpdateFormRepresentante;
use App\Models\Adiamento;
use App\Models\Consignado;
use App\Models\ContaCorrenteRepresentante;
use App\Models\PagamentosRepresentantes;
use App\Models\Parcela;
use App\Models\Pessoa;
use App\Models\Representante;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RepresentanteController extends Controller {

    public function index(Request $request)
    {
        $representantes = Representante::with('pessoa:id,nome')
            ->withSum('conta_corrente', 'peso_agregado')
            ->withSum('conta_corrente', 'fator_agregado')
            ->orderBy('inativo')
            ->orderBy('atacado')
            ->orderBy('id')
            ->get();

        $message = $request->session()->get('message');

        return view('representante.index', compact('representantes', 'message'));
    }

    public function create()
    {
        return view('representante.create');
    }

    public function store(RequestFormPessoa $request)
    {
        DB::transaction(function () use ($request) {
            $pessoa = Pessoa::create($request->validated());

            Representante::create([
                'pessoa_id' => $pessoa->id
            ]);
        });

        $request
            ->session()
            ->flash(
                'message',
                'Representante cadastrado com sucesso!'
            );
        return redirect()->route('representantes.index');
    }

    public function edit($id)
    {
        $representante = Representante::findOrFail($id);

        return view('representante.edit', compact('representante'));
    }

    public function show($id)
    {
        $representante = Representante::with('pessoa')
            ->withSum('conta_corrente', 'peso_agregado')
            ->withSum('conta_corrente', 'fator_agregado')
            ->adiamentos()
            ->findOrFail($id);

        $devolvidos = Parcela::where('representante_id', $id)
            ->whereHas('entrega', function ($query) {
                $query->whereNull('entregue_representante')
                    ->whereNotNull('entregue_parceiro');
            })
            ->where('status', '<>', 'Pago')
            ->orderBy('data_parcela')
            ->orderBy('valor_parcela')
            ->get();

        return view('representante.show', compact('representante', 'devolvidos'));
    }

    public function update (UpdateFormRepresentante $request, $id)
    {
        $representante = Representante::findOrFail($id);
        $pessoa = Pessoa::findOrFail($representante->pessoa_id);

        DB::transaction(function () use ($request, $pessoa, $representante) {
            $pessoa->fill($request->validated())
                ->save();

            $representante->update(['inativo' => $request->inativo]);
        });

        $request
            ->session()
            ->flash(
                'message',
                'Representante atualizado com sucesso!'
            );

        return redirect()->route('representantes.index');
    }

    public function destroy (Request $request, $id)
    {
        Representante::destroy($id);

        $request
            ->session()
            ->flash(
                'message',
                'Registro deletado com sucesso!'
            );

        return redirect()->route('representantes.index');
    }

    public function representanteDashboard(Representante $representante) 
    {
        $pessoa = Pessoa::findOrFail($representante->pessoa_id);
        $devolvidosComParceiros = Parcela::devolvidosComParceiros($representante->id)->get();
        $devolvidosNoEscritorio = Parcela::devolvidosNoEscritorio($representante->id)->get();
         
        $contaCorrente = ContaCorrenteRepresentante::where('representante_id', $representante->id)->get();
        //TODO 
        // FAZER QUERY DE CHEQUES DEVOLVIDOS -> PROVAVELMETENTE CRIAR UM SERVICE
        // $infoRepresentante = [
        //     1 => [
        //         'Saldo' => -24269,
        //         'Data' => '2023-04-13'
        //     ],
        //     5 => [
        //         'Saldo' => -33974,
        //         'Data' => '2023-04-13'
        //     ],
        //     8 => [
        //         'Saldo' => 0,
        //         'Data' => '2023-07-05'
        //     ],
        //     12 => [
        //         'Saldo' => 0,
        //         'Data' => '2023-07-05'
        //     ],
        //     20 => [
        //         'Saldo' => -51400,
        //         'Data' => '2023-04-13'
        //     ],
        //     23 => [
        //         'Saldo' => -26486,
        //         'Data' => '2023-04-13'
        //     ],
        //     24 => [
        //         'Saldo' => 0,
        //         'Data' => '2023-04-13'
        //     ],
        //     26 => [
        //         'Saldo' => 0,
        //         'Data' => '2023-07-05'
        //     ],
        //     'Default' => [
        //         'Saldo' => 0,
        //         'Data' => '2023-01-02'
        //     ]
        // ];
        
        // $saldo_total = $infoRepresentante['Default']['Saldo'];
        // $data_inicio = $infoRepresentante['Default']['Data'];

        // if (array_key_exists($representante->id, $infoRepresentante)) {
        //     $saldo_total = $infoRepresentante[$representante->id]['Saldo'];
        //     $data_inicio = $infoRepresentante[$representante->id]['Data'];
        // }

        $arquivo_json = Storage::disk('public')
        ->get('comissao_representantes/conta_corrente_cheques.json');

        $infoRepresentante = json_decode($arquivo_json, true);

        $saldo_total = $infoRepresentante['Default']['Saldo'];
        $data_inicio = $infoRepresentante['Default']['Data'];

        if (array_key_exists($representante->id, $infoRepresentante)) {
            $saldo_total = $infoRepresentante[$representante->id]['Saldo'];
            $data_inicio = $infoRepresentante[$representante->id]['Data'];
        }

        $pagamentosRepresentantes = PagamentosRepresentantes::query()
            ->select('data', 'observacao', 'valor')
            ->where('representante_id', $representante->id)
            ->whereNull('baixado')
            ->whereNull('parcela_id')
            ->get();

        $entregas = Parcela::query()
            ->withSum('pagamentos_representantes', 'valor')
            ->where('representante_id', $representante->id)
            ->whereHas('entrega', function ($query) use ($data_inicio) {
                $query->where('entregue_representante', '>=', $data_inicio);
            })
            ->get();
    
    
        $saldoContaCorrenteChsDevolvidos = ($saldo_total - $entregas->sum('valor_parcela') ) + ($entregas->sum('pagamentos_representantes_sum_valor') + $pagamentosRepresentantes->sum('valor'));

        $acertos = Parcela::query()
            ->with('venda')
            ->where([
                ['forma_pagamento', 'LIKE', 'Cheque'], 
                ['status', 'LIKE', 'Aguardando Envio'],
                ['representante_id', $representante->id]
            ])
            ->orWhere([
                ['forma_pagamento', 'NOT LIKE', 'Cheque'], 
                ['status', 'NOT LIKE', 'Pago'],
                ['representante_id', $representante->id]
            ])
            ->get();
            //->groupBy('venda.cliente_id');
        
        $ultimoRelatorioVendasId = Venda::where('representante_id', $representante->id)->max('enviado_conta_corrente');
        $ultimoRelatorioVendas = Venda::query()
            ->where('enviado_conta_corrente', $ultimoRelatorioVendasId)
            ->get();
            
        $consignados = Consignado::query()
            ->where('representante_id', $representante->id)
            ->whereNull('baixado')
            ->get();
        
        return view('representante.dashboard', 
            compact(
                'pessoa', 
                'devolvidosComParceiros',
                'devolvidosNoEscritorio',
                'ultimoRelatorioVendas',
                'consignados',
                'contaCorrente',
                'representante',
                'acertos',
                'saldoContaCorrenteChsDevolvidos'
            )
        );

    }

    public function impresso()
    {
        $representantes = Representante::with('pessoa', 'conta_corrente')->get();
        $contaCorrenteGeral = ContaCorrenteRepresentante::get();
        $devolvidos = Parcela::where('status', 'Devolvido')->get();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('representante.pdf.impresso', compact('representantes', 'contaCorrenteGeral', 'devolvidos') );

        return $pdf->stream();
    }

    public function baixarDebitosRepresentantes(baixarDebitosRepresentantesRequest $request, $representante_id)
    {
        //TODO criar botão desfazer
        if ($request->devolvidos) {
            Parcela::whereIn('id', $request->devolvidos)->update(['status' => 'Pago']);
        }

        if ($request->adiamentos) {
            Adiamento::whereIn('id', $request->adiamentos)->update(['pago' => 1]);
        }

        return redirect()->route('representantes.show', $representante_id);

    }

    public function pdf_cc_representante($representante_id)
    {
        $representante = Representante::findOrFail($representante_id);
        
        $arquivo_json = Storage::disk('public')
            ->get('comissao_representantes/conta_corrente_cheques.json');

        $infoRepresentante = json_decode($arquivo_json, true);
        
        $saldo_total = $infoRepresentante['Default']['Saldo'];
        $data_inicio = $infoRepresentante['Default']['Data'];

        if (array_key_exists($representante_id, $infoRepresentante)) {
            $saldo_total = $infoRepresentante[$representante_id]['Saldo'];
            $data_inicio = $infoRepresentante[$representante_id]['Data'];
        }

        $saldos = DB::select('SELECT
                (sum(p.valor_parcela) - (SELECT COALESCE(SUM(pr.valor), 0) FROM pagamentos_representantes pr WHERE pr.deleted_at is null  and representante_id = ? AND pr.parcela_id in (SELECT ep1.parcela_id FROM entrega_parcela ep1 where ep1.entregue_representante = ep.entregue_representante) ) ) as valor_total_debito,
                ep.entregue_representante as data_entrega,
                ? as balanco,
                ? as descricao
                FROM movimentacoes_cheques m
                INNER JOIN parcelas p ON p.id = m.parcela_id AND p.representante_id = ? AND (m.status = ? OR m.status = ?)
                INNER JOIN entrega_parcela ep ON p.id = ep.parcela_id AND entregue_representante IS NOT NULL
                WHERE ep.entregue_representante >= ?
                AND ep.enviado IS NULL
                group by ep.entregue_representante
            UNION
                SELECT  pr.valor, pr.data as data_entrega, ? as balanco, UPPER(pr.observacao) as descricao
                FROM pagamentos_representantes pr
                WHERE pr.representante_id = ?
                AND pr.baixado IS NULL
                AND pr.parcela_id IS NULL
                AND pr.deleted_at IS NULL
            ORDER BY data_entrega',
            [
                $representante_id,
                'Débito',
                'CHEQUES ENTREGUES EM MÃOS',
                $representante_id,
                'Devolvido',
                'Resgatado',
                $data_inicio,
                'Crédito',
                $representante_id
            ]
        );
        
        $chequesNaoEntregues = Parcela::query()
            ->devolvidosNoEscritorio($representante_id)
            ->withSum('pagamentos_representantes', 'valor')
            ->get();
        
        $chequesComParceiros = Parcela::query()
            ->devolvidosComParceiros($representante_id)
            ->withSum('pagamentos_representantes', 'valor')
            ->get();

        $pdf = App::make('dompdf.wrapper');
        $hoje = date('Y-m-d');
        $pdf->loadView('representante.pdf.pdf_cc_representante_novo', 
            compact(
                'chequesComParceiros', 
                'saldos', 
                'representante', 
                'saldo_total', 
                'data_inicio',
                'hoje', 
                'infoRepresentante', 
                'chequesNaoEntregues'
            ) 
        );

        return $pdf->stream();
    }

    public function pdf_cheques_devolvidos_escritorio($representante_id)
    {
        $representante = Representante::findOrFail($representante_id);

        $cheques = Parcela::devolvidosNoEscritorio($representante_id)->get();
            
        $totalPago = $cheques->sum(function ($cheques) {
            return $cheques->pagamentos_representantes->sum('valor');
        });
        
        $pdf = App::make('dompdf.wrapper');
        $hoje = date('Y-m-d');
        $pdf->loadView('representante.pdf.pdf_cheques_devolvidos_escritorio',
            compact('cheques', 'representante', 'totalPago', 'hoje')
        )->setPaper('a4', 'landscape');

        return $pdf->stream();
    }

    public function pdf_cc_representante_com_cheques_devolvidos($representante_id)
    {
        $representante = Representante::findOrFail($representante_id);

        $arquivo_json = Storage::disk('public')
        ->get('comissao_representantes/conta_corrente_cheques.json');

        $infoRepresentante = json_decode($arquivo_json, true);

        $saldo_total = $infoRepresentante['Default']['Saldo'];
        $data_inicio = $infoRepresentante['Default']['Data'];

        if (array_key_exists($representante_id, $infoRepresentante)) {
            $saldo_total = $infoRepresentante[$representante_id]['Saldo'];
            $data_inicio = $infoRepresentante[$representante_id]['Data'];
        }

        // $infoRepresentante = [
        //     1 => [
        //         'Saldo' => -24269,
        //         'Data' => '2023-04-13'
        //     ],
        //     5 => [
        //         'Saldo' => -33974,
        //         'Data' => '2023-04-13'
        //     ],
        //     12 => [
        //         'Saldo' => 0,
        //         'Data' => '2023-08-24'
        //     ],
        //     20 => [
        //         'Saldo' => -51400,
        //         'Data' => '2023-04-13'
        //     ],
        //     23 => [
        //         'Saldo' => -26486,
        //         'Data' => '2023-04-13'
        //     ],
        //     24 => [
        //         'Saldo' => 0,
        //         'Data' => '2023-04-13'
        //     ],
        //     26 => [
        //         'Saldo' => 0,
        //         'Data' => '2023-08-24'
        //     ],
        // ];
        $saldos = DB::select('SELECT
                (sum(p.valor_parcela) - (SELECT COALESCE(SUM(pr.valor), 0) FROM pagamentos_representantes pr WHERE pr.deleted_at is null  and representante_id = ? AND pr.parcela_id in (SELECT ep1.parcela_id FROM entrega_parcela ep1 where ep1.entregue_representante = ep.entregue_representante) ) ) as valor_total_debito,
                ep.entregue_representante as data_entrega,
                ? as balanco,
                ? as descricao
                FROM movimentacoes_cheques m
                INNER JOIN parcelas p ON p.id = m.parcela_id AND p.representante_id = ? AND m.status IN (?, ?)
                INNER JOIN entrega_parcela ep ON p.id = ep.parcela_id AND entregue_representante IS NOT NULL
                WHERE ep.entregue_representante >= ?
                group by ep.entregue_representante
            UNION
                SELECT  pr.valor, pr.data as data_entrega, ? as balanco, pr.observacao as descricao
                FROM pagamentos_representantes pr
                WHERE pr.representante_id = ?
                AND pr.baixado IS NULL
                AND pr.parcela_id IS NULL
                AND pr.deleted_at IS NULL
            ORDER BY data_entrega',
                [
                    $representante_id,
                    'Débito',
                    'Cheque entregue em mãos',
                    $representante_id,
                    'Devolvido',
                    'Resgatado',
                    $data_inicio,
                    'Crédito',
                    $representante_id
                ]
            );

        // $saldo_total = $infoRepresentante[$representante_id]['Saldo'];

        $contaCorrenteRepresentante = ContaCorrenteRepresentante::where('representante_id', $representante->id)
            ->get();

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('representante.pdf.pdf_cc_representante_com_cheques_devolvidos',
            compact('saldos', 'representante', 'saldo_total', 'infoRepresentante', 'contaCorrenteRepresentante')
        );
        // )->setPaper('a4', 'landscape');

        return $pdf->stream();

    }
    
    public function pdf_cheques_devolvidos_parceiros ($representante_id) 
    {
        $representante = Representante::findOrFail($representante_id);
        $hoje = date('Y-m-d');
        
        $cheques = Parcela::devolvidosComParceiros($representante_id)->get();
        $totalPago = 0;

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('representante.pdf.pdf_cheques_devolvidos_parceiros',
            compact('cheques', 'representante', 'hoje', 'totalPago')
        );
        return $pdf->stream();
    }

    
    public function relacao_deb_cred_representantes ($data) {
        // $cc = ContaCorrente::where('data', '<=', $data)->groupBy('fornecedor_id')->get();
        $cc = Representante::query()
            ->with('pessoa:id,nome')
            ->withSum(['conta_corrente as credito_peso' => function ($query) use ($data) {
                $query->whereDate('data', '>=', $data);
                $query->where('balanco', 'LIKE', 'Reposição');
            }], 'peso')
            ->withSum(['conta_corrente as debito_peso' => function ($query) use ($data) {
                $query->whereDate('data', '>=', $data);
                $query->where('balanco', 'LIKE', 'Venda');
            }], 'peso')
            ->withSum(['conta_corrente as credito_fator' => function ($query) use ($data) {
                $query->whereDate('data', '>=', $data);
                $query->where('balanco', 'LIKE', 'Reposição');
            }], 'fator')
            ->withSum(['conta_corrente as debito_fator' => function ($query) use ($data) {
                $query->whereDate('data', '>=', $data);
                $query->where('balanco', 'LIKE', 'Venda');
            }], 'fator')
            ->whereNull('inativo')
            ->orderBy('debito_peso', 'desc')
            ->get();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'relatorios.relacao_deb_cred_representantes',
            compact(
               'data',
               'cc'
            )
        );

        return $pdf->stream();
    }
}

?>
