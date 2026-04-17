<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu de Paiement - {{ $payment->transaction_id }}</title>
    <style>
        * { margin: 0; padding: 0; }
        @page { margin: 0cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #334155; line-height: 1.2; font-size: 10px; margin: 0; padding: 0.5cm; }
        .receipt-wrapper { width: 100%; border: 1px solid #cbd5e1; border-collapse: collapse; table-layout: fixed; margin-bottom: 0.5cm; }
        .content-cell { padding: 20px; }
        
        .school-header { padding: 5px 0; margin-bottom: 15px; }
        .school-name { font-size: 16px; font-weight: 800; color: #1e3a8a; letter-spacing: 0.5px; }
        .school-motto { font-size: 9px; color: #64748b; font-style: italic; }
        .school-slogan { font-size: 11px; font-weight: bold; color: #1e3a8a; margin-top: 2px; }
        
        .receipt-label { font-size: 16px; font-weight: bold; color: #1e3a8a; }
        .copy-type { font-size: 9px; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
        .transaction-id { font-size: 12px; font-weight: bold; color: #ef4444; }
        
        .label-cell { font-weight: bold; color: #475569; width: 130px; padding: 5px 0; border-bottom: 1px dotted #e2e8f0; }
        .value-cell { color: #1e293b; font-weight: 500; padding: 5px 0; border-bottom: 1px dotted #e2e8f0; }
        
        .th-bg { background-color: #f8fafc; color: #475569; font-weight: bold; text-align: left; padding: 8px 10px; border-bottom: 2px solid #e2e8f0; text-transform: uppercase; font-size: 8px; }
        .td-border { padding: 10px; border-bottom: 1px solid #f1f5f9; }
        
        .amount-box { border: 2px solid #1e3a8a; padding: 12px; font-size: 18px; font-weight: bold; color: #1e3a8a; background-color: #f0f7ff; text-align: center; }
        .sig-box { text-align: center; padding-top: 30px; }
        .sig-line { width: 150px; height: 1px; border-top: 1px solid #334155; margin: 35px auto 5px auto; }
        
        .cut-line { width: 100%; border-top: 1px dashed #94a3b8; margin: 15px 0; position: relative; text-align: center; }
        .cut-icon { background: #fff; padding: 0 10px; font-size: 10px; color: #94a3b8; position: absolute; top: -7px; left: 45%; }
    </style>
</head>
<body>
    @php
        $copies = ['EXEMPLAIRE PARENT', 'EXEMPLAIRE ADMINISTRATION'];
    @endphp

    @foreach($copies as $index => $copy)
        <table class="receipt-wrapper">
            <tr>
                <td class="content-cell">
                    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px;">
                        <div style="font-size: 24px; font-weight: 900; color: #000; text-transform: uppercase; letter-spacing: 1px; line-height: 1;">INSTITUT SCOLAIRE SOPHIA</div>
                        <div style="font-size: 14px; font-style: italic; color: #334155; margin-top: 2px; font-family: 'Times New Roman', serif;">&laquo; Le Don De Dieu &raquo;</div>
                        <div style="font-size: 10px; color: #000; margin-top: 6px; font-weight: 500; font-family: 'Helvetica', sans-serif;">Mitoyenneté du marché de Pain et de l’Etat-major de la Gendarmerie Maritime à Tsévié Quartier Dévé.</div>
                        <div style="font-size: 10px; color: #000; margin-top: 4px;">
                            <span style="font-style: italic; font-family: 'Times New Roman', serif; font-weight: bold; font-size: 12px;">Téléphone :</span> 90238084 / 99964949 &nbsp;&nbsp;&nbsp;&nbsp; 
                            <span style="font-style: italic; font-family: 'Times New Roman', serif; font-weight: bold; font-size: 12px;">Courriel :</span> institutsophia98@gmail.com
                        </div>
                    </div>

                    <div style="text-align: center; margin-bottom: 15px;">
                        <div style="font-size: 22px; font-weight: 900; color: #000; text-transform: uppercase;">REÇU DE PAIEMENT</div>
                        <div style="font-size: 14px; font-weight: bold; color: #000; margin-top: 2px;">N° {{ $payment->transaction_id }}</div>
                        <div style="font-size: 9px; color: #64748b; margin-top: 4px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">- {{ $copy }} -</div>
                        <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Date: {{ $payment->created_at->format('d/m/Y') }}</div>
                    </div>

                    <table style="width: 100%; margin-top: 15px;">
                        <tr>
                            <td class="label-cell">Élève :</td>
                            <td class="value-cell" style="text-transform: uppercase;">{{ $payment->student->last_name }} {{ $payment->student->first_name }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Matricule & Classe :</td>
                            <td class="value-cell">{{ $payment->student->matricule }} &middot; {{ $payment->student->enrollments()->where('academic_year_id', $payment->academic_year_id)->first()?->level->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell">Année Scolaire :</td>
                            <td class="value-cell">{{ $payment->academicYear->name }}</td>
                        </tr>
                    </table>

                    <table style="width: 100%; margin-top: 15px; border: 1px solid #f1f5f9;">
                        <thead>
                            <tr>
                                <th class="th-bg" style="width: 70%;">Désignation / Motif</th>
                                <th class="th-bg" style="width: 30%; text-align: right;">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="td-border">
                                    <div style="font-weight: bold; color: #1e3a8a;">
                                        @if($payment->type === 'registration') INSCRIPTION
                                        @elseif($payment->type === 'miscellaneous') FRAIS DIVERS @else SCOLARITÉ @if($payment->installment_number) (T{{ $payment->installment_number }}) @endif @endif
                                    </div>
                                    <div style="font-size: 8px; color: #94a3b8;">Paiement du {{ $payment->created_at->format('d/m/Y à H:i') }}</div>
                                </td>
                                <td class="td-border" style="text-align: right; font-weight: bold; font-size: 12px;">{{ number_format($payment->amount, 0, ',', ' ') }} F CFA</td>
                            </tr>
                        </tbody>
                    </table>

                    <table style="width: 100%; margin-top: 15px;">
                        <tr>
                            <td style="width: 60%; vertical-align: middle;">
                                <div style="font-weight: bold; color: #1e3a8a; font-size: 9px;">Arrêté la présente somme à :</div>
                                <div style="font-weight: bold; text-transform: uppercase; color: #475569; font-size: 11px;">{{ number_format($payment->amount, 0, ',', ' ') }} FRANCS CFA</div>
                            </td>
                            <td style="width: 40%; text-align: right;">
                                <table style="width: 180px; float: right;">
                                    <tr><td class="amount-box">{{ number_format($payment->amount, 0, ',', ' ') }} F</td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <table style="width: 100%; margin-top: 10px;">
                        <tr>
                            <td class="sig-box">
                                <div style="font-weight: bold; color: #1e3a8a; font-size: 9px;">PARENT / ÉLÈVE</div>
                                <div class="sig-line"></div>
                            </td>
                            <td class="sig-box">
                                <div style="font-weight: bold; color: #1e3a8a; font-size: 9px;">ADMINISTRATION / CAISSE</div>
                                <div class="sig-line"></div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @if($index === 0)
            <div class="cut-line">
                <span class="cut-icon">✂--- LIGNE DE DÉCOUPE ---✂</span>
            </div>
        @endif
    @endforeach
</body>
</html>
