<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pessoa;
use App\Models\Representante;
use App\Http\Requests\RequestFormPessoa;
use App\Models\Consignado;
use App\Models\Parcela;
use App\Models\Venda;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::with(['pessoa', 'representante'])->get();
        $message = $request->session()->get('message');

        return view('cliente.index', compact('clientes', 'message'));
    }

    public function create()
    {
        $representantes = Representante::with('pessoa')->get();

        return view('cliente.create', compact('representantes'));
    }

    public function store(RequestFormPessoa $request)
    {
        $pessoa = Pessoa::create($request->validated());

        Cliente::create([
            'pessoa_id' => $pessoa->id,
            'representante_id' => $request->representante
        ]);

        $request
            ->session()
            ->flash(
                'message',
                'Cliente cadastrado com sucesso!'
            );

        return redirect()->route('clientes.index');
    }

    public function show(Cliente $cliente) {

        $vendas = Venda::with(['parcela'])
            ->where('cliente_id', $cliente->id)
            ->get();

        $totalPrazo = 0;

        return view('cliente.show', compact('cliente', 'vendas', 'totalPrazo'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $representantes = Representante::all();

        return view('cliente.edit', compact('cliente', 'representantes'));
    }

    public function update(RequestFormPessoa $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $pessoa = Pessoa::findOrFail($cliente->pessoa_id);

        $pessoa->fill($request->validated())
            ->save();

        $cliente->representante_id = $request->representante_id;
        $cliente->save();

        $request
            ->session()
            ->flash(
                'message',
                'Cliente atualizado com sucesso!'
            );
        return redirect()->route('clientes.index');
    }

    public function destroy(Request $request, $id)
    {
        Cliente::destroy($id);

        $request
        ->session()
        ->flash(
            'message',
            'Cliente excluído com sucesso!'
        );

        return redirect()->route('clientes.index');
    }

    public function procurarCliente(Request $request)
    {
        if (!$request->dado) {
            return Cliente::select(['clientes.*','pessoas.nome'])
                ->join('pessoas', 'pessoas.id', '=', 'clientes.pessoa_id')
                ->where('representante_id', $request->representante_id)
                ->orderBy('pessoas.nome')
                ->get()
                ->toJson();
        } else {
            $clientes = Cliente::query()
                ->with('pessoa')
                ->whereHas('pessoa', function (Builder $query) use ($request) {
                    $query->where('nome', 'like', '%'.$request->dado.'%');
                    $query->orWhere('cpf', 'like', $request->dado);
                })
                ->where('representante_id', $request->representante_id)
                ->get();
        }

        return json_encode([
            'clientes' => $clientes
        ]);
    }

    public function pdf_clientes(Request $request, $representante_id)
    {
        $representante = Representante::findOrFail($representante_id);

        $clientes = DB::select('SELECT
                UPPER(p.nome) as nome,
                UPPER(p.estado) as estado,
                UPPER(p.municipio) as municipio,
                UPPER(p.cep) as cep,
                UPPER(p.bairro) as bairro,
                UPPER(p.logradouro) as logradouro,
                p.telefone2,
                p.celular2,
                p.numero,
                p.telefone,
                p.celular,
                UPPER(p.complemento) as complemento,
                p.lat,
                p.lng
            FROM clientes c
            INNER JOIN pessoas p ON p.id = c.pessoa_id
                WHERE c.representante_id = ?
                AND c.deleted_at is null
            ORDER BY p.estado, p.municipio', [$representante_id]);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('cliente.pdf.pdf_cliente', compact('clientes', 'representante') )->setPaper('a4', 'landscape');

        return $pdf->stream();
    }

    public function pdf_historico_cliente ($cliente_id)
    {
        $cliente = Cliente::findOrFail($cliente_id);
        
        $compras = Venda::with('representante')
            ->where('cliente_id', $cliente_id)
            ->get();

        $parcelas = Parcela::query()
            ->whereIn('venda_id', $compras->pluck('id'))
            ->orderBy('venda_id')
            ->get();

        $consignados = Consignado::query()
            ->whereIn('venda_id', $compras->pluck('id'))
            ->orderBy('venda_id')
            ->get();

        $totalPrazo = 0;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('cliente.pdf.pdf_historico_cliente', 
            compact('cliente', 'compras', 'parcelas', 'consignados', 'totalPrazo') 
        );

        return $pdf->stream();
    }

    public function etiqueta_endereco ($pessoa_id)
    {
        if ($pessoa_id == 0) {
            $pessoa = (object)[
                'nome' => 'Fernanda Zulmira Vitorazzi',
                'logradouro' => 'Rua Anísio Haddad',
                'numero' => '8001',
                'bairro' => 'Georgina Business Park',
                'complemento' => 'Torre Madrid Norte, Sala 306',
                'municipio' => 'São José do Rio Preto',
                'estado' => 'SP',
                'cep' => '15091-751'
            ];
        } else {
            $pessoa = Pessoa::findOrFail($pessoa_id);
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper([0,0,1150,2300], 'landscape');
        $pdf->loadView('cliente.pdf.etiqueta_endereco', compact('pessoa') );

        return $pdf->stream();
    }
}
