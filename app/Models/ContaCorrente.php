<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ContaCorrente extends Model {

    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'conta_corrente';
    protected $casts = [
        'conferido' => 'datetime:d/m/Y',
    ];

    public function fornecedor() 
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function anexos()
    {
        return $this->hasMany(ContaCorrenteAnexos::class);
    }

    public function scopeExtrato($query, $fornecedor_id)
    {
        return $query->select('*', DB::raw('SUM(peso_agregado) OVER ( ORDER BY id, data) AS saldo') )
            ->where('fornecedor_id', $fornecedor_id)
            ->orderBy('data')
            ->orderBy('id')
            ->get();
    }

}

?>