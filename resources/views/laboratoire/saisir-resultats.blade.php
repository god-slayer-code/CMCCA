<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saisie Résultats — {{ $demande->patiente->nom_complet }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; margin: 20px; }
        .entete { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .entete h1 { font-size: 16px; margin: 0; }
        .entete p { margin: 2px 0; color: #555; font-size: 12px; }
        .categorie { background: #1e40af; color: white; padding: 6px 12px; font-weight: bold; margin-top: 15px; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        th { background: #f0f0f0; padding: 6px 8px; text-align: left; font-size: 12px; border: 1px solid #ddd; }
        td { padding: 5px 8px; border: 1px solid #ddd; font-size: 12px; }
        input[type="text"] { width: 100%; border: 1px solid #ccc; padding: 3px 6px; border-radius: 3px; font-size: 12px; }
        input[type="text"]:focus { outline: 2px solid #2563eb; }
        .hors-norme { color: #dc2626; font-weight: bold; }
        .normale { color: #666; }
        .btn-submit { background: #16a34a; color: white; padding: 10px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-top: 15px; }
        .btn-print { background: #2563eb; color: white; padding: 10px 30px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-top: 15px; margin-left: 10px; }
        .info-patient { background: #f0f9ff; border: 1px solid #bae6fd; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="entete">
        <h1>CLINIQUE MÉDICO-CHIRURGICALE DE LA CITÉ DE L'AIR (CMCCA)</h1>
        <p>BP : 1601 – Tél : 657 58 38 45 / 664 28 53 63 Conakry, République de Guinée</p>
        <p style="margin-top:6px; font-weight:bold; font-size:14px;">RAPPORT D'ANALYSE MÉDICALE</p>
    </div>

    <div class="info-patient">
        <strong>Examen n° :</strong> {{ str_pad($demande->id, 5, '0', STR_PAD_LEFT) }} &nbsp;|&nbsp;
        <strong>Date :</strong> {{ now()->format('d/m/Y') }} &nbsp;|&nbsp;
        <strong>Patient :</strong> {{ $demande->patiente->nom_complet }} &nbsp;|&nbsp;
        <strong>Dossier :</strong> {{ $demande->patiente->numero_dossier }} &nbsp;|&nbsp;
        <strong>Médecin prescripteur :</strong> Dr {{ $demande->demandePar->name ?? '-' }}
    </div>

    <form method="POST" action="{{ route('laboratoire.saisir-resultats', $demande) }}" class="no-print">
        @csrf

        @foreach($parametres as $categorie => $params)
        <div class="categorie">{{ $categorie }}</div>
        <table>
            <thead>
                <tr>
                    <th style="width:35%">Paramètre</th>
                    <th style="width:20%">Résultat</th>
                    <th style="width:15%">Unité</th>
                    <th style="width:30%">Valeurs normales</th>
                </tr>
            </thead>
            <tbody>
                @foreach($params as $parametre)
                <tr>
                    <td>{{ $parametre->nom }}</td>
                    <td>
                        <input type="text"
                            name="valeurs[{{ $parametre->id }}]"
                            value="{{ $resultats[$parametre->id]->valeur ?? '' }}"
                            class="{{ isset($resultats[$parametre->id]) && $resultats[$parametre->id]->hors_norme ? 'hors-norme' : '' }}">
                    </td>
                    <td class="normale">{{ $parametre->unite ?? '' }}</td>
                    <td class="normale">{{ $parametre->normale_texte ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach

        <div class="no-print" style="margin-top:20px;">
            <button type="submit" class="btn-submit">
                ✅ Enregistrer les résultats
            </button>
            <button type="button" class="btn-print" onclick="imprimerResultats()">
                🖨 Imprimer
            </button>
        </div>

    </form>

    {{-- Vue impression --}}
    <div id="vue-impression" style="display:none;">
        @foreach($parametres as $categorie => $params)
        <div class="categorie">{{ $categorie }}</div>
        <table>
            <thead>
                <tr>
                    <th style="width:35%">Paramètre</th>
                    <th style="width:20%">Résultat</th>
                    <th style="width:15%">Unité</th>
                    <th style="width:30%">Valeurs normales</th>
                </tr>
            </thead>
            <tbody>
                @foreach($params as $parametre)
                <tr>
                    <td>{{ $parametre->nom }}</td>
                    <td class="{{ isset($resultats[$parametre->id]) && $resultats[$parametre->id]->hors_norme ? 'hors-norme' : '' }}">
                        {{ $resultats[$parametre->id]->valeur ?? '' }}
                    </td>
                    <td class="normale">{{ $parametre->unite ?? '' }}</td>
                    <td class="normale">{{ $parametre->normale_texte ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach

        <div style="margin-top:30px; display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div style="border-top:1px solid #333; padding-top:5px; text-align:center; font-size:11px;">
                <p>Signature du Laborantin</p>
                <br><br>
                <p>{{ auth()->user()->name }}</p>
            </div>
            <div style="border-top:1px solid #333; padding-top:5px; text-align:center; font-size:11px;">
                <p>Cachet de la clinique</p>
            </div>
        </div>
    </div>

    <script>
        function imprimerResultats() {
            // Copier les valeurs saisies dans la vue impression
            const inputs = document.querySelectorAll('input[name^="valeurs"]');
            inputs.forEach(input => {
                const parametreId = input.name.match(/\[(\d+)\]/)[1];
                const cellule = document.querySelector(`#impression-${parametreId}`);
                if (cellule) cellule.textContent = input.value;
            });

            document.querySelector('form').style.display = 'none';
            document.getElementById('vue-impression').style.display = 'block';
            window.print();
            document.querySelector('form').style.display = 'block';
            document.getElementById('vue-impression').style.display = 'none';
        }
    </script>

</body>
</html> 
