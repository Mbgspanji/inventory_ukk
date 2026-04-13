<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request) {
        $categories = Category::withCount('items')
            ->when($request->name, function($query, $name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->when($request->division, function($query, $division) {
                return $query->where('division', 'like', "%{$division}%");
            })
            ->get();
        return view('categories.index', compact('categories'));
    }

    public function create() {
        return view('categories.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required', 'division' => 'required']);
        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category) {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category) {
        $request->validate(['name' => 'required', 'division' => 'required']);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category) {
        if ($category->items()->count() > 0) {
            return back()->with('error', 'Cannot delete category with active items.');
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
