<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Http\Resources\RoleResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;






use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleLists = Role::all();
        return response()->json(['status' => 'success', 'role' => $roleLists], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
       
        try {
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|unique:roles|max:255',
            ]);
        } catch (ValidationException $e) {
            // Return a JSON response with validation errors and status code 422
            return response()->json([
                'status' => 'error',
                'error' => 'Validation Error',
                'message' => $e->validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $role = new Role();
        $role->name = $request->name;

        $role = Role::create([
            'name' => $request->name,
        ]);

        return response()->json(['status' => 'success', 'role' => $role], Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['status' => 'error', 'message' => 'Role not found'], 404);
        }
    
        return response()->json(['status' => 'success', 'role' => $role], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);

        if(!$role){
            return response()->json(['status' => 'error', 'message' => 'Role not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            ]);
        } catch (ValidationException $e) {
            // Return a JSON response with validation errors and status code 422
            return response()->json([
                'status' => 'error',
                'error' => 'Validation Error',
                'message' => $e->validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $role->name = $request->name;


        $role->save();
        
        return response()->json(['status' => 'success','message' => 'Role updated successfully', 'role' => $role], 200);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['status' => 'error', 'message' => 'Role not found'], Response::HTTP_NOT_FOUND);
        }

        $role->delete();
        return response()->json(['status' => 'success','message' => 'Role deleted'], Response::HTTP_OK);
    }
}
