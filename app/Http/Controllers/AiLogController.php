<?php

namespace App\Http\Controllers;

use App\Models\AiUsageLog;
use Illuminate\Http\Request;

class AiLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AiUsageLog::with(['provider', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('feature')) {
            $query->where('feature', 'like', '%' . $request->feature . '%');
        }

        $data = [
            'title' => 'AI Usage Logs',
            'logs' => $query->paginate(25)->withQueryString(),
            'status' => $request->status,
            'feature' => $request->feature,
        ];

        return view('backend.ai_logs.index', $data);
    }
}
