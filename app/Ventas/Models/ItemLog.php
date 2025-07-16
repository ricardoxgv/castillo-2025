<?php

namespace App\Ventas\Models;


use Illuminate\Database\Eloquent\Model;

class ItemLog extends Model
{
    protected $fillable = ['item_id', 'user_id', 'accion', 'detalles'];
}
