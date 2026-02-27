<?php

namespace App\Http\Requests\Time;

use App\Models\Time;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateTimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Time|null $time */
        $time = $this->route('time');
        $user = $this->user();
        if (!$time || !$user) return false;

        // User must be active owner of the time's company
        return $user->activeOwnerCompanies()->where('companies.id', $time->company_id)->exists();
    }

    public function rules(): array
    {
        return [
            'start_time' => ['nullable', 'date_format:H:i', 'required_without_all:end_time'],
            'end_time' => ['nullable', 'date_format:H:i', 'required_without_all:start_time'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var Time|null $time */
            $time = $this->route('time');
            if (!$time) return;

            $start = $this->input('start_time');
            $end = $this->input('end_time');

            // If both provided, ensure end > start
            if (is_string($start) && is_string($end) && strlen($start) && strlen($end)) {
                if (!preg_match('/^\d{2}:\d{2}$/', $start) || !preg_match('/^\d{2}:\d{2}$/', $end)) {
                    return; // let base rules handle format
                }
                if ($end <= $start) {
                    $validator->errors()->add('end_time', 'End time must be later than start time.');
                }
            }

            // Overlap check if start_time is provided (same user/date, exclude current, only against records with end_time)
            if (is_string($start) && preg_match('/^\d{2}:\d{2}$/', $start)) {
                $overlaps = Time::query()
                    ->where('id', '!=', $time->id)
                    ->where('user_id', $time->user_id)
                    ->where('date', $time->date)
                    ->whereNotNull('end_time')
                    ->where('start_time', '<=', $start)
                    ->where('end_time', '>', $start)
                    ->exists();
                if ($overlaps) {
                    $validator->errors()->add('start_time', 'Start time overlaps with an existing time entry.');
                }
            }
        });
    }
}

