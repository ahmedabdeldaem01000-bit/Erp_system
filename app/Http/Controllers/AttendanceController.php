<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Service\Hr\Attendance\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected $attendanceService;
    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendance = $this->attendanceService->index();
        return response()->json([
            "message" => 'success',
            "data" => $attendance
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
    public function store(StoreAttendanceRequest $request)
    {

     
     $attendance = $this->attendanceService->store($request->validated());

        return response()->json([
            'message' => 'Attendance created successfully',
            'attendance' => $attendance,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = $this->attendanceService->show($id);
        return $attendance;
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
    public function update(UpdateAttendanceRequest $request, string $id)
    {
        
        $attendance = $this->attendanceService->update($request->validated(), $id);

        return response()->json([
            'message' => 'attendance updated successfully',
            'attendance' => $attendance,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->attendanceService->delete($id);

        return response()->json([
            'message' => 'Attendance deleted successfully',
        ]);
    }
}
