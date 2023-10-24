
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Gestionar Usuarios
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    
                <!-- Page header -->
                <div class="sm:flex sm:justify-between sm:items-center mb-5">
                    
                    <!-- Left: Title -->
                    <div class="mb-4 sm:mb-0">
                        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Usuarios ✨</h1>
                    </div>
                    
                    <!-- Right: Actions -->
                    <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                        
                        <!-- Search form -->
                        <div class="relative">
                            <x-input wire:model.live="search" type="search" placeholder="Search…"
                            class="form-input pl-9  focus:ring-blue-500 focus:border-blue-500" />
                            <span class="absolute pr-3 pt-2.5 inset-0 right-auto  ">
                                <svg class=" w-4 h-4 shrink-0 fill-current text-slate-400 group-hover:text-slate-500 ml-3 mr-2"
                                viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                <path
                                d="M7 14c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zM7 2C4.243 2 2 4.243 2 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5z" />
                                <path
                                d="M15.707 14.293L13.314 11.9a8.019 8.019 0 01-1.414 1.414l2.393 2.393a.997.997 0 001.414 0 .999.999 0 000-1.414z" />
                            </svg>
                        </span>
                    </div>
                    
                    
                    @livewire('usuarios.create-user')
                </div>
                
            </div>
            
                <!-- More actions -->
                <div class="sm:flex sm:justify-between sm:items-center mb-5">
            
            
                    <div class="mb-4 sm:mb-0">
                        <label class="mr4" for="">
                            Mostrar
                        </label>
                        <select class="form-select" wire:model.live="count">
            
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
            
                        </select>
                        <label class="ml4" for="">
                            Filas
                        </label>
            
                    </div>
            
                </div>
            
            
                <!-- Table -->
            
                @if (!$usuarios->isEmpty())
                    <table class="min-w-full  border-collapse block md:table">
                        <thead class="bg-gray-50 block md:table-header-group">
                            <tr
                                class=" border border-gray-500 md:border-none block md:table-row absolute -top-full md:top-auto -left-full md:left-auto  md:relative ">
                                <th
                                    class=" p-2 text-gray-900 font-bold  md:border-2 md:border-grey-500 text-left block md:table-cell">
                                    Id</th>
                                <th
                                    class=" p-2 text-gray-900 font-bold md:border-2 md:border-grey-500 text-left block md:table-cell">
                                    Foto</th>
                                <th
                                    class="p-2 text-gray-900 font-bold md:border-2 md:border-grey-500 text-left block md:table-cell">
                                    Nombre</th>
                                <th
                                    class=" p-2 text-gray-900 font-bold md:border-2 md:border-grey-500 text-left block md:table-cell">
                                    Email Address</th>
            
                                <th
                                    class=" p-2 text-gray-900 font-bold md:border-2 md:border-grey-500 text-left block md:table-cell">
                                    Rol</th>
            
                                <th
                                    class=" p-2 text-gray-900 font-bold md:border-2 md:border-grey-500 text-left block md:table-cell">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="block md:table-row-group">
                            @foreach ($usuarios as $user)
                                <tr
                                    class="bg-gray-100 border-2 hover:bg-gray-300 border-gray-500 rounded-lg m-2 md:border-none block md:table-row" wire:key="user-{{$user->id}}">
            
            
                                    <td class="  p-2 md:border md:border-grey-500 text-left block md:table-cell"><span
                                            class="inline-block w-1/3 md:hidden font-bold">Id:</span>
            
                                        <div class="flex justify-center">
                                            {{ $user->id }}
            
                                        </div>
            
            
                                    </td>
                                    <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span
                                            class="inline-block w-1/3 md:hidden font-bold">Foto:</span>
            
                                        <div class=" flex justify-center">
            
                                            @if ($user->profile_photo_path)
                                                <a href="#" wire:click="edit({{ $user->id }})">
                                                    <img class=" h-14 w-14 m-1 rounded-full object-cover object-center  relative "
                                                        src="{{ $user->profile_photo_path }}" alt="" />
                                                </a>
                                            @else
                                                <a class=" h-14 w-14 bg-indigo-400 rounded-full border-black border-2 text-slate-700 hover:bg-indigo-500 hover:text-black"
                                                    href="#" wire:click="edit({{ $user->id }})">
                                                    <img class="mt-3 ml-2 h-9 w-9 m-1  object-cover object-center  relative "
                                                        src="{{ asset('images/imagen.png') }}" alt="" />
                                                </a>
                                            @endif
            
                                        </div>
            
                                    </td>
                                    <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span
                                            class="inline-block w-1/3 md:hidden font-bold">Nombre:</span>{{ $user->name }}</td>
                                    <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell"><span
                                            class="inline-block w-1/3 md:hidden font-bold">Email:</span>{{ $user->email }}
                                    </td>
            
                                    <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                                        <span class="inline-block w-1/3 md:hidden font-bold">Rol:</span>
            
                                        @if ($user->getRoleNames()->first())
                                            {{ $user->getRoleNames()->first() }}
                                        @else
                                            sin Rol
                                        @endif
            
            
                                    </td>
            
                                    <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                                        <span class="inline-block w-1/3 md:hidden font-bold">Actions:</span>
                                        <div class="flex justify-center gap-4">
            
                                            {{--  @livewire('update-user', ['usuario' => $usuario, 'rol'], key($usuario->id)) --}}
            
                                            <button class="btn-sm border-2 border-black bg-indigo-400 rounded-lg text-slate-700 hover:bg-indigo-500 hover:text-black"
                                             wire:click="edit({{$user->id}})">
                                                
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="h-6 w-6" x-tooltip="tooltip">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                </svg>
                                            </button>
            
            
                                            <a class="btn-sm border-2 border-black bg-indigo-400 rounded-lg text-slate-700 hover:bg-indigo-500 hover:text-black"
                                                href="#"
                                                wire:click="$dispatch('deleteUser', { userId: {{ $user->id }} })">
            
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="h-6 w-6" x-tooltip="tooltip">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </a>
            
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
            
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <div class="px-6 py-3">
            
                        {{ $usuarios->links() }}
            
                    </div>
                @else
                    <span class="text-black">No existe ningun registro coincidente</span>
            
                @endif
            
            
            
            @if ($open)
                <x-dialog-modal>
            
                    <x-slot name="title">
                        Actualizar Usuario
                    </x-slot>
            
                    <x-slot name="content">
                        <div class="px-4 ">
            
                            <label for="">
                                Foto de Perfil<span class="text-rose-500">*</span>
                            </label>
            
                            <div class="flex items-center">
                                <div class="m-3">
            
                                    @if ($foto)
                                        <img class="h-24 w-24 rounded-full" src="{{ $foto->temporaryUrl() }}" alt="">
                                    @elseif($usuario['profile_photo_path'])
                                        <img class="h-24 w-24 rounded-full"
                                        src="{{ $usuario['profile_photo_path'] }}" alt="">
                                    @else
                                        <span class="btn-sm bg-indigo-500 rounded-lg text-slate-700">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0"
                                                y="0" width="40" height="40" viewBox="0 0 134 134"
                                                xml:space="preserve">
                                                <circle cx="67" cy="67" r="65" fill="#d2d2d2">
                                                </circle>
                                                <path
                                                    d="M67 2c35.841 0 65 29.159 65 65s-29.159 65-65 65S2 102.841 2 67 31.159 2 67 2m0-1C30.55 1 1 30.55 1 67s29.55 66 66 66 66-29.55 66-66S103.45 1 67 1z"
                                                    fill="#0c0d0d" opacity=".2"></path>
                                                <path class="st3"
                                                    d="M65.92 66.34h2.16c14.802.421 30.928 6.062 29.283 20.349l-1.618 13.322c-.844 6.814-5.208 7.827-13.972 7.865H52.23c-8.765-.038-13.13-1.05-13.974-7.865l-1.62-13.322C34.994 72.402 51.12 66.761 65.92 66.341zM49.432 43.934c0-9.819 7.989-17.81 17.807-17.81 9.822 0 17.81 7.991 17.81 17.81 0 9.819-7.988 17.807-17.81 17.807-9.818 0-17.807-7.988-17.807-17.807z">
                                                </path>
                                            </svg>
                                        </span>
                                    @endif
            
            
            
                                </div>
            
                                <x-input type="file" class="w-full" id="{{ $identificador }}" wire:model="foto" />
            
                            </div>
                            <div class="mb-3">
                                <x-input-error for="foto" />
                            </div>
            
            
            
                            <div class="mb-4">
                                <label class="" for="">
                                    Nombre <span class="text-rose-500">*</span>
                                </label>
            
                                <x-input type="text" class=" w-full " wire:model="usuario.name" />
                                <x-input-error for="usuario.name" />
            
                            </div>
            
            
                            <div class="mb-4">
                                <label for="">
                                    email <span class="text-rose-500">*</span>
                                </label>
                                <x-input type="email" class="w-full" wire:model="usuario.email" />
                                <x-input-error for="usuario.email" />
            
                            </div>
            
                            <div class="mb-4">
                                <label for="">
                                    password<span class="text-rose-500">*</span>
                                </label>
                                <x-input type="password" class="w-full" wire:model="usuario.password" />
                                <x-input-error for="password" />
            
            
                                <div class="mb-4">
                                    <label class="mr4" for="">
                                        Tipo de Usuario<span class="text-rose-500">*</span>
                                    </label>
                                    <select class="form-select w-full" wire:model="usuario.rol">
                                        <option value="{{ $usuario['rol'] }}"></option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error for="rol" />
            
                                </div>
            
            
                            </div>
            
                    </x-slot>
            
                    <x-slot name="footer">
                        <div class="mr-4">
            
                            <button class="h-full rounded-md bg-red-500 hover:bg-red-600 text-white p-2"
                            wire:click="$set('open',false)">Cancelar</button>
            
                            <button
                            class="h-full rounded-md bg-indigo-500 hover:bg-indigo-600 text-white p-2"
                                wire:loading.attr="disabled" wire:target="update,foto" wire:click="update">
                                <svg wire:loading wire:target="update,foto" class="animate-spin w-4 h-4 fill-current shrink-0"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M8 16a7.928 7.928 0 01-3.428-.77l.857-1.807A6.006 6.006 0 0014 8c0-3.309-2.691-6-6-6a6.006 6.006 0 00-5.422 8.572l-1.806.859A7.929 7.929 0 010 8c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z">
                                    </path>
                                </svg>
                                <span class="ml-2" wire:loading.remove wire:target="update,foto">Actualizar</span>
                                <span wire:loading wire:target="update,foto"> cargando </span>
            
                            </button>
            
                        </div>
            
                    </x-slot>
                </x-dialog-modal>
            @endif
            
            
                @push('js')
                    <script>
                        document.addEventListener('livewire:initialized', () => {
                            @this.on('deleteUser', (userId) => {
            
                                Swal.fire({
                                    title: 'Estas seguro?',
                                    text: "¡No podrás revertir esto!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#7066e0',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Si, Eliminiar!',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        console.log(userId);
                                        @this.dispatchSelf('delete', {
                                            user: userId
                                        });
                                        Swal.fire(
                                            'Eliminado!',
                                            'El usuario ha sido eliminado.',
                                            'success'
                                        )
                                    }
                                })
            
                            });
                        });
                    </script>
                @endpush
            
            </div>
        </div>
    </div>
</div>