<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MealDistribution;
use Illuminate\Http\JsonResponse;

class DistributionController extends Controller
{
    public function getBySchool(int $schoolId): JsonResponse
    {
        $distributions = MealDistribution::where('school_id', $schoolId)
            ->orderBy('tanggal_distribusi', 'desc')
            ->get(['id', 'tanggal_distribusi', 'menu_makanan']);

        return response()->json($distributions);
    }
}