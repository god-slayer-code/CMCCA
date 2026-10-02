<?php

namespace App\Livewire;

use App\Services\DetectionDoublonsService;
use Livewire\Component;

class RechercheDoublons extends Component
{
    public $nom = '';
    public $prenom = '';
    public $doublonsPotentiels = [];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['nom', 'prenom']) && strlen($this->nom) >= 3) {
            $this->doublonsPotentiels = app(DetectionDoublonsService::class)
                ->rechercherDoublonsPotentiels($this->nom, $this->prenom);
        }
    }

    public function render()
    {
        return view('livewire.recherche-doublons');
    }
}