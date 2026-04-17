<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.5; font-size: 14px; margin: 0; padding: 20px; }
        .header-table { width: 100%; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-cell { width: 25%; vertical-align: top; }
        .info-cell { width: 75%; text-align: right; vertical-align: top; }
        .title { color: #1e3a8a; font-size: 20px; font-weight: bold; margin: 0 0 5px 0; text-transform: uppercase; }
        .slogan { font-size: 13px; font-style: italic; color: #555; margin: 0 0 5px 0; }
        .contact-info { font-size: 11px; color: #666; margin: 0; line-height: 1.4; }
        .receipt-title-box { text-align: center; margin-bottom: 30px; }
        .receipt-title { display: inline-block; background-color: #1e3a8a; color: #fff; padding: 8px 20px; font-size: 16px; font-weight: bold; border-radius: 4px; text-transform: uppercase; letter-spacing: 1px; }
        .student-info-table { width: 100%; border: 1px solid #ddd; border-collapse: collapse; margin-bottom: 30px; }
        .student-info-table td { padding: 10px; border: 1px solid #ddd; }
        .student-info-table td label { font-size: 11px; color: #777; text-transform: uppercase; display: block; margin-bottom: 3px; }
        .student-info-table td .value { font-size: 14px; font-weight: bold; color: #333; display: block; }
        .financial-details { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .financial-details th, .financial-details td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .financial-details th { background-color: #f9fafb; font-weight: bold; color: #555; text-transform: uppercase; font-size: 12px; }
        .amount-box { border: 2px solid #1e3a8a; background-color: #f8fafc; padding: 15px; text-align: center; margin-bottom: 40px; border-radius: 8px; }
        .amount-box .label { font-size: 14px; color: #555; text-transform: uppercase; margin-bottom: 5px; }
        .amount-box .amount { font-size: 28px; font-weight: bold; color: #1e3a8a; }
        .footer { margin-top: 50px; width: 100%; display: table; }
        .footer-left { display: table-cell; width: 60%; font-size: 11px; color: #666; vertical-align: bottom; }
        .footer-right { display: table-cell; width: 40%; text-align: center; vertical-align: top; }
        .stamp-box { display: inline-block; width: 150px; height: 80px; border: 1px dashed #ccc; margin-top: 10px; line-height: 80px; color: #aaa; font-size: 12px; font-style: italic; }
        .logo { max-height: 70px; }
    </style>
</head>
<body>

@php
    $totalRequired = 0;
    $categoryName = '';
    // Registration
    if($payment->type === 'registration') {
        $categoryName = "Frais d'Inscription";
        $cycle = $enrollment->level->cycle;
        if (in_array($cycle, ['preschool', 'primary'])) $totalRequired = 3000;
        elseif ($cycle === 'college') $totalRequired = 5000;
        elseif ($cycle === 'lycee') $totalRequired = 10000;
    }
    // Miscellaneous
    elseif($payment->type === 'miscellaneous') {
        $categoryName = "Frais Divers";
        $levelName = strtolower($enrollment->level->name);
        $cycle = $enrollment->level->cycle;
        $isExamClass = $enrollment->level->is_exam_class;
        if (str_contains($levelName, 'cm2')) {
            $totalRequired = 2000;
        } elseif (in_array($cycle, ['college', 'lycee']) && $isExamClass) {
            $totalRequired = 3000;
        } else {
            $totalRequired = 1000;
        }
    }
    // Tuition
    elseif($payment->type === 'tuition') {
        $categoryName = "Scolarité (Tranche " . $payment->installment_number . ")";
        $tuitionFee = \App\Models\TuitionFee::where('level_id', $enrollment->level_id)
            ->where('academic_year_id', $payment->academic_year_id)
            ->first();
        if ($payment->installment_number && $tuitionFee) {
            $installment = \App\Models\Installment::where('tuition_fee_id', $tuitionFee->id)
                ->where('installment_number', $payment->installment_number)
                ->first();
            $totalRequired = $installment ? $installment->amount : 0;
        }
    }

    if ($payment->type === 'tuition' && $payment->installment_number) {
        $totalPaid = \App\Models\Payment::where('student_id', $payment->student_id)
            ->where('academic_year_id', $payment->academic_year_id)
            ->where('type', 'tuition')
            ->where('installment_number', $payment->installment_number)
            ->sum('amount');
    } else {
        $totalPaid = \App\Models\Payment::where('student_id', $payment->student_id)
            ->where('academic_year_id', $payment->academic_year_id)
            ->where('type', $payment->type)
            ->sum('amount');
    }

    $balance = max(0, $totalRequired - $totalPaid);
    $schoolName = isset($schoolSetting) && $schoolSetting->name ? $schoolSetting->name : 'INSTITUT SCOLAIRE SOPHIA';
    $schoolSlogan = isset($schoolSetting) && $schoolSetting->slogan ? $schoolSetting->slogan : '«Le Don De Dieu»';
    $schoolAddress = isset($schoolSetting) && $schoolSetting->address ? $schoolSetting->address : "Mitoyenneté du marché de Pain et de l'Etat-major de la Gendarmerie Maritime à Tsévié Quartier Dévé";
    $schoolPhones = isset($schoolSetting) && $schoolSetting->phones ? $schoolSetting->phones : '90238084 / 99964949';
    $schoolEmail = isset($schoolSetting) && $schoolSetting->email ? $schoolSetting->email : 'institutsophia98@gmail.com';
@endphp

    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @if(isset($schoolSetting) && $schoolSetting->logo_path)
                    <img src="{{ public_path('storage/' . $schoolSetting->logo_path) }}" alt="Logo" class="logo">
                @endif
            </td>
            <td class="info-cell">
                <h1 class="title">{{ $schoolName }}</h1>
                <p class="slogan">{{ $schoolSlogan }}</p>
                <div class="contact-info">
                    {{ $schoolAddress }}<br>
                    Tél: {{ $schoolPhones }} | Email: {{ $schoolEmail }}
                </div>
            </td>
        </tr>
    </table>

    <div class="receipt-title-box">
        <div class="receipt-title">REÇU DE CAISSE N° {{ $payment->transaction_id }}</div>
    </div>

    <table class="student-info-table">
        <tr>
            <td style="width: 50%;">
                <label>Nom de l'élève</label>
                <span class="value">{{ mb_strtoupper($student->last_name) }} {{ $student->first_name }}</span>
            </td>
            <td style="width: 25%;">
                <label>Matricule</label>
                <span class="value">{{ $student->matricule }}</span>
            </td>
            <td style="width: 25%;">
                <label>Classe</label>
                <span class="value">{{ $enrollment->level->name }}</span>
            </td>
        </tr>
    </table>

    <div class="amount-box">
        <div class="label">Montant Versé</div>
        <div class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
    </div>

    <table class="financial-details">
        <thead>
            <tr>
                <th style="width: 40%;">Désignation des Frais</th>
                <th style="width: 20%; text-align: right;">Montant Requis</th>
                <th style="width: 20%; text-align: right;">Déjà Payé</th>
                <th style="width: 20%; text-align: right;">Reste à Payer</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $categoryName }}</strong></td>
                <td style="text-align: right;">{{ number_format($totalRequired, 0, ',', ' ') }} FCFA</td>
                <td style="text-align: right;">{{ number_format($totalPaid, 0, ',', ' ') }} FCFA</td>
                <td style="text-align: right; color: {{ $balance > 0 ? '#dc2626' : '#16a34a' }}; font-weight: bold;">
                    {{ number_format($balance, 0, ',', ' ') }} FCFA
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-left">
            <i>Reçu généré électroniquement le {{ $payment->created_at->format('d/m/Y') }} à {{ $payment->created_at->format('H:i') }}.</i><br>
            Ce document fait foi de paiement et doit être conservé.
        </div>
        <div class="footer-right">
            <strong>L'Économat</strong><br>
            <div class="stamp-box">Cachet de l'Ecole</div>
        </div>
    </div>
</body>
</html>
