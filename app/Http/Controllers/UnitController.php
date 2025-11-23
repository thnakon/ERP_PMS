<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        return response()->json(Unit::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string', 'abbreviation' => 'nullable|string']);
        $unit = Unit::create($validated);
        return response()->json($unit, 201);
    }

    public function update(Request $request, Unit $unit)
    {
        $unit->update($request->all());
        return response()->json($unit);
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return response()->json(null, 204);
    }
}
