<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
    //
    protected $guarded = [];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class, 'numeroMesa', 'id');
    }


}
