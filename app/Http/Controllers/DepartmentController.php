<?php

namespace App\Http\Controllers;

use App\Service\Department\DepartmentService;
use Illuminate\Http\Request;
 

class DepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(DepartmentService $departmentService){
        $this->departmentService=$departmentService;

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $department=$this->departmentService->index();
        return response()->json([
        "message"=>'success',
        "data"=>$department
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
    {
        $department = $this->departmentService->store($request);

        return response()->json([
            'message' => 'Department created successfully',
            'department' => $department,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $department=$this->departmentService->show($id);
         return $department;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $department = $this->departmentService->update($request, $id);

    return response()->json([
        'message' => 'Department updated successfully',
        'department' => $department,
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
          $this->departmentService->delete($id);

    return response()->json([
        'message' => 'Department deleted successfully',
    ]);
    }
}
