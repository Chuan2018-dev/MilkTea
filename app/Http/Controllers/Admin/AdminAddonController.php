<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAddonController extends Controller
{
    /**
     * Display a listing of addons.
     */
    public function index(Request $request)
    {
        $query = Addon::query();

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $addons = $query->latest()->paginate(15);

        return view('admin.addons.index', compact('addons'));
    }

    /**
     * Show the form for creating a new addon.
     */
    public function create()
    {
        return view('admin.addons.create');
    }

    /**
     * Store a newly created addon.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->boolean('is_active', true);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $filename);
            $data['image'] = $filename;
        }

        Addon::create($data);

        return redirect()->route('admin.addons.index')
            ->with('success', 'Add-on created successfully!');
    }

    /**
     * Show the form for editing the addon.
     */
    public function edit(Addon $addon)
    {
        return view('admin.addons.edit', compact('addon'));
    }

    /**
     * Update the addon.
     */
    public function update(Request $request, Addon $addon)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->boolean('is_active', true);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($addon->image) {
                Storage::delete('public/products/' . $addon->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/products', $filename);
            $data['image'] = $filename;
        }

        $addon->update($data);

        return redirect()->route('admin.addons.index')
            ->with('success', 'Add-on updated successfully!');
    }

    /**
     * Remove the addon.
     */
    public function destroy(Addon $addon)
    {
        // Delete image
        if ($addon->image) {
            Storage::delete('public/products/' . $addon->image);
        }

        $addon->delete();

        return redirect()->route('admin.addons.index')
            ->with('success', 'Add-on deleted successfully!');
    }

    /**
     * Toggle addon status.
     */
    public function toggleStatus(Addon $addon)
    {
        $addon->update(['is_active' => !$addon->is_active]);

        $status = $addon->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Add-on {$status} successfully!");
    }
}
