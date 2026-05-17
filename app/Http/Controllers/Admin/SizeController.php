<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SizeController extends Controller
{
    /**
     * Display list of sizes.
     */
    public function index(): View
    {
        $sizes = Size::orderBy('sort_order')->get();
        return view('admin.sizes.index', compact('sizes'));
    }

    /**
     * Show form to create a new size.
     */
    public function create(): View
    {
        return view('admin.sizes.create');
    }

    /**
     * Store a new size.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'display_name' => ['required', 'string', 'max:100'],
            'price_adjustment' => ['required', 'numeric'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Size::create($validated);

        return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully!');
    }

    /**
     * Show form to edit a size.
     */
    public function edit(Size $size): View
    {
        return view('admin.sizes.edit', compact('size'));
    }

    /**
     * Update a size.
     */
    public function update(Request $request, Size $size): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'display_name' => ['required', 'string', 'max:100'],
            'price_adjustment' => ['required', 'numeric'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $size->update($validated);

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully!');
    }

    /**
     * Delete a size.
     */
    public function destroy(Size $size): RedirectResponse
    {
        $size->delete();

        return redirect()->route('admin.sizes.index')->with('success', 'Size deleted successfully!');
    }
}
