<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trek;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminTrekController extends Controller
{
    /**
     * Display a listing of the treks.
     */
    public function index()
    {
        $treks = Trek::latest()->paginate(10);
        return view('admin.treks.index', compact('treks'));
    }

    /**
     * Show the form for creating a new trek.
     */
    public function create()
    {
        return view('admin.treks.create');
    }

    /**
     * Store a newly created trek in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'difficulty' => 'required|in:Easy,Moderate,Difficult,Extreme',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Active,Inactive',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('treks', 'public');
            $data['image'] = Storage::url($path);
        }

        Trek::create($data);

        return redirect()->route('admin.treks.index')->with('success', 'Trek created successfully.');
    }

    /**
     * Display the specified trek.
     */
    public function show(Trek $trek)
    {
        return view('admin.treks.show', compact('trek'));
    }

    /**
     * Show the form for editing the specified trek.
     */
    public function edit(Trek $trek)
    {
        return view('admin.treks.edit', compact('trek'));
    }

    /**
     * Update the specified trek in storage.
     */
    public function update(Request $request, Trek $trek)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'difficulty' => 'required|in:Easy,Moderate,Difficult,Extreme',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Active,Inactive',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($trek->image) {
                $oldPath = str_replace('/storage/', '', $trek->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('treks', 'public');
            $data['image'] = Storage::url($path);
        }

        $trek->update($data);

        return redirect()->route('admin.treks.index')->with('success', 'Trek updated successfully.');
    }

    /**
     * Remove the specified trek from storage.
     */
    public function destroy(Trek $trek)
    {
        if ($trek->image) {
            $oldPath = str_replace('/storage/', '', $trek->image);
            Storage::disk('public')->delete($oldPath);
        }
        $trek->delete();

        return redirect()->route('admin.treks.index')->with('success', 'Trek deleted successfully.');
    }
}
