<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // M:N
    public function productos() {
        return $this->belongsToMany(Product::class)->withPivot(["quantity"])->withTimestamps();
    }

    // N:1
    public function cliente() {
        return $this->belongsTo(Client::class);
    }

    // N:1
    public function user() {
        return $this->belongsTo(User::class);
    }
}
