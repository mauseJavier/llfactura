<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloOrden extends Model
{
    protected $fillable = [
        'ordenCompraId',
        'codigo',
        'detalle',

        'rubro',
        'proveedor',
        'marca',

        'cantidad',
        'costoUnitario',
        'subTotal',
    ];

    public function orden()
    {
        return $this->belongsTo(OrdenCompra::class, 'ordenCompraId');
    }
}

