<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class AddOnController extends Controller
{
    /**
     * Display list of add-ons.
     */
    public function index(): View
    {
        $addOns = AddOn::orderBy('category')->orderBy('sort_order')->paginate(20);
        return view('admin.addons.index', compact('addOns'));
    }

    /**
     * Show form to create a new add-on.
     */
    public function create(): View
    {
        return view('admin.addons.create');
    }

    /**
     * Store a new add-on.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('add-ons', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        AddOn::create($validated);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on created successfully!');
    }

    /**
     * Show form to edit an add-on.
     */
    public function edit(AddOn $addOn): View
    {
        return view('admin.addons.edit', compact('addOn'));
    }

    /**
     * Update an add-on.
     */
    public function update(Request $request, AddOn $addOn): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($addOn->image) {
                Storage::disk('public')->delete($addOn->image);
            }
            $validated['image'] = $request->file('image')->store('add-ons', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $addOn->update($validated);

        return redirect()->route('admin.addons.index')->with('success', 'Add-on updated successfully!');
    }

    /**
     * Delete an add-on.
     */
    public function destroy(AddOn $addOn): RedirectResponse
    {
        // Delete image
        if ($addOn->image) {
            Storage::disk('public')->delete($addOn->image);
        }

        $addOn->delete();

        return redirect()->route('admin.addons.index')->with('success', 'Add-on deleted successfully!');
    }
}
