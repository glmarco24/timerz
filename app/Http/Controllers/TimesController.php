<?php

namespace App\Http\Controllers;

use App\Http\Requests\Time\StoreTimeRequest;
use App\Models\Time;
use Illuminate\Http\JsonResponse;

class TimesController extends Controller
{
    public function store(StoreTimeRequest $request): JsonResponse
    {
        $user = $request->user();

        $company = $user->activeOwnerCompanies()->where('companies.id', $request->integer('company_id'))->first();
        if (!$company) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validated();

        $time = Time::create([
            'user_id' => $data['user_id'],
            'company_id' => $data['company_id'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'] ?? null,
            'benefit' => $data['benefit'] ?? null,
            'comment' => $data['comment'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ]);

        return response()->json([
            'message' => 'Saved',
            'time' => $time,
        ], 201);
    }
}
