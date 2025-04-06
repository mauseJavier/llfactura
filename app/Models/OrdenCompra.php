<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    protected $fillable = [
        'numero',
        'fecha',
        'empresa',
        'proveedor',
        'cuit_proveedor',
        'direccion_proveedor',
        'email_proveedor',
        'telefono_proveedor',
        'idProveedor',
        'subtotal',
        'iva',
        'total',
        'estado', // Estado de la orden de compra
        'empresa_id',
        'usuario',
        'usuario_id',
    ];

    public function articulos()
    {
        return $this->hasMany(ArticuloOrden::class, 'ordenCompraId');
    }
}

