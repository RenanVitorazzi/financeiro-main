<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdiamentoFormRequest;
use App\Mail\ProrrogacoesEResgates;
use App\Models\Adiamento;
use App\Models\Feriados;
use App\Models\Parceiro;
use App\Models\MovimentacaoCheque;
use App\Models\Parcela;
use App\Models\Representante;
use App\Models\TrocaAdiamento as ModelsTrocaAdiamento;
use App\Models\TrocaParcela;
use DateTime;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\TrocaAdiamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdiamentosController extends Controller
{
    public function index()
    {
        $parceiros = Parceiro::with('pessoa:id,nome')->get();
        $representantes = Representante::with('pessoa:id,nome')->get();
        $prorrogacoes = Adiamento::with('parcelas')
            ->orderBy('id', 'desc')
            ->take(100)
            ->get();

        return view('adiamento.index', compact('parceiros', 'representantes', 'prorrogacoes'));
    }

    public function store(AdiamentoFormRequest $request)
    {
        // return $request;
        $nova_data = new DateTime($request->nova_data);
        $feriados = Feriados::all();

        // while (in_array($nova_data->format('w'), [0, 6]) || !$feriados->where('data_feriado', $nova_data->format('Y-m-d'))->isEmpty()) {
        //     $nova_data->modify('+1 weekday');
        // }

        $porcentagem = $request->taxa_juros / 100;
        $cheque = Parcela::findOrFail($request->parcela_id);

        $parcela_data = new DateTime($request->parcela_data);
       
        $dias = $parcela_data->diff($nova_data)->days;

        $jurosTotais = ( ( ($cheque->valor_parcela * $porcentagem) / 30 ) * $dias);
        
        $cheque->update([
            'status' => 'Adiado'
        ]);

        //* Deleta o registro antigo e gera um novo
        if(Adiamento::where('parcela_id', $cheque->id)->exists()){
            Adiamento::where('parcela_id', $cheque->id)->delete();
        }

        //!GERA UMA 'VIA' PARA A PESSOA QUE ESTÁ COM O CHEQUE, DESSE JEITO SABEMOS O QUANTO PAGAREMOS
        if ($cheque->parceiro_id) {
            $parceiro = Parceiro::findOrFail($cheque->parceiro_id);
            $troca = TrocaParcela::where('parcela_id', $cheque->id)->firstOrFail();

            $porcentagemParceiro = number_format($parceiro->porcentagem_padrao / 100, 3);
            $jurosTotaisParceiro = ( ( ($cheque->valor_parcela * $porcentagemParceiro) / 30 ) * $dias);
            
            if(ModelsTrocaAdiamento::where('parcela_id', $cheque->id)->exists()){
                ModelsTrocaAdiamento::where('parcela_id', $cheque->id)->delete();
            }

            ModelsTrocaAdiamento::create([
                'data' => $nova_data->format('Y-m-d'),
                'dias_totais' => $dias,
                'juros_totais' => $jurosTotaisParceiro,
                'adicional_juros' => $jurosTotaisParceiro,
                'taxa' => $parceiro->porcentagem_padrao,
                'parcela_id' => $cheque->id,
                'troca_parcela_id' => $troca->id
            ]);
        }
        
        $adiamento = Adiamento::create([
            'nova_data' => $nova_data->format('Y-m-d'),
            'taxa_juros' => $request->taxa_juros,
            'juros_totais' => $jurosTotais,
            'dias_totais' => $dias,
            'observacao' => $request->observacao,
            'parcela_id' => $cheque->id
        ]);

        MovimentacaoCheque::create([
            'parcela_id' => $cheque->id,
            'status' => 'Adiado',
            'adiamento_id' => $adiamento->id
        ]); 

        return json_encode([
            'title' => 'Sucesso',
            'icon' => 'success',
            'text' => 'Cheque prorrogado para dia '.$nova_data->format('d/m/Y'),
            'adiamento' => $adiamento,
        ]);        
    }

    public function adiamento_impresso ($representante_id) {

        $representante = Representante::with('pessoa')->findOrFail($representante_id);

        $adiamentos = Parcela::whereHas('adiamentos')
            ->where('representante_id', $representante_id)
            ->with('adiamentos')
            ->get();

        $total = 0;

        foreach ($adiamentos as $adiamentos->adiamentos => $cheque) {
            $total += $cheque->adiamentos->juros_totais;
        }
        
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('adiamento.pdf.adiamentos_representante', compact('adiamentos', 'representante', 'total') );
        
        return $pdf->stream();
    }

    public function pdf_prorrogacao ($dia) 
    {
        if ($dia == 0) {
            $dia = date('Y-m-d');
        }

        $cheques = Parcela::query()
            ->with('movimentacoes', function($query) use ($dia) {
                $query->whereDate('data', $dia)
                ->whereIn('status', ['Resgatado', 'Adiado']);
            })
            ->whereHas('movimentacoes', function($query) use ($dia) {
                $query->whereDate('data', $dia)
                ->whereIn('status', ['Resgatado', 'Adiado']);
            })
            ->with(['adiamentos' => fn ($query)  => $query->withoutGlobalScopes()])
        ->get();
        // dd($cheques);
        $antigosAdiamentos = TrocaAdiamento::whereIn('parcela_id', $cheques->pluck('id'))
            ->onlyTrashed()
            ->latest()
            ->get()
            ->unique('parcela_id');

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('adiamento.pdf.pdf_prorrogacao', compact('cheques', 'dia', 'antigosAdiamentos') );
        
        return $pdf->stream();    
    }

    public function destroy(Request $request, $id)
    {
        DB::transaction(function () use ($id) {

            $adiamento = Adiamento::with('parcelas')->findOrFail($id);
            $parcela = $adiamento->parcelas;
            $adiamento->delete();

            if ($parcela->parceiro_id) {
                ModelsTrocaAdiamento::where('parcela_id', $parcela->id)->latest()->first()->delete();
            }
            
            $mov = MovimentacaoCheque::where('parcela_id', $parcela->id)->latest('id')->first();
            
            if ($mov->status === 'Adiado') {
                $mov->delete();
            }
          
            $ultimaMov = MovimentacaoCheque::where('parcela_id', $parcela->id)->latest('id')->first();
            $status = ($ultimaMov) ? 'Aguardando' : $ultimaMov->status;
            
            Parcela::where('id', $parcela->id)->update([
                'status' => $status
            ]);
            

            return 'success';
        });
    }

    public function mailProrrogacao($parceiro_id, $data) 
    {
        $cheques = Parcela::query()
            ->where('parceiro_id', $parceiro_id)
            ->with('movimentacoes', function($query) use ($data) {
                $query->whereDate('data', $data)
                ->whereIn('status', ['Resgatado', 'Adiado']);
            })
            ->whereHas('movimentacoes', function($query) use ($data) {
                $query->whereDate('data', $data)
                ->whereIn('status', ['Resgatado', 'Adiado']);
            })
            ->with(['adiamentos' => fn ($query)  => $query->withoutGlobalScopes()])
        ->get();

        if ($cheques->isEmpty()) {
            return '<h1>Nenhuma prorrogação ou resgate realizados nessa data para esse parceiro!</h1>';
        } 

        $antigosAdiamentos = TrocaAdiamento::whereIn('parcela_id', $cheques->pluck('id'))
            ->onlyTrashed()
            ->latest()
            ->get()
            ->unique('parcela_id');

        // dd($parceiro_id);
        $mail = Mail::to('renan.vitorazzi1@gmail.com')->send(new ProrrogacoesEResgates($cheques, $antigosAdiamentos, $parceiro_id, $data));
        // dd($mail);
        return 'sucesso!';
    }
}
