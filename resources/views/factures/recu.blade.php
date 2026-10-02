<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu — {{ $facture->numero_facture }}</title>
    <style>
        @page { size: 80mm auto; margin: 0; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            width: 76mm;
            margin: 0 auto;
            padding: 8px;
            color: #000;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 6px 0; }
        .double-divider { border-top: 2px solid #000; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; margin: 2px 0; }
        .logo { max-height: 40px; margin-bottom: 4px; }
        .titre { font-size: 13px; font-weight: bold; margin: 4px 0; }
        .total-box { text-align: center; font-size: 14px; font-weight: bold; margin: 8px 0; padding: 6px 0; border-top: 1px dashed #000; border-bottom: 1px dashed #000; }
        .statut { text-align: center; font-size: 10px; margin-top: 4px; }
        .footer { text-align: center; font-size: 9px; margin-top: 10px; color: #333; }
        .btn { background: #2563eb; color: white; padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; font-family: Arial; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="center">
        @if(clinique()->logo_url)
            <img src="{{ clinique()->logo_url }}" class="logo">
        @endif
        <div class="bold">{{ strtoupper(clinique()->nom_clinique) }}</div>
        @if(clinique()->slogan)
            <div>{{ clinique()->slogan }}</div>
        @endif
        <div>{{ clinique()->ville }}, {{ clinique()->pays }}</div>
        <div>Tél: {{ clinique()->telephone_1 }}</div>
        @if(clinique()->bp)<div>BP: {{ clinique()->bp }}</div>@endif
    </div>

    <div class="divider"></div>

    <div class="center titre">REÇU DE PAIEMENT</div>
    <div class="center">N° {{ $facture->numero_facture }}</div>

    <div class="divider"></div>

    <div class="row"><span>Date</span><span>{{ $facture->created_at->format('d/m/Y H:i') }}</span></div>
    <div class="row"><span>Patiente</span><span>{{ $facture->patiente->nom_complet }}</span></div>
    <div class="row"><span>Dossier</span><span>{{ $facture->patiente->numero_dossier }}</span></div>

    <div class="divider"></div>

    <div class="row bold"><span>Service</span></div>
    <div>{{ $facture->service->nom ?? '-' }}</div>

    <div class="total-box">
        TOTAL : {{ number_format($facture->montant_final, 0, ',', ' ') }} GNF
    </div>

    @if($facture->estPayee())
        <div class="statut bold">✅ INTÉGRALEMENT PAYÉ</div>
    @else
        <div class="statut">
            Payé : {{ number_format($facture->paiements->sum('montant'), 0, ',', ' ') }} GNF<br>
            Reste : {{ number_format($facture->solde_du, 0, ',', ' ') }} GNF
        </div>
    @endif

    <div class="divider"></div>

    <div class="bold" style="font-size:10px;">Paiements :</div>
    @foreach($facture->paiements as $paiement)
        <div class="row" style="font-size:10px;">
            <span>{{ $paiement->created_at->format('d/m H:i') }}</span>
            <span>{{ number_format($paiement->montant, 0, ',', ' ') }} GNF</span>
        </div>
        <div style="font-size:9px; color:#555;">{{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }}</div>
    @endforeach

    <div class="double-divider"></div>

    <div class="footer">
        {{ clinique()->pied_de_page ?? 'Merci de votre confiance' }}
    </div>

    <div class="center no-print" style="margin-top:15px;">
        <button class="btn" onclick="window.print()">🖨 Imprimer</button>
    </div>

</body>
</html>