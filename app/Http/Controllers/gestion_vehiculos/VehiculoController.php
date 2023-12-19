<?php

namespace App\Http\Controllers\gestion_vehiculos;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function storeVehiculo(Request $request){
        try {
     
            $validatedData = $request->validate([
                'placa' => 'required|unique:vehiculo',
            ]);
    
            $vehiculo = new Vehiculo();
            $vehiculo->placa = $validatedData['placa'];
            $vehiculo->color = $request->input('color');
            $vehiculo->marca = $request->input('marca');
            $vehiculo->modelo = $request->input('modelo');
            $vehiculo->imagen = $this->storeImagenVehiculo($request);
            $vehiculo->idUser = auth()->user()->id;
            $vehiculo->save();
    
            return response()->json(['vehiculo' => $vehiculo, 'mensaje' => 'Vehículo registrado exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()], 500);
        }
    }

    private function storeImagenVehiculo(Request $request, $default = true)
    {
        $rutaFoto = null;

        if ($default) {
            $rutaFoto = Vehiculo::RUTA_FOTO_DEFAULT;
        }
        if ($request->hasFile('rutaFotoFile')) {
            $rutaFoto =
                '/storage/' .
                $request
                    ->file('rutaFotoFile')
                    ->store(Vehiculo::RUTA_FOTO, ['disk' => 'public']);
        }
        return $rutaFoto;
    }
}
