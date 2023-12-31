<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'despesas';
    public $timestamps = false;
    
    public function local() {
        return $this->belongsTo(Local::class);
    } 

    public function fixa() {
        return $this->belongsTo(DespesaFixa::class);
    } 

    public function conta() {
        return $this->belongsTo(Conta::class);
    } 
}
