<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdiamentoFormRequest;
use App\Http\Requests\EditTrocaChequeRequest;
use App\Http\Requests\TrocaChequesRequest;
use App\Models\Feriados;
use App\Models\Parcela;
use App\Models\Troca;
use App\Models\TrocaAdiamento;
use App\Models\TrocaParcela;
use App\Models\Parceiro;
use App\Models\Representante;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class TrocaChequeController extends Controller
{
    public function __construct()
    {
        $this->feriados = Feriados::all();
    }

    public function index()
    {
        $trocas = Troca::orderBy('data_troca', 'Desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('troca_cheque.index', compact('trocas') );
    }

    public function create()
    {
        $parceiros = Parceiro::with('pessoa')
            ->get();

        $cheques = DB::select('SELECT
            par.id,
            par.nome_cheque,
            par.numero_cheque,
            par.valor_parcela,
            par.observacao,
            par.status,
            par.venda_id,
            (SELECT UPPER(p.nome) FROM pessoas p WHERE p.id = r.pessoa_id) AS nome_representante,
            IF(par.status = ?,
                (SELECT nova_data FROM adiamentos WHERE parcela_id = par.id ORDER BY id desc LIMIT 1),
                par.data_parcela
            ) as data_parcela,
            p.nome
        FROM
            parcelas par
                LEFT JOIN
            representantes r ON r.id = par.representante_id
            LEFT JOIN
            pessoas p ON p.id = r.pessoa_id
        WHERE
            (par.status LIKE ? or par.status LIKE ?)
                AND par.deleted_at IS NULL
                AND par.forma_pagamento like ?
                AND r.deleted_at IS NULL
                AND parceiro_id IS NULL
        ORDER BY data_parcela ASC, valor_parcela ASC', ['Adiado','Adiado', 'Aguardando', 'Cheque']);
        // dd($cheques);
        return view('troca_cheque.create', compact('cheques', 'parceiros') );
    }

    public function store(TrocaChequesRequest $request)
    {
        $porcetagem_padrao = $request->taxa_juros;
        $taxa = $porcetagem_padrao / 100;

        $cheques = Parcela::find($request->cheque_id);
        $dataInicio = new DateTime($request->data_troca);

        $troca = Troca::create([
            'data_troca' => $request->data_troca,
            'parceiro_id' => $request->parceiro_id,
            'titulo' => $request->titulo,
            'observacao' => $request->observacao,
            'taxa_juros' => $request->taxa_juros,
        ]);

        $totalJuros = 0;
        $totalLiquido = 0;

        foreach ($cheques as $cheque) {
            //* Se o cheque foi adiado, seleciona a nova data
            $dataDoCheque =  $cheque->status == 'Adiado' ? $cheque->adiamentos->nova_data : $cheque->data_parcela;

            $dataFim = new DateTime($dataDoCheque);

            if ($request->parceiro_id == 3 || $request->parceiro_id == 4) {
                //* Confere se é sábado ou domingo ou se o próximo dia útil não é feriado
                while (in_array($dataFim->format('w'), [0, 6]) || !$this->feriados->where('data_feriado', $dataFim->format('Y-m-d'))->isEmpty()) {
                    $dataFim->modify('+1 weekday');
                }
            }

            $diferencaDias = $dataInicio->diff($dataFim)->days;

            $juros = ( ($cheque->valor_parcela * $taxa) / 30 ) * $diferencaDias;
            $valorLiquido = $cheque->valor_parcela - $juros;

            $totalJuros += $juros;
            $totalLiquido += $valorLiquido;

            TrocaParcela::create([
                'parcela_id' => $cheque->id,
                'troca_id' => $troca->id,
                'dias' => $diferencaDias,
                'valor_liquido' => $valorLiquido,
                'valor_juros' => $juros
            ]);

            $cheque->update([
                'parceiro_id' => $request->parceiro_id
            ]);
        }

        $totalBruto = $totalLiquido + $totalJuros;

        $troca->update([
            'valor_liquido' => $totalLiquido,
            'valor_bruto' => $totalBruto,
            'valor_juros' => $totalJuros,
        ]);

        return redirect()->route('troca_cheques.index');
    }

    public function edit($id)
    {
        $troca = Troca::findOrFail($id);
        $parceiros = Parceiro::with('pessoa')->get();

        return view('troca_cheque.edit', compact('troca', 'parceiros'));
    }

    public function update(EditTrocaChequeRequest $request, $id)
    {
        $troca = Troca::findOrFail($id);

        if  (($troca->data_troca !== $request->data_troca) || $troca->taxa_juros != $request->taxa_juros) {
            $cheques = TrocaParcela::with('parcelas')
                ->where('troca_id', $troca->id)
                ->orderBy('dias')
            ->get();

            $totalJuros = 0;
            $totalLiquido = 0;
            $dataInicio = new DateTime($request->data_troca);
            $taxa = $request->taxa_juros / 100;

            foreach ($cheques as $cheque) {
                //* Se o cheque foi adiado, seleciona a nova data
                $dataDoCheque =  $cheque->parcelas->status == 'Adiado' ? $cheque->parcelas->adiamentos->nova_data : $cheque->parcelas->data_parcela;

                $dataFim = new DateTime($dataDoCheque);

                if ($request->parceiro_id == 3 || $request->parceiro_id == 4) {
                    //* Confere se é sábado ou domingo ou se o próximo dia útil não é feriado
                    while (in_array($dataFim->format('w'), [0, 6]) || !$this->feriados->where('data_feriado', $dataFim->format('Y-m-d'))->isEmpty()) {
                        $dataFim->modify('+1 weekday');
                    }
                }

                $diferencaDias = $dataInicio->diff($dataFim)->days;

                $juros = ( ($cheque->parcelas->valor_parcela * $taxa) / 30)  * $diferencaDias;
                $valorLiquido = $cheque->parcelas->valor_parcela - $juros;

                $totalJuros += $juros;
                $totalLiquido += $valorLiquido;

                $cheque->update([
                    'dias' => $diferencaDias,
                    'valor_liquido' => $valorLiquido,
                    'valor_juros' => $juros
                ]);

                $cheque->parcelas->update([
                    'parceiro_id' => $request->parceiro_id
                ]);
            }

            $totalBruto = $totalLiquido + $totalJuros;

            $troca->update([
                'valor_liquido' => $totalLiquido,
                'valor_bruto' => $totalBruto,
                'valor_juros' => $totalJuros,
            ]);

        }

        $troca->update($request->all());
        return redirect()->route('troca_cheques.index');
    }

    public function pdf_troca($id)
    {
        $troca = Troca::with('parceiro')->findOrFail($id);
        $prazoMedio = TrocaParcela::where('troca_id', $id)->get()->avg('dias');

        $cheques = DB::select('SELECT
                IF(a.nova_data, MAX(a.nova_data), p.data_parcela) data,
                valor_parcela,
                nome_cheque,
                numero_cheque,
                t.*,
                p.id,
                (SUM(t.dias) / COUNT(t.dias)) AS media_dias 
            FROM
                parcelas p
                    INNER JOIN
                trocas_parcelas t ON t.parcela_id = p.id
                    LEFT JOIN
                adiamentos a ON a.parcela_id = p.id
            WHERE
                troca_id = ?
            GROUP BY p.id
            ORDER BY 1, 2',
            [$id]
        );

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('troca_cheque.pdf.troca_cheque', compact('troca', 'cheques', 'prazoMedio') );

        return $pdf->stream();
    }

    public function show($id)
    {
        $troca = Troca::with(['cheques' => function ($query) {
                $query->orderBy('dias');
            }, 'parceiro'])
        ->findOrFail($id);

        return view('troca_cheque.show', compact('troca') );
    }

    public function adiar_cheque(AdiamentoFormRequest $request)
    {
        $porcentagem = $request->taxa / 100;
        $cheque = Parcela::findOrFail($request->cheque_id);

        $datetime1 = date_create($request->data);
        $datetime2 = date_create($cheque->data_parcela);
        $interval = date_diff($datetime1, $datetime2);

        $dias = $interval->format('%a');

        $trocaParcela = TrocaParcela::findOrFail($request->troca_parcela_id);

        $adicionalJuros = ( ( ($cheque->valor_parcela * $porcentagem) / 30 ) * $dias);
        $jurosTotais = $adicionalJuros + $trocaParcela->valor_juros;

        $cheque->update([
            'status' => 'Adiado'
        ]);

        $adiamento = TrocaAdiamento::create([
            'data' => $request->data,
            'dias_totais' => $dias,
            'adicional_juros' => $adicionalJuros,
            'juros_totais' => $jurosTotais,
            'taxa' => $request->taxa,
            'observacao' => $request->observacao,
            'data' => $request->data,
            'troca_parcela_id' => $request->troca_parcela_id,
            'parcela_id' => $request->cheque_id,
        ]);

        return json_encode([
            'title' => 'Sucesso',
            'icon' => 'success',
            'text' => 'Salvo com sucesso',
            'adiamento' => $adiamento,
        ]);
    }

    public function resgatar_cheque(Request $request, $id)
    {
        $cheque = Parcela::findOrFail($id);
        $cheque->update([
            'status' => 'Resgatado'
        ]);

        return redirect()->route('troca_cheques.show', $request->troca_id);
    }

    public function troca_automatizada(Request $request) 
    {

        //! ALTERAR TAXA POR PARCEIRO
        // $data_inicio = '2023-07-01';
        // $data_fim = '2023-12-30';
        // $valorLiquidoTroca = 300000;
        // $totalMeses = 6;

        // $valorLiquidoPorMes = $valorLiquidoTroca / $totalMeses;

        // for($mes = $mes_inico; $i >= $totalMeses; $i++) {
        //     $cheques = selecionarAleatoriamenteCheques($i);
        // }

        // $carteira_liquida = DB::select('SELECT
        //         nome_cheque,
        //         data_parcela,
        //         valor_parcela,
        //         DATEDIFF(data_parcela,CURDATE()) AS dias,
        //         (valor_parcela * 0.02 / 30) * DATEDIFF(data_parcela, CURDATE()) as juros,
        //         (valor_parcela - (valor_parcela * 0.02 / 30) * DATEDIFF(data_parcela,CURDATE()) ) AS valor_liquido,
        //         (((valor_parcela * 0.02 / 30) * DATEDIFF(data_parcela, CURDATE())) / valor_parcela ) * 100 AS PORCENTAGEM_JUROS
        //     FROM
        //         parcelas p
        //     WHERE
        //     parceiro_id is null
        //             AND p.deleted_at IS NULL
        //         AND p.status like ?
        //         AND p.data_parcela between ? AND ?
        //     ORDER BY data_parcela, valor_parcela, nome_cheque', 
        //     ['Aguardando', $data_inicio, $data_fim]
        // );

        //TODO Validação das datas
            //? PRAZO MÉDIO -  REQUIRED
                //deixar default no input valor mais comum (105.00)
            //? % JUROS - REQUIRED 
                //deixar default no input valor mais comum (7%)
            //? DATA DE INÍCIO - REQUIRED 
            //? DATA DE FIM - REQUIRED 
            //? VALOR TOTAL LÍQUIDO - REQUIRED 
            //? TAXA DA TROCA OU PARCEIRO_ID - REQUIRED 
        
        //TODO SQL TODA CARTEIRA QUE ESTÁ ENTRE OS VALORES
            //?  Fazer date_diff da data de inicio e data de termino 
                // ver quantos meses serão dados
                // dividir valor total da troca por mês
                // fazer o sql de OVER com order by random até o valor necessário
                // passar por todos os meses
                // fazer o mesmo sql para gerar taxa de juros, prazo médio e total líquido  
                // fazer função para acertar valores
                // Cheques do lúcio não serão passados ou jogar como *low priority*


    }
}
