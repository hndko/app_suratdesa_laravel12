<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->dataTable($request);
        }

        $data = [
            'title' => 'Activity Log',
            'totalActivities' => Activity::count(),
            'todayActivities' => Activity::whereDate('created_at', today())->count(),
            'totalEvents' => Activity::whereNotNull('event')->distinct('event')->count('event'),
            'totalActors' => Activity::whereNotNull('causer_id')->distinct('causer_id')->count('causer_id'),
            'events' => Activity::whereNotNull('event')->distinct()->orderBy('event')->pluck('event'),
            'modules' => Activity::whereNotNull('subject_type')->distinct()->orderBy('subject_type')->pluck('subject_type')
                ->map(fn ($type) => class_basename($type))
                ->unique()
                ->values(),
        ];

        return view('backend.activity_log.index', $data);
    }

    private function dataTable(Request $request): JsonResponse
    {
        $columns = [
            0 => 'id',
            1 => 'created_at',
            2 => 'event',
            3 => 'subject_type',
            4 => 'causer_id',
        ];

        $baseQuery = Activity::query()->with('causer');
        $recordsTotal = (clone $baseQuery)->count();

        $this->applyFilters($baseQuery, $request);

        $search = trim((string) $request->input('search.value'));
        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('description', 'like', '%' . $search . '%')
                    ->orWhere('event', 'like', '%' . $search . '%')
                    ->orWhere('subject_type', 'like', '%' . $search . '%')
                    ->orWhere('log_name', 'like', '%' . $search . '%');
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $orderColumnIndex = (int) $request->input('order.0.column', 1);
        $orderColumn = $columns[$orderColumnIndex] ?? 'created_at';
        $orderDirection = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $length = (int) $request->input('length', 10);
        $start = max((int) $request->input('start', 0), 0);
        $length = $length > 0 ? min($length, 100) : 10;

        $rows = $baseQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $rows->map(function (Activity $activity, int $index) use ($start) {
            $event = $activity->event ?: $activity->description;
            $module = $activity->subject_type ? class_basename($activity->subject_type) : 'System';
            $properties = $activity->properties?->toArray() ?? [];
            $propertiesJson = json_encode($properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $attributes = data_get($properties, 'attributes');
            $old = data_get($properties, 'old');
            $summary = $this->changeSummary($attributes, $old);
            $eventClass = $this->eventClass($event);

            return [
                'no' => $start + $index + 1,
                'created_at' => '<strong>' . e($activity->created_at?->format('d/m/Y')) . '</strong><small>' . e($activity->created_at?->format('H:i:s')) . '</small>',
                'event' => '<span class="event-pill ' . $eventClass . '"><i class="fas fa-bolt"></i>' . e($event ?: '-') . '</span><small>' . e($activity->description) . '</small>',
                'module' => '<strong>' . e($module) . '</strong><small>' . e($activity->log_name ?: 'default') . '</small>',
                'actor' => '<div class="actor-cell"><span class="actor-avatar">' . e(Str::upper(Str::substr($activity->causer?->name ?? 'S', 0, 1))) . '</span><div><strong>' . e($activity->causer?->name ?? 'System') . '</strong><small>' . e($activity->causer?->email ?? 'Tanpa user') . '</small></div></div>',
                'changes' => '<span class="change-pill"><i class="fas fa-exchange-alt"></i>' . e($summary) . '</span>',
                'detail' => '<button type="button" class="btn btn-sm btn-outline-secondary js-activity-detail" title="Detail Aktivitas" data-event="' . e($event ?: '-') . '" data-module="' . e($module) . '" data-actor="' . e($activity->causer?->name ?? 'System') . '" data-description="' . e($activity->description) . '" data-properties="' . e(Str::limit($propertiesJson ?: '{}', 1800)) . '"><i class="fas fa-eye"></i></button>',
            ];
        });

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('event')) {
            $query->where('event', $request->input('event'));
        }

        if ($request->filled('module')) {
            $query->where('subject_type', 'like', '%' . $request->input('module') . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
    }

    private function changeSummary($attributes, $old): string
    {
        $newCount = is_array($attributes) ? count($attributes) : 0;
        $oldCount = is_array($old) ? count($old) : 0;

        if ($newCount === 0 && $oldCount === 0) {
            return 'Tidak ada payload perubahan';
        }

        return $newCount . ' baru | ' . $oldCount . ' lama';
    }

    private function eventClass(?string $event): string
    {
        return match ($event) {
            'created' => 'event-success',
            'updated' => 'event-info',
            'deleted' => 'event-danger',
            default => 'event-secondary',
        };
    }
}
