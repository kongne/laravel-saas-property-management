<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        if ($request->action) {
            $query->where('action', $request->action);
        }

        if ($request->search) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $logs = $query->latest()->paginate(25);
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('audit.index', compact('logs', 'actions'));
    }
}
