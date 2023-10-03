<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntregaParcela extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'entrega_parcela';
    public $timestamps = false; 
    
    public function parcelas() {
        return $this->belongsTo(Parcela::class);
    } 

    // protected static function booted(): void
    // {
    //     static::addGlobalScope('enviado', function (Builder $builder) {
    //         $builder->whereNull('enviado');
    //     });
    // }
}
