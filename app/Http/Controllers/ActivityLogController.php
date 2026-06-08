<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($builder) use ($keyword) {
                $builder->where('description', 'like', '%' . $keyword . '%')
                    ->orWhere('subject_type', 'like', '%' . $keyword . '%')
                    ->orWhere('event', 'like', '%' . $keyword . '%');
            });
        }

        $data = [
            'title' => 'Activity Log',
            'activities' => $query->paginate(25)->withQueryString(),
            'q' => $request->q,
        ];

        return view('backend.activity_log.index', $data);
    }
}
