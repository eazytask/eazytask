<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EventToRosterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $shift_start = Carbon::parse($this->shift_start);
        $shift_end = Carbon::parse($this->shift_end);
        $duration = round($shift_start->floatDiffInRealHours($shift_end), 2);
        return [
            'id' => $this->id,
            'is_event' => true,
            'employee_id' => null,
            'project_id' => $this->project_name,
            'roaster_date' => $this->event_date,
            'shift_start' => $this->shift_start,
            'shift_end' => $this->shift_end,
            'sing_in' => null,
            'sing_out' => null,
            'duration' => $duration,
            'ratePerHour' => (float) $this->rate,
            'amount' => (float) ($this->rate * $duration),
            'job_type_id' => $this->job_type_name,
            'is_applied' => count($this->already_applied) ? 1 : 0,
            'remarks' => $this->remarks,
            'latest' => Carbon::parse($this->shift_end) > Carbon::now()?true:false,
            'calendar' => 'bg-yahoo text-white',
        ];
    }
}
