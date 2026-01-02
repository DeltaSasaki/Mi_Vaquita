<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    // 1. Listar Usuarios
public function index(Request $request)
    {
        $query = $request->input('q');

        $users = User::query()
            ->when($query, function ($q) use ($query) {
                return $q->where('name', 'LIKE', "%{$query}%")
                         ->orWhere('email', 'LIKE', "%{$query}%")
                         ->orWhere('cedula', 'LIKE', "%{$query}%"); // <--- Agregamos búsqueda por Cédula
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'query'));
    }
    // 2. Eliminar Usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // SEGURIDAD: No permitir que el admin se borre a sí mismo
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta mientras estás logueado.');
        }

        $user->delete();
        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    // 3. (Extra) Convertir a Admin / Quitar Admin
    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);

        // SEGURIDAD: No quitarse el admin a uno mismo
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes quitarte el rol de admin a ti mismo.');
        }

        // Invertimos el valor (si es true pasa a false, y viceversa)
        $user->is_admin = !$user->is_admin;
        $user->save();

        $status = $user->is_admin ? 'ahora es Administrador.' : 'ya no es Administrador.';
        return back()->with('success', 'El usuario ' . $user->name . ' ' . $status);
    }
}