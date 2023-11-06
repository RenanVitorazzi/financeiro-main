<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtualizarContaCorrenteParceiro;
use App\Http\Requests\RequestFormPessoa;
use App\Models\MovimentacaoCheque;
use App\Models\PagamentosParceiros as ModelsPagamentosParceiros;
use App\Models\Parceiro;
use App\Models\Parcela;
use App\Models\Pessoa;
use App\Models\TrocaAdiamento as ModelsTrocaAdiamento;
use App\Models\TrocaParcela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use PagamentosParceiros;
use TrocaAdiamento;

class ParceiroController extends Controller
{
    public function index(Request $request)
    {
        $parceiros = Parceiro::with('pessoa')->get();
        $message = $request->session()->get('message');

        return view('parceiro.index', compact('parceiros', 'message'));
    }

    public function create()
    {
        return view('parceiro.create');
    }

    public function store(RequestFormPessoa $request)
    {

        $request->validate([
            'porcentagem_padrao' => 'required|numeric|min:0|max:100',
        ]);

        $pessoa = Pessoa::create($request->all());

        $parceiro = Parceiro::create([
            'pessoa_id' => $pessoa->id,
            'porcentagem_padrao' => $request->porcentagem_padrao
        ]);

        $request
            ->session()
            ->flash(
                'message',
                'Parceiro cadastrado com sucesso!'
            );

        return redirect()->route('parceiros.index');
    }

    public function edit($id)
    {
        $parceiro = Parceiro::findOrFail($id);

        return view('parceiro.edit', compact('parceiro'));
    }

    public function update (RequestFormPessoa $request, $id)
    {
        $parceiro = Parceiro::findOrFail($id);

        $pessoa = Pessoa::findOrFail($parceiro->pessoa_id);

        $pessoa->fill($request->all())
            ->save();

        $parceiro->porcentagem_padrao = $request->porcentagem_padrao;
        $parceiro->save();

        $request
            ->session()
            ->flash(
                'message',
                'Parceiro atualizado com sucesso!'
            );

        return redirect()->route('parceiros.index');
    }

    public function destroy (Request $request, $id)
    {
        Parceiro::destroy($id);

        $request
        ->session()
        ->flash(
            'message',
            'Parceiro excluído com sucesso!'
        );

        return redirect()->route('parceiros.index');
    }

    public function pdf_cc_parceiro ($parceiro_id)
    {
        $parceiro = Parceiro::findOrFail($parceiro_id);

        $saldos = DB::select('SELECT * FROM
                (SELECT
                        mc.data as rank2,
                        p.nome_cheque AS nome_cheque,
                        p.valor_parcela as valor,
                        mc.status,
                        p.numero_cheque
                    FROM
                        movimentacoes_cheques mc
                            INNER JOIN
                        parcelas p ON p.id = mc.parcela_id
                    WHERE
                        p.parceiro_id = ? AND mc.status IN (?,?)
                        AND mc.parcela_id NOT IN  (SELECT parcela_id
                            FROM movimentacoes_cheques
                            WHERE status like ?
                            and deleted_at is null
                            AND parcela_id = mc.parcela_id
                )
            UNION ALL
                SELECT
                    ta2.created_at as rank2,
                    p2.nome_cheque AS nome_cheque,
                    ta2.juros_totais as valor,
                    ?,
                    p2.numero_cheque
                FROM parcelas p2
                INNER JOIN troca_adiamentos ta2
                    ON ta2.parcela_id = p2.id 
                    AND ta2.pago is null 
                    AND p2.parceiro_id = ? 
                    AND ta2.deleted_at is null
            UNION ALL
                SELECT
                    pp.data as rank2,
                    UPPER(pp.observacao),
                    pp.valor,
                    ?,
                    
                    pp.forma_pagamento
                    FROM pagamentos_parceiros pp WHERE parceiro_id = ?
                    AND deleted_at is null
                    and baixado is null
                ) a order by rank2',
            [$parceiro_id, 'Devolvido', 'Resgatado', 'Pago parceiro', 'Prorrogação', $parceiro_id, 'Crédito', $parceiro_id]
        );

        $saldo_total = 0;

        $pdf = App::make('dompdf.wrapper');
        $hoje = date('Y-m-d');
        $pdf->loadView('parceiro.pdf.pdf_cc_parceiro', compact('saldos', 'parceiro', 'saldo_total', 'hoje') );

        return $pdf->stream();
    }

    public function configurar_cc_parceiros ($parceiro_id) {
        if(auth()->user()->id !== 1) {
            return false;
        }
    
        $parceiro = Parceiro::with('pessoa:id,nome')->findOrFail($parceiro_id);

        $saldos = DB::select('SELECT * FROM
                (SELECT
                        mc.data as rank2,
                        p.nome_cheque AS nome_cheque,
                        p.valor_parcela as valor,
                        mc.status,
                        p.numero_cheque,
                        p.id,
                        ? as tabela
                    FROM
                        movimentacoes_cheques mc
                            INNER JOIN
                        parcelas p ON p.id = mc.parcela_id
                    WHERE
                        p.parceiro_id = ? AND mc.status IN (?,?)
                        AND mc.parcela_id NOT IN  (SELECT parcela_id
                            FROM movimentacoes_cheques
                            WHERE status like ?
                            and deleted_at is null
                            AND parcela_id = mc.parcela_id
                )
            UNION ALL
                SELECT
                    ta2.created_at as rank2,
                    p2.nome_cheque AS nome_cheque,
                    ta2.juros_totais as valor,
                    ?,
                    p2.numero_cheque,
                    ta2.id,
                    ?
                FROM parcelas p2
                INNER JOIN troca_adiamentos ta2
                    ON ta2.parcela_id = p2.id 
                    AND ta2.pago is null 
                    AND p2.parceiro_id = ? 
                    AND ta2.deleted_at is null
            UNION ALL
                SELECT
                    pp.data as rank2,
                    UPPER(pp.observacao),
                    pp.valor,
                    ?,
                    pp.forma_pagamento,
                    pp.id,
                    ?
                    FROM pagamentos_parceiros pp WHERE parceiro_id = ?
                    AND deleted_at is null
                    and baixado is null
            ) a order by rank2',
            [
                'mc',
                $parceiro_id, 
                'Devolvido', 
                'Resgatado', 
                'Pago parceiro', 
                'Prorrogação',
                'ta', 
                $parceiro_id, 
                'Crédito', 
                'pp',
                $parceiro_id
            ]
        );

        $saldo_total = 0;

       return view('parceiro.configurar_cc_parceiros', compact('saldo_total', 'parceiro', 'saldos'));

    }

    public function atualizar_conta_corrente (AtualizarContaCorrenteParceiro $request, $parceiro_id) {

        $resultado = DB::transaction(function () use ($request) {
            if ($request->mc) {
                foreach ($request->mc as $key => $parcela_id) {
                    MovimentacaoCheque::create([
                        'status' => 'Pago parceiro',
                        'data' => now(),
                        'parcela_id' => $parcela_id
                    ]);
                }
            }
            if($request->ta) {
                foreach ($request->ta as $key => $adiamento_id) {
                    ModelsTrocaAdiamento::findOrFail($adiamento_id)->update(['pago' => 1]);
                }
            }
            
            if($request->pp) {
                foreach ($request->pp as $key => $pagamento_parceiro_id) {
                    ModelsPagamentosParceiros::findOrFail($pagamento_parceiro_id)->update(['baixado' => 1]);
                }
            }
            
        });

        return redirect()->route('configurar_cc_parceiros', compact('parceiro_id'));
        
    }
}

?>
