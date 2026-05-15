<?php

namespace App\Service\Position;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
 
class PositionService
{
    public function index()
    {
        return Position::get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:positions,name',
        ]);

        return Position::create([
            'name' => $request->name,

        ]);
    }

    public function show(string $id)
    {
        return Position::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {

        $position = Position::findOrFail($id);

        $position->update([
            'name' => $request->name,
        ]);

        return $position;
    }

    public function destroy(string $id)
    {
        $position = Position::findOrFail($id);

        $position->delete();

        return true;
    }


}