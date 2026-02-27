<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Return workplaces (companies where the user is active owner).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $workplaces = $user->activeOwnerCompanies()
            ->select('companies.id', 'companies.name')
            ->orderBy('companies.name')
            ->get();

        return response()->json([
            'workplaces' => $workplaces,
        ]);
    }
}
