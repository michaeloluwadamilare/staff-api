<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;






class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with('role')->get();
        return response()->json(['status' => 'success', 'employees' => $employees], Response::HTTP_OK);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|max:255',
                'role_id' => 'required|exists:roles,id',
                'status' => 'required|in:Fired,Employed',
                'email' => ['string', 'lowercase', 'email', 'max:255', 'unique:'.Employee::class],
            ]);
        } catch (ValidationException $e) {
            // Return a JSON response with validation errors and status code 422
            return response()->json([
                'status' => 'error',
                'error' => 'Validation Error',
                'message' => $e->validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $employee = new Employee();
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->status = $request->status;
        $employee->role_id = $request->role_id;


        $employee->save();

        return response()->json(['status' => 'success', 'employee' => $employee], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
        }
    
        return response()->json(['status' => 'success', 'employee' => $employee], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::find($id);

        if(!$employee){
            return response()->json(['status' => 'error', 'message' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            // Validate the incoming request data
            $request->validate([
                'name' => 'required|unique:roles|max:255',
                'role_id' => 'required|exists:roles,id',
                'status' => 'required|in:Fired,Employed',
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255',Rule::unique('employees')->ignore($id)],
            ]);
        } catch (ValidationException $e) {
            // Return a JSON response with validation errors and status code 422
            return response()->json([
                'status' => 'error',
                'error' => 'Validation Error',
                'message' => $e->validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->status = $request->status;
        $employee->role_id = $request->role_id;


        $employee->save();
        
        return response()->json(['status' => 'success', 'message' => 'Employee updated successfully', 'employee' => $employee], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['status' => 'error', 'message' => 'Employee not found'], Response::HTTP_NOT_FOUND);
        }

        $employee->delete();
        return response()->json(['status' => 'success', 'message' => 'Employee deleted'], Response::HTTP_OK);
    }
}
