<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Gasto;


class repetirGastoMinuto extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repetirGastoMinuto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca los gastos configurados para repetir y los segun su frecuencia';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
                // Buscar el dato en la base de datos (ejemplo: usuarios activos hoy)
                // $usuarios = DB::table('users')->whereDate('created_at', today())->count();

                // Guardar en el log
                // Log::info("Usuarios registrados hoy: " . $usuarios);
        
                // Mostrar en la terminal si se ejecuta manualmente
                // $this->info("Usuarios registrados hoy: " . $usuarios);

                $gastoRepetir = Gasto::where('repetir','Minuto')->get();
                // Log::info("Gastos A Repetir " . $gastoRepetir[0]->tipo);

                // log::info("Gastos A Repetir " . $gastoRepetir->count());

                // {
                //     "id": 1,
                //     "tipo": "Gasto",
                //     "importe": 123,
                //     "formaPago": "Cuenta Corriente",
                //     "estado": "Impago",
                //     "idProveedor": 11,
                //     "comentario": "sdf\nsdfsd\nfsdfs\ndfsdf\nsdfsdf\nsdfsdf\n",
                //     "diaNotificacion": 3,
                //     "usuario": "Desmaret",
                //     "repetir": "Minuto",
                //     "empresa_id": 1,
                //     "created_at": "2025-02-23T22:03:28.000000Z",
                //     "updated_at": "2025-02-23T22:03:28.000000Z"
                // }

                if($gastoRepetir->count() > 0){

                    foreach ($gastoRepetir as $gasto) {
                        // Log::info("Gasto Repetir " . $gasto->tipo);
                        // Crear un nuevo gasto con los datos del gasto a repetir
                        $Gasto = Gasto::create([
                            'tipo' => $gasto->tipo,
                            'importe' => 0,
                            'formaPago' => $gasto->formaPago,
                            'estado' => 'Impago',
                            'idProveedor' => $gasto->idProveedor,
                            'comentario' => $gasto->comentario,
                            'diaNotificacion' => $gasto->diaNotificacion,
                            'usuario' => $gasto->usuario,
                            'empresa_id' => $gasto->empresa_id,
                            'repetir' => 'Repetido',        
                        ]);

                        if(env('APP_ENV') == 'local'){
                            Log::info("Gasto Repetir " . $gasto);
                        }
    
                        // Log::info("Correcto " . $Gasto);
                        $this->info("Correcto " . $Gasto);
                    }

    
                    // Log::info("Correcto " . $gastoRepetir->count());
                    $this->info("Correcto " . $Gasto);
                }





    }
}
