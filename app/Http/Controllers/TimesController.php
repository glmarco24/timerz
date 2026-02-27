<?php

namespace App\Http\Controllers;

use App\Http\Requests\Time\StoreTimeRequest;
use App\Models\Time;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Time\UpdateTimeRequest;

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
            ->orderByDesc('created_at')
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

        $status = isset($data['end_time']) && $data['end_time'] !== null && $data['end_time'] !== ''
            ? 'Completed'
            : 'At work';

        $time = Time::create([
            'user_id' => $data['user_id'],
            'company_id' => $data['company_id'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'] ?? null,
            'status' => $status,
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

    public function update(UpdateTimeRequest $request, Time $time): JsonResponse
    {
        $start = $request->input('start_time');
        $end = $request->input('end_time');

        $newStart = is_string($start) ? $start : $time->start_time;
        $newEnd = is_string($end) ? $end : $time->end_time;

        $newStatus = $newEnd ? 'Completed' : 'At work';

        $payload = [
            'user_id' => $time->user_id,
            'company_id' => $time->company_id,
            'date' => $time->date,
            'start_time' => $newStart,
            'end_time' => $newEnd ?: null,
            'status' => $newStatus,
            'benefit' => $time->benefit,
            'comment' => $time->comment,
            'latitude' => $time->latitude,
            'longitude' => $time->longitude,
        ];

        $newTime = DB::transaction(function () use ($time, $payload) {
            $time->status = 'Deleted';
            $time->save();

            return Time::create($payload);
        });

        return response()->json([
            'message' => 'Updated',
            'time' => $newTime->load(['user:id,first_name,last_name', 'company:id,name']),
        ]);
    }
}
