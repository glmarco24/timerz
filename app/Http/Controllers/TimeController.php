<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    /**
     * Return data to populate the Add Time modal.
     * Includes workplaces (companies where the user is active owner)
     * and sensible defaults.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $workplaces = $user->activeOwnerCompanies()
            ->select('companies.id', 'companies.name')
            ->orderBy('companies.name')
            ->get();

        $staff = collect();
        if ($companyId = $request->integer('company_id')) {
            $company = $user->activeOwnerCompanies()->where('companies.id', $companyId)->first();
            if ($company) {
                $staff = $company->users()
                    ->select('users.id', 'users.first_name', 'users.last_name')
                    ->orderBy('users.first_name')
                    ->orderBy('users.last_name')
                    ->get();
            }
        }

        return response()->json([
            'workplaces' => $workplaces,
            'staff' => $staff,
            'defaults' => [
                'date' => now()->toDateString(),
                'benefit' => null,
            ],
        ]);
    }
}
