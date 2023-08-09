<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consignado extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $with = ['cliente', 'representante'];

    public function representante() 
    {
        return $this->belongsTo(Representante::class)->with('pessoa');
    }

    public function venda() 
    {
        return $this->belongsTo(Venda::class);
    }

    public function cliente() 
    {
        return $this->belongsTo(Cliente::class)->with('pessoa');;
    }

    protected static function booted()
    {
        if (auth()->user()->is_representante) {
            static::addGlobalScope('consignado', function (Builder $builder) {
                $builder->where('representante_id', auth()->user()->is_representante);
            });
        }
    }
}
