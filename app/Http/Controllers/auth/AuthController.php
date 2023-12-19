<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'idTipoIdentificacion' => 'required',
                'identificacion' => 'required|unique:persona',
                'nombre1' => 'required',
                'apellido1' => 'required',
                'email' => 'required|email|unique:usuario',
                'contrasena' => 'required',
                'celular' => 'required'
            ]);

            DB::beginTransaction();

            $persona = new Person();
            $persona->idTipoIdentificacion = $validatedData['idTipoIdentificacion'];
            $persona->identificacion = $validatedData['identificacion'];
            $persona->nombre1 = $validatedData['nombre1'];
            $persona->apellido1 = $validatedData['apellido1'];
            $persona->email = $validatedData['email'];
            $persona->celular = $validatedData['celular'];
             $persona->rutaFoto = $this->storeLogoPersona($request);
            $persona->save();

            $usuario = new User();
            $usuario->email = $validatedData['email'];
            $usuario->contrasena = bcrypt($validatedData['contrasena']);
            $usuario->idpersona = $persona->id;
            $usuario->save();

            DB::commit();
            $token = JWTAuth::fromUser($usuario);

            return response()->json(compact('token'), 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => 'Se encontraron errores de validación en la solicitud.'], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al procesar la solicitud'], 500);
        }
    }


    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'contrasena' => 'required|min:8',

        ]);

        try {
            $user = User::where('email', $validatedData['email'])->first();

            if (!$user || !Hash::check($validatedData['contrasena'], $user->contrasena)) {
                return response()->json([
                    'error' => 'Credenciales inválidas'
                ], 400);
            }

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('token'), 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar la solicitud'
            ], 500);
        }
    }





    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }



    public function userData()
    {
        if (auth()->check()) {
            $user = auth()->user()->load('persona');

            return response()->json(["userData" => $user], 200);
        } else {
            return response()->json(["message" => "No autorizado"], 401);
        }
    }

    private function storeLogoPersona(Request $request, $default = true)
    {
        $rutaFoto = null;

        if ($default) {
            $rutaFoto = Person::RUTA_FOTO_DEFAULT;
        }
        if ($request->hasFile('rutaFotoFile')) {
            $rutaFoto =
                '/storage/' .
                $request
                ->file('rutaFotoFile')
                ->store(Person::RUTA_FOTO, ['disk' => 'public']);
        }
        return $rutaFoto;
    }

}
