<?php

namespace Database\Seeders;

use App\Models\Pessoa;
use App\Models\Cliente;
use App\Models\Parcela;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Storage;

class CarteiraSeeder extends Seeder
{
    public function confereSePessoaExiste($info) 
    {
        return Pessoa::where('nome', 'like', $info['nome_cheque'])->get()->isEmpty();
    }

    public function criarCliente($info) 
    {
        if (!$this->confereSePessoaExiste($info)) {
            return;
        }
        DB::transaction(function() use ($info){
            if (isset($info['CPF'])) { 
                $pessoa = Pessoa::create([
                    'nome' => $info['nome_cheque'],
                    'tipoCadastro' => 'Pessoa Física',
                    'cpf' => $info['CPF'],
                    'telefone' => $info['telefone'] ?? NULL,
                ]);

            } elseif (isset($info['CNPJ'])) {
                $pessoa = Pessoa::create([
                    'nome' => $info['nome_cheque'],
                    'tipoCadastro' => 'Pessoa Jurídica',
                    'cnpj' => $info['CNPJ'],
                    'telefone' => $info['telefone'] ?? NULL,
                ]);
            }

            Cliente::create([
                'pessoa_id' => $pessoa->id,
                'representante_id' => $info['id_representante']
            ]);
        });
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = Storage::disk('public')->get('json/2023-10-06.json');
        $json = json_decode($json, true);

        foreach ($json['Carteira'] as $cheque => $info) {
 
            Parcela::create([
                'nome_cheque' => $info['nome_cheque'],
                'numero_banco' => $info['numero_banco'] ?? NULL,
                'numero_cheque' => $info['numero_cheque'] ?? NULL,
                'valor_parcela' => $info['valor_parcela'],
                'data_parcela' => $info['data_parcela'],
                'representante_id' => $info['id_representante'] ?? NULL,
                'observacao' => $info['observacao'] ?? NULL,
                'forma_pagamento' => 'Cheque'
            ]);
            
            // if ( isset($info['CADASTRAR'])) {
                
            //     $this->criarCliente($info);
              
            // }
        }
    }
}
