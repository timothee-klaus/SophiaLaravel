<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des Paiements</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #334155; line-height: 1.5; font-size: 11px; }
        .header { border-bottom: 2px solid #1e3a8a; padding-bottom: 20px; margin-bottom: 30px; }
        .school-name { font-size: 24px; font-weight: bold; color: #1e3a8a; margin: 0; }
        .report-title { font-size: 18px; color: #64748b; margin-top: 5px; text-transform: uppercase; letter-spacing: 1px; }
        .meta { margin-bottom: 20px; }
        .meta-item { margin-bottom: 4px; }
        .meta-label { font-weight: bold; color: #475569; width: 120px; display: inline-block; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; color: #475569; font-weight: bold; text-align: left; padding: 10px 8px; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; font-size: 9px; }
        td { padding: 10px 8px; border-bottom: 1px solid #f1f5f9; color: #334155; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; padding-top: 10px; border-top: 1px solid #f1f5f9; }
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-registration { background-color: #ecfdf5; color: #065f46; }
        .badge-tuition { background-color: #eff6ff; color: #1e40af; }
        .badge-miscellaneous { background-color: #fffbeb; color: #92400e; }
        .total-row { background-color: #f1f5f9; font-weight: bold; font-size: 12px; }
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
        <div style="font-size: 18px; font-weight: 900; color: #000; text-transform: uppercase; letter-spacing: 1px;">RAPPORT DES PAIEMENTS</div>
        <div style="font-size: 10px; color: #64748b; margin-top: 5px;">Généré le : {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="meta">
        <div class="meta-item"><span class="meta-label">Année Académique:</span> {{ $academicYear->name }}</div>
        @if($level)
            <div class="meta-item"><span class="meta-label">Classe:</span> {{ $level->name }}</div>
        @endif
        <div class="meta-item"><span class="meta-label">Période:</span> Du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
    </div>

    @php $total = 0; @endphp
    @foreach($payments->chunk(200) as $chunk)
        <table>
            <thead>
                <tr>
                    <th>Réf. Transaction</th>
                    <th>Date & Heure</th>
                    <th>Élève</th>
                    <th>Type de Frais</th>
                    <th>Classe</th>
                    <th class="text-right">Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($chunk as $payment)
                    @php $total += $payment->amount; @endphp
                    <tr>
                        <td class="font-bold">{{ $payment->transaction_id }}</td>
                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ mb_strtoupper($payment->student->last_name) }} {{ $payment->student->first_name }}</td>
                        <td>
                            @if($payment->type === 'registration') Inscription
                            @elseif($payment->type === 'miscellaneous') Frais Divers
                            @else Scolarité @if($payment->installment_number)(T{{ $payment->installment_number }})@endif
                            @endif
                        </td>
                        <td>{{ $payment->student->enrollments->where('academic_year_id', $payment->academic_year_id)->first()?->level->name ?? '-' }}</td>
                        <td class="text-right font-bold">{{ number_format($payment->amount, 0, ',', ' ') }}</td>
                    </tr>
                @endforeach
                @if($loop->last)
                <tr class="total-row">
                    <td colspan="5" class="text-right">TOTAL GÉNÉRAL</td>
                    <td class="text-right">{{ number_format($total, 0, ',', ' ') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach

    <div class="footer">
        Sophia Student Management System &copy; {{ date('Y') }} - Document Officiel
    </div>
</body>
</html>
