<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    public function index()
    {
        $utilisateurs = User::with('role')->orderBy('name')->get();
        return view('utilisateurs.index', compact('utilisateurs'));
    }

    public function create()
    {
        $roles = Role::orderBy('nom')->get();
        return view('utilisateurs.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
            'actif'    => true,
        ]);

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Compte créé avec succès.');
    }

    public function edit(User $utilisateur)
    {
        $roles = Role::orderBy('nom')->get();
        return view('utilisateurs.edit', compact('utilisateur', 'roles'));
    }

    public function update(Request $request, User $utilisateur)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|unique:users,email,' . $utilisateur->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $donnees = [
            'name'    => $request->name,
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $donnees['password'] = Hash::make($request->password);
        }

        $utilisateur->update($donnees);

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Compte mis à jour.');
    }

    public function toggleActif(User $utilisateur)
    {
        if ($utilisateur->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $utilisateur->update(['actif' => !$utilisateur->actif]);

        return back()->with('success', $utilisateur->actif ? 'Compte réactivé.' : 'Compte désactivé.');
    }
}