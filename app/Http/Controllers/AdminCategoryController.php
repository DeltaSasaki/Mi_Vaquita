<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = true; // Por defecto activa

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = '/storage/' . $path;
        }

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Categoría creada.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        $data = $request->all();
        
        if($category->name != $request->name){
            $data['slug'] = Str::slug($request->name);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image'] = '/storage/' . $path;
        } else {
            $data['image'] = $category->image;
        }

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        // Validamos si tiene productos antes de borrar para no romper la tienda
        if($category->products()->count() > 0){
            return back()->with('error', 'No puedes borrar una categoría que tiene productos asociados.');
        }
        
        $category->delete();
        return back()->with('success', 'Categoría eliminada.');
    }
}