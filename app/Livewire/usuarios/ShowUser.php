<?php

namespace App\Livewire\usuarios;

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
        'rol' => '',
    ];
    public $userId;
    

    protected $listeners = ['render', 'delete'];

    protected $rules = [
        'usuario.name' => 'required|max:25',
        'usuario.email' => 'required|email|max:25',
        'usuario.rol' => 'required',
    ];


    public function render()
    {

        $roles = Role::all();
        $usuarios = User::join('model_has_roles', 'model_has_roles.model_id', 'users.id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')->select('users.*', 'roles.name as rol')
            ->where('users.name', 'like', '%' . $this->search . '%')
            ->orWhere('users.email', 'like', '%' . $this->search . '%')
            ->orWhere('roles.name', 'like', '%' . $this->search . '%')->orderBy('users.id', 'desc')->paginate($this->count);
        return view('livewire.usuarios.show-user', compact('usuarios', 'roles'));
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

    }

    public function update()
    {
        $this->validate();

        $user = User::find($this->userId);

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

        $user->save();
        $user->roles()->sync($this->usuario['rol']);
        
        Utils::eliminarArchivosTemporales('livewire-tmp');
        
        $this->dispatch('alert', mensaje: 'El usuario se actualizo satisfactoriamente');
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
