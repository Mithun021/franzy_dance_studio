<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LevelController extends Controller
{
    /**
     * Display Level Listing
     */
    public function index()
    {
        $levels = Level::orderBy('id', 'ASC')->get();

        return view('backend.level.index', compact('levels'));
    }

    /**
     * Store New Level
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:levels,name',
        ]);

        Level::create([
            'name' => $request->name,
        ]);

        return redirect()->route('level.index')
            ->with('success', 'Level added successfully.');
    }

    /**
     * Edit Level
     */
    public function edit($id)
    {
        $level = Level::findOrFail($id);

        return view('backend.level.edit', compact('level'));
    }

    /**
     * Update Level
     */
    public function update(Request $request, $id)
    {
        $level = Level::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('levels')->ignore($level->id),
            ],
        ]);

        $level->update([
            'name' => $request->name,
        ]);

        return redirect()->route('level.index')
            ->with('success', 'Level updated successfully.');
    }

    /**
     * Delete Level
     */
    public function destroy($id)
    {
        $level = Level::findOrFail($id);

        $level->delete();

        return redirect()->route('level.index')
            ->with('success', 'Level deleted successfully.');
    }
}
