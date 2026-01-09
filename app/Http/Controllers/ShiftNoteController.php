<?php

namespace App\Http\Controllers;

use App\Models\ShiftNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftNoteController extends Controller
{
    /**
     * Display a listing of shift notes.
     */
    public function index()
    {
        $notes = ShiftNote::with('user')
            ->orderBy('is_pinned', 'desc')
            ->latest()
            ->get();

        return view('shift-notes.index', compact('notes'));
    }

    /**
     * Store a newly created shift note.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'color' => 'required|string|in:yellow,pink,blue,green,purple',
        ]);

        ShiftNote::create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'color' => $validated['color'],
            'is_pinned' => $request->has('is_pinned'),
        ]);

        return redirect()->back()->with('success', __('shift_notes.created'));
    }

    /**
     * Update the specified shift note.
     */
    public function update(Request $request, ShiftNote $shift_note)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'color' => 'required|string|in:yellow,pink,blue,green,purple',
        ]);

        $shift_note->update([
            'content' => $validated['content'],
            'color' => $validated['color'],
            'is_pinned' => $request->has('is_pinned'),
        ]);

        return redirect()->back()->with('success', __('shift_notes.updated'));
    }

    /**
     * Remove the specified shift note.
     */
    public function destroy(ShiftNote $shift_note)
    {
        $shift_note->delete();

        return redirect()->back()->with('success', __('shift_notes.deleted'));
    }

    /**
     * Toggle pin status.
     */
    public function togglePin(ShiftNote $shift_note)
    {
        $shift_note->update([
            'is_pinned' => !$shift_note->is_pinned
        ]);

        return redirect()->back();
    }
}
