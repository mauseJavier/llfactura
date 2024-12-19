<?php

namespace App\Livewire\Empresa;

// use Afip;
use Livewire\Component;

use App\Events\GenerarCertificadoEvent;
use Afip;

use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;



use Livewire\Attributes\Validate;
use Livewire\WithPagination;

use App\Models\Empresa;
use App\Models\Deposito;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VerEmpresa extends Component
{

    public $datoBuscado;
    public $modal = 'close';
    public $preloader = 'close';


    public $id=Null;

    #[Validate('required', message: 'Requerido')]
    #[Validate('min:1', message: 'Minimo 1 caracter')]
    #[Validate('max:250', message: 'Maximo 250 caracter')]
    public $razonSocial='';
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:1', message: 'Minimo 1 caracter')]
    #[Validate('max:250', message: 'Maximo 250 caracter')]
    public $titular='';
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:1', message: 'Minimo 1 caracter')]

    #[Validate('numeric', message: 'Solo Numeros')]
    public $cuit='';
    #[Validate('required', message: 'Requerido')]
    public $claveFiscal='';
    public $fe = 'si';
    public $iva='ME';
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:1', message: 'Minimo 1 caracter')]
    #[Validate('numeric', message: 'Solo Numeros')]
    public $ivaDefecto=21;
    #[Validate('required', message: 'Requerido')]
    public $inicioActividades ;

    #[Validate('required', message: 'Requerido')]
    #[Validate('min:1', message: 'Minimo 1 caracter')]
    #[Validate('numeric', message: 'Solo Numeros')]
    public $precio2=50;
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:1', message: 'Minimo 1 caracter')]
    #[Validate('numeric', message: 'Solo Numeros')]
    public $precio3=100;
    public $domicilio='';
    #[Validate('numeric', message: 'Solo Numeros')]
    public $telefono;
    public $correo='';
    public $logo='';

    public $generarCertificado='no';



    public function datosEmpresa($idEmpresa){

        $empresa = Empresa::find($idEmpresa);

        $afip = $this->objetoAfip($empresa);

        $sales_points = $afip->ElectronicBilling->GetSalesPoints();

        dump($sales_points);

        $concept_types = $afip->ElectronicBilling->GetConceptTypes();

        dump($concept_types);



    }

    function objetoAfip($empresa){
        // // Certificado (Puede estar guardado en archivos, DB, etc)
        // $cert = file_get_contents('./certificado.crt');

        // // Key (Puede estar guardado en archivos, DB, etc)
        // $key = file_get_contents('./key.key');

        // dd(Storage::disk('local')->exists('public/'.$this->empresa->cuit.'/ert.crt') );


        if (Storage::disk('local')->exists('public/'.$empresa->cuit.'/cert.crt') ) {
            // ...
            $cert = Storage::get('public/'.$empresa->cuit.'/cert.crt');

            // return response()->json($cert, 200);
            
        }else
        {
            dd('No existe certificado');
        }

        if ( Storage::disk('local')->exists('public/'.$empresa->cuit.'/key.key')) {
            // ...

            $key = Storage::get('public/'.$empresa->cuit.'/key.key');

            // return response()->json($key, 200);
            
        }else
        {
            dd('No existe key');
        }



        $afip = new Afip(array(
            'CUIT' => $empresa->cuit,
            'cert' => $cert,
            'key' => $key,
            'access_token' => env('tokenAFIPsdk'),
            'production' => TRUE
        ));

        return $afip;
    }

    public function guardarEmpresa(){

        $this->preloader = 'open';

        // $this->validate();

        $nuevaEmpresa = Empresa::updateOrCreate(
            ['id' => $this->id, ],
            [
                 
                'cuit' => $this->cuit,
                'razonSocial' => $this->razonSocial,
                'claveFiscal'=> $this->claveFiscal,
                'domicilio'=> $this->domicilio,
                'fe'=> $this->fe,
                'iva'=> $this->iva,
                'ivaDefecto'=> $this->ivaDefecto,
                'precio2'=> round($this->precio2,2),
                'precio3'=> round($this->precio3,2),
                'inicioActividades'=> $this->inicioActividades,
                'telefono'=> $this->telefono,
                'titular'=> $this->titular,
                'logo'=> $this->logo,
                'correo'=> $this->correo,
            ]
        );

        $despositoEmpresa = Deposito::updateOrCreate(
            ['nombre' => 'General', 'empresa_id' => $nuevaEmpresa->id],
            [
                'Comentario'=> 'Nuevo Deposito',

            ]
        );


        if( $this->generarCertificado == 'si'){

            GenerarCertificadoEvent::dispatch($this->cuit,$this->claveFiscal);
        }

        $this->razonSocial='';
        $this->titular='';
        $this->cuit='';
        $this->claveFiscal='';
        $this->fe = 'si';
        $this->iva='ME';
        $this->ivaDefecto=21;
        $this->inicioActividades;
        $this->precio2=50;
        $this->precio3=100;
        $this->domicilio='';
        $this->telefono='';
        $this->logo='';
        $this->correo='';
        $this->id=null;


        $this->datoBuscado= $nuevaEmpresa->razonSocial;
        $this->modal='close';
        $this->preloader = 'close';
    }

    public function editarId(Empresa $empresa){
        // dd($articulo);
        // array:15 [▼
        //     "id" => 2
        //     "codigo" => "50733354"
        //     "detalle" => "maiores"
        //     "costo" => 979.36
        //     "precio1" => 99.67
        //     "precio2" => 71.53
        //     "precio3" => 138.17
        //     "iva" => 21.0
        //     "rubro" => "General"
        //     "proveedor" => "MAUSE"
        //     "pesable" => "si"
        //     "imagen" => null
        //     "empresa_id" => 1
        //     "created_at" => "2024-05-20 17:19:06"
        //     "updated_at" => "2024-05-20 17:19:06"
        // ]


        $this->id= $empresa->id;

        $this->razonSocial= $empresa->razonSocial;
        $this->titular= $empresa->titular;
        $this->cuit= $empresa->cuit;
        $this->claveFiscal= $empresa->claveFiscal;
        $this->fe = $empresa->fe;
        $this->iva= $empresa->iva;
        $this->ivaDefecto= $empresa->ivaDefecto;
        $this->inicioActividades= $empresa->inicioActividades;
        $this->precio2= $empresa->precio2;
        $this->precio3= $empresa->precio3;
        $this->domicilio= $empresa->domicilio;
        $this->telefono= $empresa->telefono;
        $this->logo= $empresa->logo;
        $this->correo= $empresa->correo;


        $this->modal="open";
    }

    public function cambiarModal(){

        if($this->modal == 'close'){
            $this->modal = 'open';
        }else{
            $this->modal = 'close';

            $this->razonSocial='';
            $this->titular='';
            $this->cuit='';
            $this->claveFiscal='';
            $this->fe = 'si';
            $this->iva='ME';
            $this->ivaDefecto=21;
            $this->inicioActividades;
            $this->precio2=50;
            $this->precio3=100;
            $this->domicilio='';
            $this->telefono='';
            $this->logo='';
            $this->correo='';
            $this->id=null;

            
        }
    }

    public function mount()
    {
        $this->inicioActividades = Carbon::now()->format('Y-m-d');
        // $now = Carbon::now();
 

    }

    public function eliminarEmpresa($empresa_id,$accion){

        $empresa = Empresa::find($empresa_id);
        // dd($empresa->cuit);

        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        
        if($accion == 'ver'){

            dump($empresa);
            
            
            foreach ($tables as $table) {
                
                if($table == 'empresas'){
                    
                    // SOLO PARA LA TABLA EMPRESAS QUE ES LA DE ID EMPRESA
                    $datos = DB::table($table)->where('id', $empresa_id)->get();    
                    dump(array($table,$datos,count($datos)));
    
                }else{
    
                    // Verifica si la tabla tiene la columna empresa_id antes de intentar borrar
        
                    if (Schema::hasColumn($table, 'empresa_id')) {
                        $datos = DB::table($table)->where('empresa_id', $empresa_id)->get();    
                        dump(array($table,$datos,count($datos)));
                    }
                    else{
                        // dump($table);
                    }
                }
            }

        }else{

            DB::transaction(function () use ($tables,$empresa_id) {
                foreach ($tables as $table) {

                    if($table == 'empresas'){
                        DB::table($table)->where('id', $empresa_id)->delete();
                    }else{

                        if (Schema::hasColumn($table, 'empresa_id')) {
                            DB::table($table)->where('empresa_id', $empresa_id)->delete();
                        }
                    }
                }
            });


            // COMPROBAMOS QUE SE ELIMINO TODO 
            foreach ($tables as $table) {
                
                if($table == 'empresas'){
                    
                    // SOLO PARA LA TABLA EMPRESAS QUE ES LA DE ID EMPRESA
                    $datos = DB::table($table)->where('id', $empresa_id)->get();    
                    dump(array($table,$datos,count($datos)));
    
                }else{
    
                    // Verifica si la tabla tiene la columna empresa_id antes de intentar borrar
        
                    if (Schema::hasColumn($table, 'empresa_id')) {
                        $datos = DB::table($table)->where('empresa_id', $empresa_id)->get();    
                        dump(array($table,$datos,count($datos)));
                    }
                    else{
                        // dump($table);
                    }
                }
            }


            // Storage::put('public/'.$empresa->cuit.'/key.key', $res->key);

            $directoryPath= 'public/'.$empresa->cuit.'';
            if (Storage::exists($directoryPath)) {
                Storage::deleteDirectory($directoryPath);
            }


        }

        
        

    }

    public function render()
    {
        return view('livewire.empresa.ver-empresa',
        [
            'empresas'=> Empresa::whereAny([
                                'razonSocial',
                                'titular',
                                'cuit',
                                'correo'
                            ], 'LIKE', "%$this->datoBuscado%")    
                            ->orderBy('created_at','DESC')                            
                            ->paginate(500),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
