<?php

namespace App\Http\Requests\Time;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use App\Models\Time;

class StoreTimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('memberships', 'user_id')->where(fn ($q) => $q->where('company_id', $this->input('company_id'))),
            ],
            'date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'benefit' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $start = $this->input('start_time');
            $end = $this->input('end_time');

            if (is_string($end) && strlen($end) && is_string($start) && strlen($start)) {
                // Simple HH:MM lexical compare works when both are zero-padded
                if (!preg_match('/^\d{2}:\d{2}$/', $start) || !preg_match('/^\d{2}:\d{2}$/', $end)) {
                    return; // let other validators handle format errors
                }
                if ($end <= $start) {
                    $validator->errors()->add('end_time', 'End time must be later than start time.');
                }
            }

            // Prevent overlapping start time within another record for the same worker on the same date
            $userId = (int) $this->input('user_id');
            $date = $this->input('date');
            if ($userId && is_string($date) && is_string($start) && preg_match('/^\d{2}:\d{2}$/', $start)) {
                $overlaps = Time::query()
                    ->where('user_id', $userId)
                    ->where('date', $date)
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
