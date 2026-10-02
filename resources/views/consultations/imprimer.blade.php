<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche — {{ $consultation->patiente->nom_complet }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; margin: 20px; }
        .entete { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .entete h1 { font-size: 18px; margin: 0; }
        .entete p { margin: 2px 0; color: #555; }
        .section { margin-bottom: 15px; }
        .section h3 { font-size: 14px; background: #f0f0f0; padding: 5px 10px; margin: 0 0 8px 0; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding: 0 10px; }
        .item label { color: #666; font-size: 11px; display: block; }
        .item span { font-weight: bold; }
        .footer { margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .signature { border-top: 1px solid #333; padding-top: 5px; text-align: center; font-size: 11px; }
        .btn { padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; color: white; margin: 5px; }
        .btn-blue { background: #2563eb; }
        .btn-green { background: #16a34a; }
        .confidentiel { background: #fee2e2; border: 1px solid #ef4444; padding: 5px 10px; color: #991b1b; font-size: 11px; text-align: center; margin-bottom: 10px; }
        @media print {
            .btn, .boutons { display: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    {{-- Boutons de sélection --}}
    <div class="boutons no-print" style="text-align:center; margin-bottom:20px;">
        <button class="btn btn-blue" onclick="imprimerOrdonnance()">
            🖨 Imprimer Ordonnance (pour la patiente)
        </button>
        <button class="btn btn-green" onclick="imprimerFicheMedicale()">
            🖨 Imprimer Fiche Médicale (confidentielle)
        </button>
    </div>

   {{-- EN-TÊTE --}}
<div class="entete">
    @if(clinique()->logo_url)
        <img src="{{ clinique()->logo_url }}" alt="Logo" style="height:50px; margin-bottom:5px;">
    @endif
    <h1>{{ strtoupper(clinique()->nom_clinique) }}</h1>
    <p>
        @if(clinique()->bp)BP: {{ clinique()->bp }} — @endif
        Tél: {{ clinique()->telephone_1 }}{{ clinique()->telephone_2 ? ' / ' . clinique()->telephone_2 : '' }}
        — {{ clinique()->ville }}, {{ clinique()->pays }}
    </p>
    <p style="margin-top:8px; font-size:15px; font-weight:bold;" id="titre_doc">
        ORDONNANCE D'EXAMENS
    </p>
</div>

    {{-- INFOS PATIENTE --}}
    <div class="section">
        <h3>Informations patiente</h3>
        <div class="grid">
            <div class="item">
                <label>Nom complet</label>
                <span>{{ $consultation->patiente->nom_complet }}</span>
            </div>
            <div class="item">
                <label>N° Dossier</label>
                <span>{{ $consultation->patiente->numero_dossier }}</span>
            </div>
            <div class="item">
                <label>Âge</label>
                <span>{{ $consultation->patiente->age ? $consultation->patiente->age . ' ans' : 'Non renseigné' }}</span>
            </div>
            <div class="item">
                <label>Groupe sanguin</label>
                <span>{{ $consultation->patiente->groupe_sanguin ?? 'Non renseigné' }}</span>
            </div>
            <div class="item">
                <label>Date</label>
                <span>{{ $consultation->created_at->format('d/m/Y à H:i') }}</span>
            </div>
            <div class="item">
                <label>Assurance</label>
                <span>{{ $consultation->patiente->est_assuree ? '✅ Assurée' : 'Non assurée' }}</span>
            </div>
        </div>
    </div>

    {{-- ACTE PRESCRIT --}}
    <div class="section">
        <h3>Acte prescrit</h3>
        <div class="grid">
            <div class="item">
                <label>Acte</label>
                <span>{{ $consultation->service->nom ?? 'Non renseigné' }}</span>
            </div>
            <div class="item">
                <label>Prix</label>
                <span>{{ $consultation->service ? number_format($consultation->service->prix_standard, 0, ',', ' ') . ' GNF' : '-' }}</span>
            </div>
            @if($consultation->patiente->est_assuree && $consultation->patiente->assurances->isNotEmpty())
            <div class="item">
                <label>Taux assurance</label>
                <span>{{ $consultation->patiente->assurances->first()->taux_couverture }}%</span>
            </div>
            <div class="item">
                <label>Patiente paie</label>
                <span>
                    @php
                        $prix = $consultation->service->prix_standard ?? 0;
                        $taux = $consultation->patiente->assurances->first()->taux_couverture ?? 0;
                        $part = $prix - ($prix * $taux / 100);
                    @endphp
                    {{ number_format($part, 0, ',', ' ') }} GNF
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- SECTION MÉDICALE (cachée par défaut pour ordonnance) --}}
    <div class="section" id="section_medicale" style="display:none;">
        <div class="confidentiel">⚠ DOCUMENT MÉDICAL CONFIDENTIEL — NE PAS REMETTRE AU PATIENT</div>
        <h3>Informations médicales</h3>
        <div style="padding: 0 10px;">
            @if($consultation->poids || $consultation->tension || $consultation->temperature)
            <div class="grid" style="margin-bottom:8px;">
                @if($consultation->poids)
                <div class="item"><label>Poids</label><span>{{ $consultation->poids }} kg</span></div>
                @endif
                @if($consultation->tension)
                <div class="item"><label>Tension</label><span>{{ $consultation->tension }}</span></div>
                @endif
                @if($consultation->temperature)
                <div class="item"><label>Température</label><span>{{ $consultation->temperature }} °C</span></div>
                @endif
            </div>
            @endif
            @if($consultation->motif)
            <p><strong>Motif :</strong> {{ $consultation->motif }}</p>
            @endif
            @if($consultation->diagnostic)
            <p><strong>Diagnostic :</strong> {{ $consultation->diagnostic }}</p>
            @endif
            @if($consultation->notes)
            <p><strong>Notes :</strong> {{ $consultation->notes }}</p>
            @endif
        </div>
    </div>

    {{-- SIGNATURES --}}
    <div class="footer">
        <div class="signature">
            <p>Signature du médecin</p>
            <br><br>
            <p>Dr. {{ $consultation->medecin->name }}</p>
        </div>
        <div class="signature">
            <p>Cachet de la clinique</p>
            <br><br>
        </div>
    </div>

    <script>
        function imprimerOrdonnance() {
            document.getElementById('titre_doc').textContent = 'ORDONNANCE D\'EXAMENS';
            document.getElementById('section_medicale').style.display = 'none';
            window.print();
        }

        function imprimerFicheMedicale() {
            document.getElementById('titre_doc').textContent = 'FICHE MÉDICALE CONFIDENTIELLE';
            document.getElementById('section_medicale').style.display = 'block';
            window.print();
        }
    </script>

</body>
</html>