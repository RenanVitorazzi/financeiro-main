<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Pessoa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClienteSeeder extends Seeder
{

    public function confereSePessoaExiste($info) 
    {
        return Pessoa::where('nome', 'like', $info['nome'])->get()->isEmpty();
    }

    public function criarCliente($info) 
    {
        if (!$this->confereSePessoaExiste($info)) {
            return;
        }

        DB::transaction(function() use ($info) {
            
            $cpf = NULL;
            $cnpj = NULL;
            $tipoCadastro = NULL;

            if ( isset( $info['cpf'] ) ) { 
                
                $cpf = $info['cpf'];
                $tipoCadastro = 'Pessoa Física';
            
            } elseif ( isset( $info['cnpj'] ) ) {
            
                $cnpj = $info['cnpj'];
                $tipoCadastro = 'Pessoa Jurídica';
            
            }

            $pessoa = Pessoa::create([
                'nome' => $info['nome'],
                'cnpj' => $cnpj,
                'cpf'=> $cpf,
                'tipoCadastro' => $tipoCadastro,
                'telefone' => $info['telefone'] ?? NULL,
                'celular' => $info['celular'] ?? NULL,
                'cep' => $info['cep'] ?? NULL,
                'logradouro' => $info['logradouro'] ?? NULL,
                'numero' => $info['numero'] ?? NULL,
                'bairro' => $info['bairro'] ?? NULL,
                'complemento' => $info['complemento'] ?? NULL,
                'municipio' => $info['municipio'] ?? NULL,
                'estado' => $info['estado'] ?? NULL,
            ]);

            Cliente::create([
                'pessoa_id' => $pessoa->id,
                'representante_id' => 1
            ]);
        });
    }

    public function run()
    {
        $json = Storage::disk('public')->get('json/clientes/.json');
        $json = json_decode($json, true);

        foreach ($json['Jairo'] as $cheque => $info) {
            $this->criarCliente($info);              
        }

    }
}
