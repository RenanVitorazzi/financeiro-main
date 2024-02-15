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
        //! PARA MYSQL 8.0 SERVER
        // return $query->select('*', DB::raw('SUM(peso_agregado) OVER ( ORDER BY data, id) AS saldo') )
        //     ->where('fornecedor_id', $fornecedor_id)
        //     ->orderBy('data')
        //     ->orderBy('id')
        //     ->get();

        //! PARA MYSQL 5.7
        $teste =  DB::select("SELECT id,
                data,
                balanco,
                peso,
                observacao,
                peso_agregado,
                (SELECT SUM(peso_agregado)
                    FROM conta_corrente
                    WHERE fornecedor_id = ?
                    AND deleted_at IS NULL
                    AND (data < cc.data OR (data = cc.data AND id <= cc.id))) as saldo
            FROM
                conta_corrente cc
            WHERE
                fornecedor_id = ?
                AND deleted_at IS NULL
            ORDER BY data, id",
            [$fornecedor_id, $fornecedor_id]
        );

        return $query->hydrate($teste);
      
    }

}

?>