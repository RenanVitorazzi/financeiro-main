<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestDespesasFixasStore;
use App\Models\DespesaFixa;
use App\Models\Local;
use Illuminate\Http\Request;

class DespesasFixasController extends Controller
{
    public function index(Request $request)
    {
        $despesas_fixas = DespesaFixa::with('local')->get();

        $message = $request
            ->session()
            ->get('message');

        return view('despesas_fixas.index', compact('despesas_fixas', 'message'));
    }

    public function create()
    {
        $locais = Local::all();
        return view('despesas_fixas.create', compact('locais'));
    }

    public function store(RequestDespesasFixasStore $request)
    {
        DespesaFixa::create($request->validated());
    
        $request
            ->session()
            ->flash(
                'message',
                'Despesa fixa criada com sucesso!'
            );

        return redirect()->route('despesas_fixas.index');
    }

    public function edit($id)
    {
        $despesa_fixa = DespesaFixa::findOrFail($id);
        $locais = Local::all();
        
        return view('despesas_fixas.edit', compact('despesa_fixa', 'locais'));
    }

    public function update (RequestDespesasFixasStore $request, $id)
    {
        DespesaFixa::find($id)->update($request->validated());
        
        $request
            ->session()
            ->flash(
                'message',
                'Despesa fixa atualizada com sucesso!'
            );

        return redirect()->route('despesas_fixas.index');
    }
}