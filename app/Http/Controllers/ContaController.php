<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use Illuminate\Http\Request;

class ContaController extends Controller
{
    public function procurarContas() {
        return Conta::all()->toJson();
    }
}
