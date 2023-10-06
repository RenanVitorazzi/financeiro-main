<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestFormPessoa;
use App\Http\Requests\UpdateFornecedorRequest;
use App\Models\Adiamento;
use App\Models\Fornecedor;
use App\Models\Pessoa;
use App\Models\ContaCorrente;
use App\Models\ContaCorrenteRepresentante;
use App\Models\Despesa;
use App\Models\Estoque;
use App\Models\Local;
use App\Models\MovimentacaoCheque;
use App\Models\PagamentosRepresentantes;
use App\Models\Parceiro;
use App\Models\Parcela;
use App\Models\Representante;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FornecedorController extends Controller
{
    public function index(Request $request)
    {
        $fornecedores = Fornecedor::with(['pessoa'])
            // ->withCount(['contaCorrente' => function (Builder $query) {
            //     $query->whereNull('peso_agregado');
            // }])
            ->withSum('contaCorrente', 'peso_agregado')
            ->orderBy('inativo')
            ->orderBy('conta_corrente_sum_peso_agregado')
            ->get();

        $lancamentos_pendentes = contaCorrente::query()
            ->whereNull('peso_agregado')
            ->get();

        // $labels = json_encode($fornecedores->pluck('pessoa.nome'));

        // $data = json_encode($fornecedores->pluck('conta_corrente_sum_peso_agregado'));

        $message = $request
            ->session()
            ->get('message');

        return view('fornecedor.index', compact('fornecedores', 'message', 'lancamentos_pendentes'));
    }

    public function create()
    {
        return view('fornecedor.create');
    }

    public function store(RequestFormPessoa $request)
    {
        $pessoa = Pessoa::create($request->validated());

        Fornecedor::create([
            'pessoa_id' => $pessoa->id,
        ]);

        $request
            ->session()
            ->flash(
                'message',
                'Fornecedor cadastrado com sucesso!'
            );

        return redirect()->route('fornecedores.index');
    }

    public function show($id)
    {
        $fornecedor = Fornecedor::with('pessoa')->findOrFail($id);

        $registrosContaCorrente = ContaCorrente::extrato($id);

        $ultimaConferencia = $registrosContaCorrente->whereNotNull('conferido')->last();
       
        $filtrarConferencia = !$ultimaConferencia ? true : false;

        $data_inicio = Carbon::now()->modify('-6 months')->format('Y-m-d');
        
        return view('fornecedor.show', 
            compact('fornecedor', 
            'registrosContaCorrente',
            'ultimaConferencia', 
            'data_inicio',
            'filtrarConferencia')
        );
    }

    public function edit($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);

        return view('fornecedor.edit', compact('fornecedor'));
    }

    public function update(UpdateFornecedorRequest $request, $id)
    {
        $fornecedor = Fornecedor::findOrFail($id);
        $fornecedor->update(['inativo' => $request->inativo]);

        $pessoa = Pessoa::findOrFail($fornecedor->pessoa_id);

        $pessoa->fill($request->all())
            ->save();

        $request
            ->session()
            ->flash(
                'message',
                'Fornecedor atualizado com sucesso!'
            );

        return redirect()->route('fornecedores.index');
    }

    public function destroy(Request $request, $id)
    {
        Fornecedor::destroy($id);
        ContaCorrente::where('fornecedor_id', $id)->delete();

        $request
        ->session()
        ->flash(
            'message',
            'Fornecedor excluído com sucesso!'
        );

        return redirect()->route('fornecedores.index');
    }

    public function pdf_fornecedores()
    {
        $fornecedores = Fornecedor::saldoFornecedores();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('fornecedor.pdf.fornecedores', compact('fornecedores') );

        return $pdf->stream();
    }

    public function pdf_fornecedor($id, $data_inicio)
    {
        $fornecedor = Fornecedor::with('pessoa:id,nome')->findOrFail($id);

        $registrosContaCorrente = ContaCorrente::extrato($id);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('fornecedor.pdf.relacao_fornecedor', compact('fornecedor', 'data_inicio', 'registrosContaCorrente') );

        return $pdf->stream();
    }

    public function pdf_diario2()
    {
        $fornecedores = Fornecedor::with(['pessoa'])
            ->withSum('contaCorrente', 'peso_agregado')
            ->get()
            ->sortBy('conta_corrente_sum_peso_agregado');

        $carteira = DB::select('SELECT
                sum(valor_parcela) as total_mes,
                MONTH(IF(par.status = ?,
                    (SELECT nova_data FROM adiamentos WHERE parcela_id = par.id ORDER BY id desc LIMIT 1),
                    par.data_parcela
                )) as month,
                YEAR(IF(par.status = ?,
                    (SELECT nova_data FROM adiamentos WHERE parcela_id = par.id ORDER BY id desc LIMIT 1),
                    par.data_parcela
                )) as year
            FROM
                parcelas par
            WHERE
                par.status in (?,?)
                    AND par.deleted_at IS NULL
                    AND parceiro_id IS NULL
                    AND forma_pagamento LIKE ?
                    GROUP BY YEAR( IF(par.status = ?,
                    (SELECT nova_data FROM adiamentos WHERE parcela_id = par.id ORDER BY id desc LIMIT 1),
                    par.data_parcela
                ) ), MONTH( IF(par.status = ?,
                    (SELECT nova_data FROM adiamentos WHERE parcela_id = par.id ORDER BY id desc LIMIT 1),
                    par.data_parcela
                ) )
            ORDER BY 3,2',
            ['Adiado', 'Adiado', 'Adiado', 'Aguardando', 'Cheque', 'Adiado', 'Adiado']
        );

        $totalCarteira =  DB::select('SELECT
                sum(valor_parcela) as totalCarteira
            FROM
                parcelas par
            WHERE
                par.status in (?,?)
                    AND par.deleted_at IS NULL
                    AND parceiro_id IS NULL
                    AND forma_pagamento LIKE ?',
            ['Adiado', 'Aguardando', 'Cheque']
        );

        $devolvidos = Parcela::where('status', 'Devolvido')->get();
        $estoque = Estoque::get();

        $representantes = Representante::with('pessoa')
            ->withSum('conta_corrente', 'peso_agregado')
            ->withSum('conta_corrente', 'fator_agregado')
            ->orderBy('atacado')
            ->get();

        // $adiamentos = Parcela::withSum('adiamentos', 'juros_totais')
        //     ->whereHas('adiamentos')
        //     ->get();


        // $parceiros = DB::select('SELECT
        //         SUM(juros_totais) AS totalJuros, pe.nome AS nomeParceiro
        //     FROM
        //         troca_adiamentos t
        //             INNER JOIN
        //         parcelas p ON p.id = t.parcela_id
        //             INNER JOIN
        //         parceiros pa ON pa.id = p.parceiro_id
        //             INNER JOIN
        //         pessoas pe ON pe.id = pa.pessoa_id
        //     WHERE
        //         t.pago IS NULL
        //         AND t.deleted_at IS NULL
        //         AND pa.deleted_at IS NULL
        //     GROUP BY pa.id
        // ');

        // $mes = date('m');
        // $Op = DB::select('SELECT
        //         MONTH(p.data_parcela) AS mes,
        //         SUM(p.valor_parcela) AS total_devedor,
        //         SUM((SELECT SUM(valor) FROM pagamentos_representantes pr WHERE pr.parcela_id = p.id  AND pr.deleted_at is null)) AS total_pago
        //     FROM
        //         parcelas p
        //             LEFT JOIN
        //         vendas v ON v.id = p.venda_id
        //             INNER JOIN
        //         clientes c ON c.id = v.cliente_id
        //             INNER JOIN
        //         pessoas pe ON pe.id = c.pessoa_id
        //     WHERE
        //         p.forma_pagamento IN (?, ?, ?)
        //             AND p.status LIKE ?
        //             AND year(p.data_parcela) = year(CURDATE())
        //             AND v.deleted_at is null
        //         AND p.deleted_at is null
        //             GROUP BY MONTH(p.data_parcela)
        //             ORDER BY 1',
        //     ['Cheque' , 'Pix', 'Transferência Bancária', 'Aguardando Pagamento']
        // );

        // $chequesAguardandoEnvio = DB::select('SELECT
        //     SUM(VALOR_PARCELA) AS valor, MONTH(data_parcela) AS mes
        //     FROM parcelas where forma_pagamento like ?
        //     AND status like ?
        //     AND deleted_at is null
        //     GROUP BY MONTH(data_parcela)
        //     ORDER BY MONTH(data_parcela)',
        //     ['Cheque', 'Aguardando Envio']
        // );

        // $chequesAguardandoEnvioTotal = 0;
        // $totalDevedorGeral = 0;
        // $totalPagoGeral = 0;
        // $opsVencidasDevedoras = 0;
        // $opsPagas = 0;

        $estoque = Estoque::all();

        $hoje = date('d/m/Y');
        // $totalCarteiraMaisSeisMeses = 0;

        $arquivo_json = Storage::disk('public')
            ->get('comissao_representantes/conta_corrente_cheques.json');

        $infoRepresentante = json_decode($arquivo_json, true);
        $saldo_total = $infoRepresentante['Default']['Saldo'];
        $data_inicio = $infoRepresentante['Default']['Data'];
        $saldoContaCorrenteChsDevolvidos = [];
        $totalGeralDeTodosChequesDevolvidos = 0;

        foreach ($representantes as $key => $representante) {
            // dd($representantes);
            if (($representante->conta_corrente_sum_peso_agregado != 0 || $representante->conta_corrente_sum_fator_agregado != 0)) {
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
                
                $devolvidosComParceiros = Parcela::withSum('pagamentos_representantes', 'valor')->devolvidosComParceiros($representante->id)->get();
                $devolvidosNoEscritorio = Parcela::withSum('pagamentos_representantes', 'valor')->devolvidosNoEscritorio($representante->id)->get();

                $totalDevolvidoParceiros = $devolvidosComParceiros->sum('pagamentos_representantes_sum_valor') - $devolvidosComParceiros->sum('valor_parcela');
                $totalDevolvidoEscritorio =  $devolvidosNoEscritorio->sum('pagamentos_representantes_sum_valor') - $devolvidosNoEscritorio->sum('valor_parcela');

                $contaCorrente = ($saldo_total - $entregas->sum('valor_parcela') ) + ($entregas->sum('pagamentos_representantes_sum_valor') + $pagamentosRepresentantes->sum('valor'));
                
                $totalGeralDevolvidos =  $totalDevolvidoParceiros + $totalDevolvidoEscritorio + $contaCorrente;
                
                $chequesEmAberto[$representante->id] = [
                    'parceiros' => $totalDevolvidoParceiros,
                    'escritorio' => $totalDevolvidoEscritorio,
                    'contaCorrente' => $contaCorrente,
                    'totalGeral' => $totalGeralDevolvidos
                ]; 
                
                $totalGeralDeTodosChequesDevolvidos += $totalGeralDevolvidos;
            }
        }
        // dd($chequesEmAberto[1]);
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadView(
            'fornecedor.pdf.diario3',
            compact(
                'estoque',
                'fornecedores',
                'carteira',
                'representantes',
                'devolvidos',
                'hoje',
                'totalCarteira',
                'estoque',
                'chequesEmAberto',
                'totalGeralDevolvidos',
                'totalGeralDeTodosChequesDevolvidos'
            )
        );

        return $pdf->stream();
    }

    // public function pdf_diario()
    // {
    //     $fornecedores = Fornecedor::saldoFornecedores();

    //     $carteira = DB::select('SELECT
    //             sum(valor_parcela) as total_mes,
    //             MONTH(IF(par.status = ?,
    //                 (SELECT nova_data FROM adiamentos WHERE parcela_id = par.id ORDER BY id desc LIMIT 1),
    //                 par.data_parcela
    //             )) as month,
    //             YEAR(IF(par.status = ?,
    //                 (SELECT nova_data FROM adiamentos WHERE parcela_id = par.id ORDER BY id desc LIMIT 1),
    //                 par.data_parcela
    //             )) as year
    //         FROM
    //             parcelas par
    //         WHERE
    //             par.status in (?,?)
    //                 AND par.deleted_at IS NULL
    //                 AND parceiro_id IS NULL
    //                 AND forma_pagamento LIKE ?
    //                 GROUP BY YEAR( IF(par.status = ?,
    //                 (SELECT nova_data FROM adiamentos WHERE parcela_id = par.id ORDER BY id desc LIMIT 1),
    //                 par.data_parcela
    //             ) ), MONTH( IF(par.status = ?,
    //                 (SELECT nova_data FROM adiamentos WHERE parcela_id = par.id ORDER BY id desc LIMIT 1),
    //                 par.data_parcela
    //             ) )
    //         ORDER BY 3,2',
    //         ['Adiado', 'Adiado', 'Adiado', 'Aguardando', 'Cheque', 'Adiado', 'Adiado']
    //     );

    //     $totalCarteira =  DB::select('SELECT
    //             sum(valor_parcela) as totalCarteira
    //         FROM
    //             parcelas par
    //         WHERE
    //             par.status in (?,?)
    //                 AND par.deleted_at IS NULL
    //                 AND parceiro_id IS NULL
    //                 AND forma_pagamento LIKE ?',
    //         ['Adiado', 'Aguardando', 'Cheque']
    //     );

    //     $devolvidos = Parcela::where('status', 'Devolvido')->get();

    //     $representantes = Representante::with('pessoa')
    //         ->withSum('conta_corrente', 'peso_agregado')
    //         ->withSum('conta_corrente', 'fator_agregado')
    //         ->orderBy('atacado')
    //         ->get();

    //     $adiamentos = Parcela::withSum('adiamentos', 'juros_totais')
    //         ->whereHas('adiamentos')
    //         ->get();


    //     $parceiros = DB::select('SELECT
    //             SUM(juros_totais) AS totalJuros, pe.nome AS nomeParceiro
    //         FROM
    //             troca_adiamentos t
    //                 INNER JOIN
    //             parcelas p ON p.id = t.parcela_id
    //                 INNER JOIN
    //             parceiros pa ON pa.id = p.parceiro_id
    //                 INNER JOIN
    //             pessoas pe ON pe.id = pa.pessoa_id
    //         WHERE
    //             t.pago IS NULL
    //             AND t.deleted_at IS NULL
    //             AND pa.deleted_at IS NULL
    //         GROUP BY pa.id
    //     ');

    //     $mes = date('m');
    //     $Op = DB::select('SELECT
    //             MONTH(p.data_parcela) AS mes,
    //             SUM(p.valor_parcela) AS total_devedor,
    //             SUM((SELECT SUM(valor) FROM pagamentos_representantes pr WHERE pr.parcela_id = p.id  AND pr.deleted_at is null)) AS total_pago
    //         FROM
    //             parcelas p
    //                 LEFT JOIN
    //             vendas v ON v.id = p.venda_id
    //                 INNER JOIN
    //             clientes c ON c.id = v.cliente_id
    //                 INNER JOIN
    //             pessoas pe ON pe.id = c.pessoa_id
    //         WHERE
    //             p.forma_pagamento IN (?, ?, ?)
    //                 AND p.status LIKE ?
    //                 AND year(p.data_parcela) = year(CURDATE())
    //                 AND v.deleted_at is null
    //             AND p.deleted_at is null
    //                 GROUP BY MONTH(p.data_parcela)
    //                 ORDER BY 1',
    //         ['Cheque' , 'Pix', 'Transferência Bancária', 'Aguardando Pagamento']
    //     );

    //     $chequesAguardandoEnvio = DB::select('SELECT
    //         SUM(VALOR_PARCELA) AS valor, MONTH(data_parcela) AS mes
    //         FROM parcelas where forma_pagamento like ?
    //         AND status like ?
    //         AND deleted_at is null
    //         GROUP BY MONTH(data_parcela)
    //         ORDER BY MONTH(data_parcela)',
    //         ['Cheque', 'Aguardando Envio']
    //     );
    //     $chequesAguardandoEnvioTotal = 0;
    //     $totalDevedorGeral = 0;
    //     $totalPagoGeral = 0;
    //     $opsVencidasDevedoras = 0;
    //     $opsPagas = 0;

    //     $estoque = Estoque::all();

    //     // $pagamentoMed = DB::select('SELECT
    //     //     (SELECT IFNULL(sum(peso), 0) FROM conta_corrente WHERE balanco like ? and fornecedor_id = f.id AND deleted_at is null)
    //     //     -
    //     //     ((SELECT IFNULL(sum(peso)/2, 0) FROM conta_corrente WHERE balanco like ? and fornecedor_id = f.id AND (datediff(curdate(), data) between 30 and 59) AND deleted_at is null ) +
    //     //     (SELECT IFNULL(sum(peso), 0) FROM conta_corrente WHERE balanco like ? and fornecedor_id = f.id AND (datediff(curdate(), data) >= 60) AND deleted_at is null ) ) AS total,
    //     //     (SELECT nome from pessoas WHERE f.pessoa_id = id) as fornecedor,
    //     //     f.id as fornecedor_id
    //     //     FROM fornecedores f',
    //     //     [
    //     //         'Crédito', 'Débito', 'Débito'
    //     //     ]
    //     // );
    //     $hoje = date('d/m/Y');
    //     $totalCarteiraMaisSeisMeses = 0;

    //     $pdf = App::make('dompdf.wrapper');
    //     $pdf->loadView(
    //         'fornecedor.pdf.diario',
    //         compact(
    //             'fornecedores',
    //             'carteira',
    //             'representantes',
    //             'devolvidos',
    //             'adiamentos',
    //             'hoje',
    //             'parceiros',
    //             'totalCarteira',
    //             'Op',
    //             'mes',
    //             'opsVencidasDevedoras',
    //             'opsPagas',
    //             'totalDevedorGeral',
    //             'totalPagoGeral',
    //             'chequesAguardandoEnvio',
    //             'chequesAguardandoEnvioTotal',
    //             'estoque',
    //             'totalCarteiraMaisSeisMeses'
    //         )
    //     );

    //     return $pdf->stream();
    // }

    public function pdf_mov_diario($data)
    {
        $cc_fornecedor = ContaCorrente::query()
            ->with('fornecedor.pessoa:id,nome')
            ->whereDate('created_at', $data)
            ->get();
        
        $cc_representante = ContaCorrenteRepresentante::query()
            ->with('representante.pessoa:id,nome')
            ->whereDate('created_at', $data)
            ->get();

        $cheques = Parcela::query()
            ->with('representante.pessoa:id,nome', 'parceiro.pessoa:id,nome', 'adiamentos')
            ->whereHas('movimentacoes', function (Builder $query) use ($data) {
                $query->where(DB::raw('DATE(data)'), $data)
                    ->whereNotIn('status', ['Pago parceiro', 'Pago representante']);
            })
            ->get()
            ->groupBy('status');
            
        // $cheques = MovimentacaoCheque::query()
        //     ->with('parcela')
        //     ->whereDate('data', '=', $data)
        //     ->where('status', 'LIKE', 'Adiado')
        //     ->get();
            // dd($cheques);
        // dd($cheques->has('Resgatado'));

        $despesas = Despesa::query()
            ->whereDate('data_vencimento', '=', $data)
            ->with('local')
            ->get();

        $recebimentos = PagamentosRepresentantes::query()
            ->whereDate('created_at', '=', $data)
            ->get();

        $titulo = 'Movimentação diária '.  date("d/m/Y", strtotime($data));

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'fornecedor.pdf.mov_diario',
            compact(
                'titulo',
                'cc_fornecedor',
                'cc_representante',
                'cheques',
                'despesas',
                'recebimentos'
            )
        );

        return $pdf->stream();
    }

    public function pdf_movimentacao($dataInicio, $dataFim)
    {
        $cc_fornecedor = Fornecedor::query()
            ->with('representante.pessoa:id,nome', 'parceiro.pessoa:id,nome')
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->get();

        $cc_representante = Representante::query()
            ->with('representante.pessoa:id,nome', 'parceiro.pessoa:id,nome')
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->get();

        $cheques = Parcela::query()
            ->with('representante.pessoa:id,nome', 'parceiro.pessoa:id,nome', 'adiamentos')
            ->whereHas('movimentacoes', function (Builder $query) use ($dataInicio, $dataFim) {
                $query->whereBetween(DB::raw('DATE(data)'), [$dataInicio, $dataFim])
                    ->whereNotIn('status', ['Pago parceiro', 'Pago representante']);
            })
            ->get()
            ->groupBy('status');
            
        // $cheques = MovimentacaoCheque::query()
        //     ->with('parcela')
        //     ->whereDate('data', '=', $data)
        //     ->where('status', 'LIKE', 'Adiado')
        //     ->get();
            // dd($cheques);
        // dd($cheques->has('Resgatado'));

        $despesas = Despesa::query()
            ->whereBetween('data_vencimento', [$dataInicio, $dataFim])
            ->with('local')
            ->get();

        $recebimentos = PagamentosRepresentantes::query()
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->get();

        $titulo = 'Movimentação ' . date("d/m/Y", strtotime($dataInicio)) .' até '. date("d/m/Y", strtotime($dataFim));

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'fornecedor.pdf.mov_diario',
            compact(
                'titulo',
                'cc_fornecedor',
                'cc_representante',
                'cheques',
                'despesas',
                'recebimentos'
            )
        );
        
        return $pdf->stream();
    }

    public function pdf_relatorio_mensal($mes, $ano)
    {
        $locais = Local::all();
        $despesa_mensal = Despesa::whereMonth('data_vencimento', $mes)
            ->whereYear('data_vencimento', $ano)
            ->orderBy('local_id')
            ->get();

        $fornecedores = Fornecedor::with('pessoa')->get();
        $cc_fornecedores = ContaCorrente::whereMonth('data', $mes)
            ->whereYear('data', $ano)
            ->orderBy('fornecedor_id')
            ->get();

        $representantes = Representante::with('pessoa')->get();
        $cc_representantes = ContaCorrenteRepresentante::whereMonth('data', $mes)
            ->whereYear('data', $ano)
            ->orderBy('representante_id')
            ->get();

        $depositosConta = Parcela::whereHas('movimentacoes', function (Builder $query) use ($ano, $mes) {
                $query->where('status', 'like', 'Depositado')
                ->whereMonth('data', $mes)
                ->whereYear('data', $ano);
            })
            ->sum('valor_parcela');

        $adiamentos = Adiamento::select(DB::raw('SUM(juros_totais) as juros, COUNT(id) as total'))
            ->whereMonth('created_at', $mes)
            ->whereYear('created_at', $ano)
            ->first();

        $ops = PagamentosRepresentantes::whereMonth('data', $mes)
            ->whereYear('data', $ano)
            ->sum('valor');

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadView(
            'fornecedor.pdf.pdf_relatorio_mensal',
            compact(
                'mes',
                'ano',
                'locais',
                'despesa_mensal',
                'fornecedores',
                'cc_fornecedores',
                'representantes',
                'cc_representantes',
                'adiamentos',
                'depositosConta',
                'ops'
            )
        );

        return $pdf->stream();
    }
}
