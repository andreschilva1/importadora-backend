<?php

namespace App\Utils;



class Utils 
{
   static function eliminarArchivosTemporales($carpetaInStorageApp){  
    
        $directory = storage_path('app/'.$carpetaInStorageApp);
            if (file_exists($directory)) {
                $files = glob($directory . '/*'); // Obtiene todos los archivos dentro del directorio

                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file); // Elimina el archivo
                    }
                }
            }    
    }
   
}
