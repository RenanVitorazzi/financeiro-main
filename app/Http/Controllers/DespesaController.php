<?php

namespace App\Http\Controllers;

use App\Http\Requests\NovaDespesaRequest;
use App\Models\Despesa as ModelsDespesa;
use App\Models\DespesaFixa;
use App\Models\Local;
use Despesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DespesaImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DespesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idFixasPagas = ModelsDespesa::query()
            ->whereMonth('data_vencimento', DB::raw('MONTH(CURDATE())'))
            ->whereYear('data_vencimento', DB::raw('YEAR(CURDATE())'))
            ->whereNotNull('fixas_id')
            ->orderBy('local_id')
            ->pluck('fixas_id');

        $fixasNaoPagas = DespesaFixa::with('local')
            ->whereNotIn('id', $idFixasPagas)   
            ->where(function ($query) {
                $query->whereNull('data_quitacao')
                    ->orWhere('data_quitacao', '>=', date('Y-m-d'));
            })
            // ->orderBy('local_id')
            ->orderBy('dia_vencimento')
            ->get();
            
        $despesas = ModelsDespesa::with('local')
            ->where(DB::raw('MONTH(data_vencimento)'), DB::raw('MONTH(CURDATE())'))
            ->whereYear('data_vencimento', DB::raw('YEAR(CURDATE())'))
            ->orderBy('local_id')
            ->get();

        $mes = date('m');

        return view('despesa.index', compact('despesas', 'fixasNaoPagas', 'mes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $locais = Local::all();
        $fixas = DespesaFixa::with('local')
            ->orderBy('local_id')
            ->get()
            ->toJson();

        $data = NULL;
        $descricao = NULL;
        $valor = NULL;
        $conta = NULL;

        return view('despesa.create', compact('locais', 'fixas', 'data', 'descricao', 'valor', 'conta' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NovaDespesaRequest $request)
    {
        ModelsDespesa::firstOrCreate($request->validated());

        $request
            ->session()
            ->flash(
                'message',
                'Conta lançada com sucesso!'
            );

        return redirect()->route('despesas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $locais = Local::all();
        $despesa = ModelsDespesa::with('local')->findOrFail($id);

        return view('despesa.edit', compact('locais', 'despesa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NovaDespesaRequest $request, $id)
    {
        $despesa = ModelsDespesa::findOrFail($id);
        $despesa->update($request->validated());

        $request
            ->session()
            ->flash(
                'message',
                'Conta lançada com sucesso!'
            );

        return redirect()->route('despesas.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        ModelsDespesa::destroy($id);

        $request
            ->session()
            ->flash(
                'message',
                'Registro deletado com sucesso!'
            );

        return redirect()->route('despesas.index');
    }

    public function importDespesas (Request $request)
    {
        $import = new DespesaImport;
        Excel::import($import, $request->file('importacao'));

        return view('despesa.importacao', compact('import'));
    }

    public function criarDespesaImportacao($data, $descricao, $valor, $conta)
    {
        $locais = Local::all();
        $fixas = DespesaFixa::with('local')
            ->orderBy('local_id')
            ->get()
            ->toJson();

        return view('despesa.create', compact('locais', 'fixas', 'data', 'descricao', 'valor', 'conta'));
    }

    public function pdf_despesa_mensal ($mes)
    {
        $despesas = ModelsDespesa::where(DB::raw('MONTH(data_vencimento)'), DB::raw($mes))
            ->where(DB::raw('YEAR(data_vencimento)'), DB::raw('YEAR(CURDATE())'))
            ->orderBy('local_id')
            ->orderBy('data_vencimento')
            ->get();

        $local = Local::all();
        // dd($despesas->where('local_id', 1)->sum('valor'));
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('despesa.pdf.pdf_despesa_mensal', compact('despesas', 'mes', 'local') );

        return $pdf->stream();
    }
}
