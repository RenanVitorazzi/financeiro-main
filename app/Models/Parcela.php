<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function scopeAdiamentosDoDia($query, $dia)
    {
        return $query->with('representante.pessoa:id,nome', 'parceiro.pessoa:id,nome', 'adiamentos')
        ->whereHas('adiamentos', function (Builder $query) use ($dia) {
            $query->whereDate('created_at', '=', $dia);
        })
        ->orderBy('representante_id')
        ->orderBy('data_parcela')
        ->orderBy('valor_parcela')
        ->get();
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

    // public function cliente()
    // {
    //     return $this->hasOneThrough(Cliente::class, Venda::class);
    // }

    protected static function booted()
    {
        if (auth()->user()->is_representante) {
            static::addGlobalScope('user', function (Builder $builder) {
                $builder->where('representante_id', auth()->user()->is_representante);
            });
        }
    }

}
