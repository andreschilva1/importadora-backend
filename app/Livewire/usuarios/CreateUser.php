<?php

namespace App\Livewire\Usuarios;

use App\Models\Almacen;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Utils\Utils;
use Illuminate\Support\Facades\File;

class CreateUser extends Component
{
    use WithFileUploads;

    public $open= false;
    public $name, $email, $password,$rol, $foto, $identificador,$celular,$almacen_id;
    public $path;


    protected $rules = [
        'name' => 'required|max:25',
        'email' => 'required|email|unique:users|max:25',
        'password' => 'required|max:50',
        'rol' => 'required',
        /* 'foto' => 'required|image', */
        'celular' => 'required|max:8',
    ];

    public function mount()
    {
        $this->identificador = rand();
    }
    
    public function updatedOpen()
    {
        if ($this->open == true) {
            $this->reset(['name','email','foto','password','rol']);
            $this->identificador = rand();
        }
    }
    
       
    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    
    public function save()
    {
        $this->validate();

        $rol = Role::find($this->rol);

        if ( $rol->name  == 'Empleado') {
            if ($this->almacen_id == null) {
                $this->dispatch('alert', icono: 'error', mensaje: 'Debe seleccionar un almacen');
                return;
            }
        }

        $usuario = new User();
        
        //$foto = $this->foto->store('public/clientes/');
        $usuario->name = $this->name;
        $usuario->email = $this->email;
        $usuario->password =  Hash::make($this->password);
        $usuario->celular = $this->celular;
        $usuario->save();

        if ($this->foto != null) {
            $nombre = $this->foto->getClientOriginalName();
            $path = $this->foto->storeAs('', $nombre, 's3');
            $fotoUrl = Storage::disk('s3')->url($path);
            $usuario->profile_photo_path = $fotoUrl;
        }
        $usuario->save();
        
        $usuario->roles()->sync($this->rol);
        
    
        if ($usuario->getRoleNames()->first() == 'Cliente') {

            $inicialNombre = substr($usuario->name, 0, 1);
            $ultimos4DigitosCelular = substr($usuario->celular, -4);
            Cliente::create([
                'numero_casillero' => $inicialNombre . $usuario->id . $ultimos4DigitosCelular,
                'user_id' => $usuario->id,
            ]);
            $usuario->save();
        }

        if ($usuario->getRoleNames()->first() == 'Empleado') {

            
            Empleado::create([
                'user_id' => $usuario->id,
                'almacen_id' => $this->almacen_id,
            ]);
        }
        
        //eliminar archivos temporales
        Utils::eliminarArchivosTemporales('livewire-tmp');
        
        $this->reset(['name', 'email','password','foto']);
        $this->dispatch('render')->to(ShowUser::class);
        $this->dispatch('alert', icono: 'success', mensaje: 'Se creo el usuario correctamente');
        $this->open = !$this->open;
        
    }

    public function render()
    {
        $roles = Role::all();
        $almacenes = Almacen::all();
        return view('livewire.usuarios.create-user',compact('roles','almacenes'));
    }
}
