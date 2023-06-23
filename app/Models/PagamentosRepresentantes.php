<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class PagamentosRepresentantes extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'pagamentos_representantes';

    protected $with = ['conta', 'parcela'];

    public function conta() {
        return $this->belongsTo(Conta::class);
    }

    public function parcela() {
        return $this->belongsTo(Parcela::class);
    }

    public function representante() {
        return $this->belongsTo(Representante::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
        });
    }
}
