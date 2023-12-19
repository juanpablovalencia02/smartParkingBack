<?php

namespace App\Http\Controllers\gestion_rol;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Permission\PermissionConst;
use Illuminate\Http\Request;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Role;

class RolController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:' . PermissionConst::GESTION_ROLES);
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $nombre = $request->input('name');
        $company = $request->input('company');


        $roles = Rol::with("company");

        if ($nombre) {
            $roles->where('name', '=', $nombre);
        }

        if ($company) {
            $roles->whereHas('company', function ($q) use ($company) {
                $q->where('id', '=', $company);
            });
        }


        return response()->json($roles->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rol = new Rol($data);
        $rol->save();

        return response()->json($rol, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $rol = Rol::find($id);

        return response()->json($rol);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $data = $request->all();
        $rol = Rol::findOrFail($id);
        $rol->fill($data);
        $rol->save();

        return response()->json($rol);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();

        return response()->json([], 204);
    }

    // public function index()
    // {
    //     $roles = Rol::with('company')->get();

    //     return response()->json($roles);
    // }
}
