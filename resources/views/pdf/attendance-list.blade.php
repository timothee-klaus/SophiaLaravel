<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche d'Émargement - {{ $level->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #334155; line-height: 1.5; font-size: 11px; }
        .meta { margin-bottom: 20px; }
        .meta-item { display: inline-block; margin-right: 30px; margin-bottom: 4px; }
        .meta-label { font-weight: bold; color: #475569; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; color: #475569; font-weight: bold; text-align: left; padding: 10px 8px; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; font-size: 9px; }
        td { padding: 10px 8px; border-bottom: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; padding-top: 10px; border-top: 1px solid #f1f5f9; }
        .strikethrough { text-decoration: line-through; color: #94a3b8; }
        .badge-warning { color: #dc2626; font-weight: bold; font-size: 10px; }
        .badge-success { color: #16a34a; font-weight: bold; font-size: 10px; }
    </style>
</head>
<body>
    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
        <div style="font-size: 24px; font-weight: 900; color: #000; text-transform: uppercase; letter-spacing: 1px; line-height: 1;">INSTITUT SCOLAIRE SOPHIA</div>
        <div style="font-size: 14px; font-style: italic; color: #334155; margin-top: 2px; font-family: 'Times New Roman', serif;">&laquo; Le Don De Dieu &raquo;</div>
        <div style="font-size: 9px; color: #000; margin-top: 6px; font-weight: 500;">Mitoyenneté du marché de Pain et de l’Etat-major de la Gendarmerie Maritime à Tsévié Quartier Dévé.</div>
        <div style="font-size: 9px; color: #000; margin-top: 4px;">
            <span style="font-style: italic; font-family: 'Times New Roman', serif; font-weight: bold; font-size: 11px;">Téléphone :</span> 90238084 / 99964949 &nbsp;&nbsp;&nbsp;&nbsp; 
            <span style="font-style: italic; font-family: 'Times New Roman', serif; font-weight: bold; font-size: 11px;">Courriel :</span> institutsophia98@gmail.com
        </div>
    </div>

    <div style="text-align: center; margin-bottom: 25px;">
        <div style="font-size: 18px; font-weight: 900; color: #000; text-transform: uppercase; letter-spacing: 1px;">FICHE D'ÉMARGEMENT</div>
        <div style="font-size: 10px; color: #64748b; margin-top: 5px;">Évaluations Officielles</div>
    </div>

    <div class="meta">
        <div class="meta-item"><span class="meta-label">Classe:</span> {{ $level->name }}</div>
        <div class="meta-item"><span class="meta-label">Cycle:</span> {{ ucfirst($level->cycle) }}</div>
        <div class="meta-item"><span class="meta-label">Année Académique:</span> {{ $academicYear->name }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">N°</th>
                <th width="15%">Matricule</th>
                <th width="40%">Nom et Prénom(s)</th>
                <th width="10%" class="text-center">Sexe</th>
                <th width="15%" class="text-center">Statut</th>
                <th width="15%" class="text-center">Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $index => $enrollment)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $enrollment->student->matricule }}</td>
                <td class="{{ !$enrollment->is_eligible ? 'strikethrough' : 'font-bold' }}">
                    {{ mb_strtoupper($enrollment->student->last_name) }} {{ $enrollment->student->first_name }}
                </td>
                <td class="text-center">{{ $enrollment->student->gender }}</td>
                <td class="text-center">
                    @if($enrollment->is_eligible)
                        <span class="badge-success">AUTORISÉ</span>
                        @if($enrollment->is_manually_unblocked)
                            <br><span style="font-size: 8px; color: #64748b; font-weight: normal;">(Déblocage manuel)</span>
                        @endif
                    @else
                        <span class="badge-warning">NON EN RÈGLE</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($enrollment->is_eligible)
                        <div style="height: 25px; border-bottom: 1px dotted #cbd5e1; width: 80%; margin: 0 auto;"></div>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sophia Student Management System &copy; {{ date('Y') }} - Fiche d'émargement générée le {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>

