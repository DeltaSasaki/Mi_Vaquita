<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Product;

class AdminCouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $categories = Category::all();
        $products = Product::all();
        return view('admin.coupons.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code|uppercase',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date',
        ]);

        Coupon::create($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Cupón creado exitosamente.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $categories = Category::all();
        $products = Product::all();
        return view('admin.coupons.edit', compact('coupon', 'categories', 'products'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        
        $request->validate([
            'code' => 'required|unique:coupons,code,'.$coupon->id.'|uppercase',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
        ]);

        // Manejo de checkbox para booleano
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupón actualizado.');
    }

    public function destroy($id)
    {
        Coupon::destroy($id);
        return back()->with('success', 'Cupón eliminado.');
    }
}
