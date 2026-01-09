<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'sort_order');

        $query = Category::with(['parent'])->withCount('products');

        // Apply sorting
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'created_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('sort_order')->orderBy('name');
                break;
        }

        $categories = $query->paginate(12)->withQueryString();

        // Stats
        $stats = [
            'total' => Category::count(),
            'active' => Category::active()->count(),
            'subs' => Category::whereNotNull('parent_id')->count(),
            'main' => Category::whereNull('parent_id')->count(),
        ];

        // For parent selection
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('categories.index', compact('categories', 'stats', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'color_code' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['icon_path'] = $path;
        }

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', __('categories.created'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_th' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id|different:id',
            'description' => 'nullable|string',
            'color_code' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['icon_path'] = $path;
        }

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', __('categories.updated'));
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children']);

        // Get products in this category with pagination
        $products = $category->products()
            ->with('category')
            ->orderBy('name')
            ->paginate(20);

        return view('categories.show', compact('category', 'products'));
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', __('categories.deleted'));
    }
}
