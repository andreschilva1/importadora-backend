<?php

namespace App\Livewire\usuarios;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Cliente;
use App\Utils\Utils;
use Illuminate\Support\Facades\File;

class CreateUser extends Component
{
    use WithFileUploads;

    public $open= false;
    public $name, $email, $password,$rol, $foto, $identificador;
    public $path;


    protected $rules = [
        'name' => 'required|max:25',
        'email' => 'required|email|unique:users|max:25',
        'password' => 'required|max:50',
        'rol' => 'required',
        'foto' => 'required|image|max:2048'
        
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
        
        $usuario = new User();
        
        //$foto = $this->foto->store('public/clientes/');
        $usuario->name = $this->name;
        $usuario->email = $this->email;
        $usuario->password =  Hash::make($this->password);
        $usuario->save();

        $nombre = $this->foto->getClientOriginalName();
        //dd($usuario->id);
        $path = $this->foto->storeAs('', $nombre, 's3');
        $fotoUrl = Storage::disk('s3')->url($path);

        $usuario->profile_photo_path = $fotoUrl;
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
        
        //eliminar archivos temporales
        Utils::eliminarArchivosTemporales('livewire-tmp');
        
        $this->reset(['name', 'email','password','foto']);
        $this->dispatch('render')->to(ShowUser::class);
        $this->dispatch('alert', mensaje: 'El usuario se creo satisfactoriamente');
        $this->open = !$this->open;
        
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.usuarios.create-user',compact('roles'));
    }
}
