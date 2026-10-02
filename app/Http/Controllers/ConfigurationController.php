<?php

namespace App\Http\Controllers;

use App\Models\ConfigurationClinique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller
{
    public function index()
    {
        $config = ConfigurationClinique::get();
        return view('configuration.index', compact('config'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nom_clinique'  => 'required|string|max:150',
            'telephone_1'   => 'required|string|max:20',
            'adresse'       => 'required|string|max:255',
            'logo'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'email'         => 'nullable|email',
        ]);

        $config = ConfigurationClinique::get();
        $donnees = $request->except(['logo', '_token']);

        if ($request->hasFile('logo')) {
            if ($config->logo) {
                Storage::delete('public/' . $config->logo);
            }
            $donnees['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $donnees['modifie_par'] = auth()->id();
        $config->update($donnees);

        return back()->with('success', 'Configuration mise à jour.');
    }
}