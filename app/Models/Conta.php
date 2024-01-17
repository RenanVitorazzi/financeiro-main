<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('inativo', function (Builder $builder) {
            $builder->where('inativo', '=', 0);
        });
        
    }

}
