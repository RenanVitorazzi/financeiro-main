<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestContaStore;
use App\Models\Conta;
use Illuminate\Http\Request;

class ContaController extends Controller
{
    public function index(Request $request)
    {
        $contas = Conta::all();

        $message = $request
            ->session()
            ->get('message');

        return view('contas.index', compact('contas', 'message'));
    }

    public function create()
    {
        return view('contas.create');
    }

    public function store(RequestContaStore $request)
    {
        Conta::create($request->validated());
    
        $request
            ->session()
            ->flash(
                'message',
                'Conta criada com sucesso!'
            );

        return redirect()->route('contas.index');
    }

    public function edit(Conta $conta)
    {
        return view('contas.edit', compact('conta'));
    }

    public function update (RequestContaStore $request, Conta $conta)
    {
        Conta::find($conta->id)->update($request->validated());
        
        $request
            ->session()
            ->flash(
                'message',
                'Conta atualizada com sucesso!'
            );

        return redirect()->route('contas.index');
    }

    public function procurarContas() {
        return Conta::all()->toJson();
    }
}
