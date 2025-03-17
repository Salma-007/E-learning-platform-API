<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        // return response()->json($permissions);
        return response()->json(PermissionResource::collection($permissions));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $permission = Permission::create(['name' => $request->name]);

        return response()->json($permission, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $permission = Permission::findOrFail($id);

        $permission->name = $request->name;
        $permission->save();

        return response()->json($permission);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        $permission->delete();

        return response()->json(['message' => 'Permission supprimée avec succès']);
    }

    public function show($id)
    {
        try {
            $permission = Permission::find($id);
            if (!$permission) {
                throw new ModelNotFoundException("permission non trouvé avec l'ID : $id");
            }
            return response()->json(["id" => $permission->id,"name" => $permission->name]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur s\'est produite lors de la récupération du cours.'], 500);
        }
    }
}
