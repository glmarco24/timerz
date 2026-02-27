<?php

namespace App\Http\Controllers;

use App\Http\Requests\Time\StoreTimeRequest;
use App\Models\Time;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $companyIds = $user->activeOwnerCompanies()->pluck('companies.id');

        $times = Time::with([
                'user:id,first_name,last_name',
                'company:id,name',
            ])
            ->whereIn('company_id', $companyIds)
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->get([
                'id', 'user_id', 'company_id', 'date', 'start_time', 'end_time', 'status', 'benefit', 'comment'
            ]);

        return response()->json([
            'times' => $times,
        ]);
    }

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
