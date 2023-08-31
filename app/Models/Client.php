<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // 1:N
    public function pedidos() {
        return $this->hasMany(Order::class);
    }
}
