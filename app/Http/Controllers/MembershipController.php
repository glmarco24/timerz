<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Return staff for a given company the user actively owns.
     */
    public function staff(Request $request, int $company): JsonResponse
    {
        $user = $request->user();

        $companyModel = $user->activeOwnerCompanies()
            ->where('companies.id', $company)
            ->first();

        if (!$companyModel) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $staff = $companyModel->users()
            ->select('users.id', 'users.first_name', 'users.last_name')
            ->orderBy('users.first_name')
            ->orderBy('users.last_name')
            ->get();

        return response()->json([
            'staff' => $staff,
        ]);
    }
}
