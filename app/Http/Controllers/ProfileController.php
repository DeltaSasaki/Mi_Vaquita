<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Mostrar la vista del perfil con los datos del usuario y sus pedidos.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Traemos los pedidos de este usuario (del más nuevo al más viejo)
        // Usamos 'orders' asumiendo que ya definiste la relación en el Modelo User
        // Si aún no tienes pedidos, no dará error, solo traerá una lista vacía.
        $orders = $user->orders()->latest()->get();

        return view('profile.index', compact('user', 'orders'));
    }

    /**
     * Actualizar información personal (Nombre, Cédula, Email, Teléfono, Dirección).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validaciones
        $request->validate([
            'name'    => 'required|string|max:255',
            // La cédula y el email deben ser únicos, pero ignorando al usuario actual (para que no de error si no los cambia)
            'cedula'  => 'nullable|string|max:20|unique:users,cedula,' . $user->id,
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // 2. Actualizar datos
        $user->update([
            'name'    => $request->name,
            'cedula'  => $request->cedula,  // <--- Aquí guardamos la Cédula
            'email'   => $request->email,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Tus datos se han actualizado correctamente.');
    }

    /**
     * Cambiar la contraseña.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed', // 'confirmed' busca el campo password_confirmation
        ]);

        // Verificar que la contraseña actual sea la correcta
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no coincide.']);
        }

        // Encriptar y guardar la nueva
        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Contraseña cambiada con éxito.');
    }
}