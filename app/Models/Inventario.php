<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\DB;



class Inventario extends Model
{
    use HasFactory;

    protected $guarded = [];

        // Relación personalizada: un artículo tiene muchos stocks por código y empresa_id

        public function stocks()
        {
            return $this->hasMany(Stock::class, 'codigo', 'codigo')->latest('id');
        }

        public static function traerId($id,$precio)
        {
            return DB::table('inventarios')->select('codigo','detalle',$precio.' as precio','iva')
            ->where('id', $id)

            ->get();


            // $users = DB::table('users')
            // ->select(DB::raw('count(*) as user_count, status'))
            // ->where('status', '<>', 1)
            // ->groupBy('status')
            // ->get();


        }
}