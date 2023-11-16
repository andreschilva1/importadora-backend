<?php

namespace App\Livewire\Usuarios;

use App\Models\Almacen;
use App\Models\Empleado;
use App\Models\User;
use App\Utils\Utils;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ShowUser extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $foto, $identificador, $search;
    public $count = 5;
    public $open = false;

    public $usuario = [
        'name' => '',
        'email' => '',
        'password' => '',
        'profile_photo_path' => '',
        'celular' => '',
        'rol' => '',
        'almacen_id' => '',
    ];
    
    public $userId;

    protected $listeners = ['render', 'delete'];

    protected $rules = [
        'usuario.name' => 'required|max:25',
        'usuario.email' => 'required|email|max:25',
        'usuario.rol' => 'required',
        'usuario.celular' => 'required|max:8',
    ];


    public function render()
    {

        $roles = Role::all();
        $almacenes = Almacen::all();
        $usuarios = User::join('model_has_roles', 'model_has_roles.model_id', 'users.id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')->select('users.*', 'roles.name as rol')
            ->where('users.name', 'like', '%' . $this->search . '%')
            ->orWhere('users.email', 'like', '%' . $this->search . '%')
            ->orWhere('roles.name', 'like', '%' . $this->search . '%')->orderBy('users.id', 'desc')->paginate($this->count);
        return view('livewire.usuarios.show-user', compact('usuarios', 'roles', 'almacenes'));
    }



    public function mount()
    {   
        $this->identificador = rand();
    }

    public function updatedOpen()
    {
        if ($this->open == true) {
            $this->identificador = rand();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCount()
    {
        $this->resetPage();
    }



    public function edit($userId)
    {

       $this->open = true;
       $user = User::find($userId);
       $this->userId = $user->id;
       $this->usuario['rol'] = $user->roles->pluck('id')->first();
       $this->usuario['name'] = $user->name;    
       $this->usuario['email'] = $user->email;
       $this->usuario['profile_photo_path'] = $user->profile_photo_path;
       $this->usuario['celular'] = $user->celular;

       if ($user->roles->pluck('name')->first() == 'Empleado') {
           $empleadoActual = $user->empleado;
           $this->usuario['almacen_id'] = $empleadoActual->almacen_id;
       }

    }

    public function update()
    {
        $this->validate();
        
        
        $user = User::find($this->userId);
        
        if ( $user->roles->pluck('name')->first()  == 'Empleado') {
            if ($this->usuario['almacen_id'] == null) {
                $this->dispatch('alert', icono: 'error', mensaje: 'Debe seleccionar un almacen');
                return;
            }
            $empleadoActual = $user->empleado;
            $empleadoActual->almacen_id = $this->usuario['almacen_id'];
            $empleadoActual->save();
        }

        if ($this->foto) {
            if ($user->profile_photo_path) {
                $nombreImagen = parse_url($user->profile_photo_path, PHP_URL_PATH);
                Storage::disk('s3')->delete($nombreImagen);
            }

            $nombre = $this->foto->getClientOriginalName();
            $path = $this->foto->storeAs('', $nombre, 's3');
            $fotoUrl = Storage::disk('s3')->url($path);
    
            $user->profile_photo_path = $fotoUrl;

        }

        $user->name = $this->usuario['name'];
        $user->email = $this->usuario['email'];
        if ($this->usuario['password'] != "") {
            $user->password =  Hash::make($this->pass);
        }
        $user->celular = $this->usuario['celular'];
        $user->save();
        $user->roles()->sync($this->usuario['rol']);
        
        Utils::eliminarArchivosTemporales('livewire-tmp');

        $this->dispatch('alert', mensaje: 'El usuario se actualizo satisfactoriamente', icono: 'success');
        $this->reset(['open', 'usuario', 'foto', 'identificador']);
        $this->open = false;
        $this->dispatch('render')->self();
    }

    public function delete(User $user)
    {

        if ($user->profile_photo_path) {
            $nombreImagen = parse_url($user->profile_photo_path, PHP_URL_PATH);
            //eliminar foto  de s3
            Storage::disk('s3')->delete($nombreImagen);
        }

        $user->delete();
    }
}
