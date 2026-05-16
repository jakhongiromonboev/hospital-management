<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = auth()->user()->schedules()->orderByRaw("
            CASE day_of_week
                WHEN 'monday' THEN 1
                WHEN 'tuesday' THEN 2
                WHEN 'wednesday' THEN 3
                WHEN 'thursday' THEN 4
                WHEN 'friday' THEN 5
                WHEN 'saturday' THEN 6
                WHEN 'sunday' THEN 7
            END
        ")->get();

        return view('doctor.schedule', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'max_patients' => 'required|integer|min:1|max:50',
        ]);

        $exists = auth()->user()->schedules()
            ->where('day_of_week', $request->day_of_week)
            ->exists();

        if ($exists) {
            return back()->with('error', '해당 요일에 이미 진료 시간이 있습니다. 기존 항목을 수정해 주세요.');
        }

        auth()->user()->schedules()->create($request->only(
            'day_of_week', 'start_time', 'end_time', 'max_patients'
        ));

        return redirect()->route('doctor.schedule')->with('success', '진료 시간이 추가되었습니다.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->doctor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'max_patients' => 'required|integer|min:1|max:50',
            'status' => 'required|boolean',
        ]);

        $schedule->update($request->only('start_time', 'end_time', 'max_patients', 'status'));

        return redirect()->route('doctor.schedule')->with('success', '진료 시간이 수정되었습니다.');
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->doctor_id !== auth()->id()) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('doctor.schedule')->with('success', '진료 시간이 삭제되었습니다.');
    }
}
