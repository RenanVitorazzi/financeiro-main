<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use MovimentacoesCheques;

class Parcela extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    use HasFactory, SoftDeletes;

    public function venda()
    {
        return $this->belongsTo(Venda::class)->with('cliente');
    }

    public function representante()
    {
        return $this->belongsTo(Representante::class)
            ->with('pessoa');
    }

    public function parceiro()
    {
        return $this->belongsTo(Parceiro::class)
            ->with('pessoa')
            ->withDefault('Carteira');
    }

    public function adiamentos()
    {
        return $this->hasOne(Adiamento::class);
    }

    public function entrega()
    {
        return $this->hasOne(EntregaParcela::class);
    }

    public function troca()
    {
        return $this->hasOne(TrocaParcela::class);
    }

    public function scopeCarteira($query)
    {
        return $query->with('adiamentos')
            ->whereIn('status', ['Aguardando', 'Adiado'])
            ->where('forma_pagamento', '=', 'Cheque')
            ->withMax('adiamentos', 'nova_data')
            ->whereNull('parceiro_id')
            ->orderByRaw('IF(adiamentos_max_nova_data, adiamentos_max_nova_data, data_parcela)')
            ->orderBy('valor_parcela')
            ->orderBy('nome_cheque');
    }

    public function scopeAcertos($query, $representante_id) {
        return $query
            ->with('venda')
            ->where([
                ['forma_pagamento', 'LIKE', 'Cheque'], 
                ['status', 'LIKE', 'Aguardando Envio'],
                ['representante_id', $representante_id]
            ])
            ->orWhere([
                ['forma_pagamento', 'NOT LIKE', 'Cheque'], 
                ['status', 'NOT LIKE', 'Pago'],
                ['representante_id', $representante_id]
            ]);
    }
    public function scopeAdiamentosDoDia($query, $dia)
    {
        return $query->with('representante.pessoa:id,nome', 'parceiro.pessoa:id,nome', 'adiamentos')
        ->whereHas('adiamentos', function (Builder $query) use ($dia) {
            $query->whereDate('created_at', '=', $dia);
        });
    }

    public function scopeOps($query)
    {
        return $query->where([
            ['forma_pagamento', 'Transferência Bancária'],
            ['status', 'Aguardando Pagamento']
        ])->whereNull('parceiro_id');
    }

    public function scopeAcharRepresentante($query, $id)
    {
        return $query->where('representante_id', $id);
    }

    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoCheque::class);
    }

    public function pagamentos_representantes()
    {
        return $this->hasMany(PagamentosRepresentantes::class);
    }

    public function pagamentos_parceiros()
    {
        return $this->hasMany(PagamentosParceiros::class);
    }

    public function scopeDevolvidosComParceiros($query, $representante_id)
    {
        return $query->with('movimentacoes', 'parceiro', 'adiamentos')
            ->whereHas('movimentacoes', function ($query) {
                $query->whereIn('status', ['Devolvido', 'Resgatado']);
            })
            ->doesnthave('entrega')
            ->whereNotNull('parceiro_id')
            ->where('representante_id', $representante_id)
            ->where('data_parcela', '>=', '2023-03-17')
            ->orderBy('parceiro_id')
            ->orderBy('data_parcela')
            ->orderBy('valor_parcela');
    }
    
    public function scopeDevolvidosNoEscritorio($query, $representante_id)
    {
        return $query->with('pagamentos_representantes')
        ->where('representante_id', $representante_id)
        ->whereHas('entrega', function ($query) {
            $query->whereNull('entregue_representante');
            $query->whereNotNull('entregue_parceiro');
            $query->whereNull('enviado');
        })
        ->orWhere(function (Builder $query) use ($representante_id) {
            $query->whereNull('parceiro_id')
            ->whereHas('movimentacoes', function ($query) {
                $query->whereIn('status', ['Resgatado', 'Devolvido']);
            })
            ->doesnthave('entrega')
            ->where('representante_id', $representante_id);
        })
        ->orderBy('status')
        ->orderBy('nome_cheque')
        ->orderBy('data_parcela');
    }
    
    public function scopeVencidosCarteira($query)
    {
        return $query->with('adiamentos')
        ->where(function ($query3) {
            $query3->where([
                    ['data_parcela','<=', DB::raw('CURDATE()')],
                    ['status', '=', 'Aguardando']
                ])
                ->orWhereHas('adiamentos', function (Builder $query2) {
                    $query2->where('nova_data', '<=', DB::raw('CURDATE()'));
                });
        })
        ->where([
            ['parceiro_id', NULL],
            ['forma_pagamento', 'Cheque']
        ])
        ->whereIn('status', ['Aguardando', 'Adiado'])
        ->orderBy('data_parcela')
        ->orderBy('valor_parcela')
        ->orderBy('nome_cheque');
    }

    // public function cliente()
    // {
    //     return $this->hasOneThrough(Cliente::class, Venda::class);
    // }

    // protected static function booted()
    // {
    //     if (auth()->user()->is_representante) {
    //         static::addGlobalScope('user', function (Builder $builder) {
    //             $builder->where('representante_id', auth()->user()->is_representante);
    //         });
    //     }
    // }

}
