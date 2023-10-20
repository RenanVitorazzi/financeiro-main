<?php

namespace App\Imports;

use App\Models\Conta;
use App\Models\Despesa;
use App\Models\PagamentosParceiros as ModelsPagamentosParceiros;
use App\Models\PagamentosRepresentantes;
use App\Models\Parcela;
use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PagamentosParceiros;

use function PHPUnit\Framework\throwException;

class RelatorioPixImportBradesco implements ToCollection
{
    public array $arrayDados = [];
    public $conta;

    public function collection(Collection $rows)
    {
        if ($rows[0][0] !== 'Relatório PIX') {
            dd('ERRO, RELATÓRIO ERRADO');
        }

        $agencia_banco = $rows[2][1];
        $cc_banco = '%'. $rows[3][1]. '%';
        $conta = Conta::where([
            ['agencia', $agencia_banco],
            ['conta', 'LIKE', $cc_banco]
        ])->firstOrFail();
        
        $this->conta = $conta;
        
        foreach ($rows as $index => $row) {
            
            if ($index < 6) continue;

            $data = DateTime::createFromFormat('d/m/Y', $row[0])->format('Y-m-d');
            $pixId = $row[7];
            $valorCredito = $this->tratarValor($row[5]); 
            $valorDebito = $this->tratarValor($row[6]);

            if ($valorCredito > 0) {
                //? Recebimento -> pagamentos_representantes 
                $pagamentosRepresentantes = PagamentosRepresentantes::query()
                    ->where('valor', $valorCredito)
                    ->whereDate('data', $data)
                    ->get();
                
                $pagamentoRefVendas= Parcela::query()
                    ->where([
                        ['valor_parcela', $valorCredito],
                        ['forma_pagamento', 'Pix'],
                    ])
                    ->whereDate('data_parcela', $data)
                    ->get();

                $info = [
                    'tipo' => 'Crédito',
                    'nome' => $row[1],
                    'data' => $data,
                    'comprovante_id' => $pixId,
                    'valor' => $valorCredito,
                    'pagamentosRepresentantes' => $pagamentosRepresentantes,
                    'despesas' => $pagamentoRefVendas
                ];
                
                array_push($this->arrayDados, $info);

            } else if ($valorDebito > 0) {
                $pagamentosParceiros = ModelsPagamentosParceiros::query()
                    ->where('valor', $valorDebito)
                    ->whereDate('data', $data)
                    ->get();
                
                $despesas = Despesa::where('valor', $valorDebito)
                    // ->whereDate('data_vencimento', $data)
                    ->get();
                
                $info = [
                    'tipo' => 'Débito',
                    'nome' => $row[1],
                    'data' => $data,
                    'comprovante_id' => $pixId,
                    'valor' => $valorDebito,
                    'pagamentosParceiros' => $pagamentosParceiros,
                    'despesas' => $despesas
                ];
                
                array_push($this->arrayDados, $info);
            }
            
        }
        
    }

    public function tratarValor($valor)
    {
        return abs(str_replace(",", ".",str_replace(".", "",$valor)));
    }
}
