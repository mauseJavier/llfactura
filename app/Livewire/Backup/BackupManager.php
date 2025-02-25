<?php

namespace App\Livewire\Backup;

use Livewire\Component;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;



class BackupManager extends Component
{

    public $files = [];

    public function mount()
    {
        // Obtén los archivos del directorio deseado
        $this->files = Storage::disk('backup')->allFiles(); // Cambia 'public' si usas otro disco

        // dd($this->files);
    }


    public function runBackup()
    {
        Artisan::call('backup:run');
        session()->flash('message', 'Backup creado con éxito.');

        // Obtén los archivos del directorio deseado
        $this->files = Storage::disk('backup')->allFiles(); // Cambia 'public' si usas otro disco
    }

    public function download($file)
    {
        // Verifica que el archivo existe antes de proceder
        if (Storage::disk('backup')->exists($file)) {
            return Storage::disk('backup')->download($file);
        }

        session()->flash('error', 'El archivo no existe.');
    }

   

    public function delete($file)
    {
        // Verifica que el archivo existe antes de proceder
        if (Storage::disk('backup')->exists($file)) {
            
            Storage::disk('backup')->delete($file);
            session()->flash('error', 'El archivo borrado.');

        }else{

            session()->flash('error', 'El archivo no existe.');
        }

                // Obtén los archivos del directorio deseado
                $this->files = Storage::disk('backup')->allFiles(); // Cambia 'public' si usas otro disco

    }





    public function render()
    {
        // $files = Storage::files('/'.env('APP_NAME', 'laravel-backup'));
        // dd($files );
        return view('livewire.backup.backup-manager',[
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
