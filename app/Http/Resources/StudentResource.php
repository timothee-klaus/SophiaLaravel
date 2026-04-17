<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\GetStudentFinancialSummary;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $academicYearId = $request->get('academic_year_id', 1);

        $summary = (new GetStudentFinancialSummary())->execute($this->resource, $academicYearId);

        if (empty($summary)) {
            return [
                'id' => $this->id,
                'name' => trim($this->first_name . ' ' . $this->last_name),
                'error' => 'Not enrolled in this academic year.'
            ];
        }

        return array_merge($summary, [
            'matricule' => $this->matricule,
            'birth_date' => $this->birth_date->format('Y-m-d'),
        ]);
    }
}

