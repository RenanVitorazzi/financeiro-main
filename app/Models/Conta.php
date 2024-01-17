<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
<<<<<<< HEAD
    public $timestamps = false;
    protected $guarded = ['id'];
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('inativo', function (Builder $builder) {
            $builder->where('inativo', '=', 0);
        });
        
    }

=======
    protected $guarded = ['id'];
    use HasFactory;

>>>>>>> e3a02241119ebbfc79e912da11238c16e3deac16
}
