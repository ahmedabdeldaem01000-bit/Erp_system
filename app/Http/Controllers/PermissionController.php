<?php

namespace App\Http\Controllers;

use App\Service\Permission\PermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{


    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permission = $this->permissionService->index();
        return response()->json([
            'message' => 'successfully',
            'permissions' => $permission
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
            $permission = $this->permissionService->store($request);

    return response()->json([
        'message' => 'Permission Created Successfully',
        'permission' => $permission
    ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $permission = $this->permissionService->show($id);

    return response()->json([
        'permission' => $permission
    ]);
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
      $permission = $this->permissionService->update($request, $id);

    return response()->json([
        'message' => 'permission Updated',
        'permission' => $permission
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data= $this->permissionService->destroy($id);

    return response()->json([
        'message' => 'permission Deleted Successfully',
        'data'=>$data
    ]);
    }
}
