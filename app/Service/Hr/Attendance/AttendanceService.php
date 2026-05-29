<?php
namespace App\Service\Hr\Attendance;

use App\Models\Attendance;

class AttendanceService
{
    public function index()
    {
        $attendance = Attendance::get();
        return $attendance;
    }
    public function store($request)
    {
     

        $attendance = Attendance::create([
            'employee_id' => $request['employee_id'],
            'date' => $request['date'],
            'check_in' => $request['check_in'],
            'check_out' => $request['check_out'],
            'status' => $request['status'],
            'notes' => $request['notes'],
        ]);

        return $attendance;
    }

    public function update($request, $id)
    {
        $attendance = Attendance::findOrFail($id);

 


        $attendance->update([
            'employee_id' => $request['employee_id'],
            'date' => $request['date'],
            'check_in' => $request['check_in'],
            'check_out' => $request['check_out'],
            'status' => $request['status'],
            'notes' => $request['notes'],
        ]);

        return $attendance;
    }

    public function show(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        return $attendance;
    }

    public function delete($id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->delete();

        return true;
    }
}