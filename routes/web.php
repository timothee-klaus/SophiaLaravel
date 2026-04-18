<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Livewire\SecretaryRegistration;
use App\Livewire\DirectorRegistration;
use App\Livewire\DatabaseExplorer;
use App\Models\RegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/register-secretary', SecretaryRegistration::class)->name('register-secretary');
Route::get('/register-director', DirectorRegistration::class)->name('register-director');
Route::get('/db-explorer', DatabaseExplorer::class)->name('db-explorer');

Route::get('/approve-registration/{registrationRequest}', function (Request $request, RegistrationRequest $registrationRequest) {
    if (! $request->hasValidSignature()) {
        abort(403, 'Lien d\'approbation invalide ou expiré.');
    }

    if ($registrationRequest->status === 'approved') {
        return "Cette demande a déjà été approuvée.";
    }

    // Create User
    User::create([
        'name' => $registrationRequest->name,
        'email' => $registrationRequest->email,
        'password' => Hash::make('password'), // Mot de passe par défaut pour le test
        'role' => 'secretary',
    ]);

    $registrationRequest->update([
        'status' => 'approved',
        'approved_at' => now(),
    ]);

    return "La demande de " . $registrationRequest->name . " a été approuvée avec succès. Le compte secrétaire a été créé avec le mot de passe par défaut 'password'.";
})->name('registration.approve');

Route::get('/reject-registration/{registrationRequest}', function (Request $request, RegistrationRequest $registrationRequest) {
    if (! $request->hasValidSignature()) {
        abort(403, 'Lien de refus invalide ou expiré.');
    }

    if ($registrationRequest->status !== 'pending' && $registrationRequest->status !== 'verified') {
        return "Cette demande ne peut plus être rejetée (Statut actuel : " . $registrationRequest->status . ").";
    }

    $registrationRequest->update([
        'status' => 'rejected',
    ]);

    return "La demande de " . $registrationRequest->name . " a été rejetée.";
})->name('registration.reject');

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = $request->has('remember-me');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'Les identifiants fournis ne correspondent à aucun compte.',
    ])->onlyInput('email');
})->name('login.post');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/enrollments/new', function () {
        return view('enrollments');
    })->name('enrollments.new');

    Route::get('/payments', function () {
        return view('payments');
    })->name('payments');

    Route::get('/academic-years', function () {
        return view('academic-years');
    })->name('academic-years.manager');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    Route::get('/students', function () {
        return view('students');
    })->name('students.index');

    Route::get('/exam-eligibility', function () {
        return view('exam-eligibility');
    })->name('exam-eligibility');

    Route::get('/payments/{payment}/receipt-preview', function (App\Models\Payment $payment) {
        $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('exports.receipt-pdf', ['payment' => $payment]);
        return $pdf->stream('Recu_' . $payment->transaction_id . '.pdf');
    })->name('payments.receipt-preview');

    Route::get('/payments/{payment}/receipt-download', function (App\Models\Payment $payment) {
        $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('exports.receipt-pdf', ['payment' => $payment]);
        return $pdf->download('Recu_' . $payment->transaction_id . '.pdf');
    })->name('payments.receipt-download');

    Route::get('/reports/payments/pdf', [App\Http\Controllers\ReportController::class, 'previewPayments'])->name('reports.payments.pdf');
    Route::get('/reports/balances/pdf', [App\Http\Controllers\ReportController::class, 'previewBalances'])->name('reports.balances.pdf');
});
