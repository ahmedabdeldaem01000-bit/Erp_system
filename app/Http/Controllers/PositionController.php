<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\Position\PositionService;

class PositionController extends Controller
{
    protected $positionService;
    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    public function index()
    {
        $position = $this->positionService->index();
        return response()->json([
            'message' => 'success',
            "position" => $position
        ]);
    }

    public function show(string $id)
    {
        $position = $this->positionService->show($id);
        return response()->json([
            'message' => 'success',
            "position" => $position
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->positionService->store($request);
        return response()->json([
            'message' => 'Position Created successfully',
            'data' => $data
        ]);
    }

    public function update(Request $request, string $id)
    {
        $data = $this->positionService->update($request, $id);
        return response()->json([
            'message' => 'Position Updated Successfully',
            'data' => $data
        ]);
    }

    public function destroy(string $id)
    {
        $this->positionService->destroy($id);
        return response()->json([
            'message' => 'Position Deleted Successfully'
        ]);
    }
}


