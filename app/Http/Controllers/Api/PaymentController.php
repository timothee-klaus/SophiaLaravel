<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\RegisterPaymentAction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function store(Request $request, RegisterPaymentAction $action): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'type' => 'required|in:registration,miscellaneous,tuition',
            'amount' => 'required|numeric|min:0',
            'installment_number' => 'nullable|integer|min:1',
            'receipt' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        try {
            $student = Student::findOrFail($validated['student_id']);

            $payment = $action->execute(
                $student,
                $validated['academic_year_id'],
                $validated['type'],
                $validated['amount'],
                $validated['installment_number'] ?? null,
                $request->file('receipt')
            );

            return response()->json([
                'success' => true,
                'message' => 'Paiement enregistré avec succès.',
                'payment' => $payment
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
