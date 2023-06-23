<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parceiro extends Model 
{
    use SoftDeletes;
 
    protected $guarded = ['id'];
    
    public function pessoa() {
        return $this->belongsTo(Pessoa::class);
    } 

}

?>