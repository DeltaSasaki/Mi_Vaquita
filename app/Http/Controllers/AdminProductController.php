<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    // 1. Mostrar lista de productos
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // 2. Mostrar formulario de crear
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    // 3. Guardar el producto en BD
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        $data = $request->all();

        // Generar Slug automático (ej: T-Bone Steak -> t-bone-steak)
        $data['slug'] = \Illuminate\Support\Str::slug($request->name) . '-' . time();

        // Subir Imagen (Si existe)
        if ($request->hasFile('image')) {
            // Guardamos en la carpeta 'public/products'
            $path = $request->file('image')->store('products', 'public');
            // Guardamos la URL completa para acceder fácil luego
            $data['image'] = '/storage/' . $path;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado correctamente');
    }
    
    // 4. Eliminar producto
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Borrar imagen del disco si existe
        if ($product->image) {
            $oldPath = str_replace('/storage/', '', $product->image);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();
        return back()->with('success', 'Producto eliminado.');
    }
    
    // 5. Mostrar formulario de edición
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // 6. Actualizar en BD
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240' // nullable porque al editar no es obligatoria
        ]);

        $data = $request->all();
        
        // Actualizar Slug solo si cambia el nombre
        if($product->name != $request->name){
             $data['slug'] = Str::slug($request->name) . '-' . time();
        }

        // Lógica de Imagen
        if ($request->hasFile('image')) {
            // Borrar imagen vieja
            if ($product->image) {
                $oldPath = str_replace('/storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }
            
            // Subir nueva
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = '/storage/' . $path;
        } else {
            // Si no sube imagen, mantenemos la que tenía
            $data['image'] = $product->image;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente');
    }
}