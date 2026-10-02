<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    public function index()
    {
        $annonces = Annonce::orderBy('date_debut', 'desc')->get();
        return view('annonces.index', compact('annonces'));
    }

    public function create()
    {
        return view('annonces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'      => 'required|string|max:150',
            'contenu'    => 'required|string',
            'image'      => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'date_debut' => 'nullable|date',
            'date_fin'   => 'nullable|date|after_or_equal:date_debut',
        ]);

        $donnees = $request->only(['titre', 'contenu', 'date_debut', 'date_fin']);
        $donnees['created_by'] = auth()->id();
        $donnees['actif'] = true;

        if ($request->hasFile('image')) {
            $donnees['image'] = $request->file('image')->store('annonces', 'public');
        }

        Annonce::create($donnees);

        return redirect()->route('annonces.index')->with('success', 'Annonce créée.');
    }

    public function toggleActif(Annonce $annonce)
    {
        $annonce->update(['actif' => !$annonce->actif]);
        return back()->with('success', $annonce->actif ? 'Annonce réactivée.' : 'Annonce désactivée.');
    }

    public function destroy(Annonce $annonce)
    {
        if ($annonce->image) {
            Storage::delete('public/' . $annonce->image);
        }
        $annonce->delete();
        return back()->with('success', 'Annonce supprimée.');
    }
}