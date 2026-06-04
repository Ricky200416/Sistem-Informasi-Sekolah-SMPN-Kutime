<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    // ── GET /admin/activity-log ──────────────────────────────────
    // Full-page view (opsional — dashboard sudah embed widget)
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user')
            ->where('created_at', '>=', now()->subHours(12));

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $logs = $query->orderByDesc('created_at')->paginate(50)->withQueryString();

        $counts = ActivityLog::where('created_at', '>=', now()->subHours(12))
            ->selectRaw("role, count(*) as total")
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('admin.activity-log.index', compact('logs', 'counts'));
    }

    // ── GET /admin/activity-log/data  (JSON — widget live refresh) ──
    public function data(Request $request): JsonResponse
    {
        $query = ActivityLog::with('user:id,name,photo')
            ->where('created_at', '>=', now()->subHours(12));

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        $logs = $query->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(fn($log) => [
                'id'          => $log->id,
                'user_name'   => $log->user?->name ?? 'Unknown',
                'role'        => $log->role,
                'action'      => $log->action,
                'module'      => $log->module,
                'description' => $log->description,
                'ip_address'  => $log->ip_address,
                'icon'        => $log->actionIcon(),
                'badge_color' => $log->roleBadgeColor(),
                'time'        => $log->created_at->format('H:i'),
                'diff'        => $log->created_at->diffForHumans(null, true),
                'created_at'  => $log->created_at->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'total'   => $logs->count(),
            'data'    => $logs,
        ]);
    }

    // ── DELETE /admin/activity-log/{log} ────────────────────────
    public function destroy(ActivityLog $log): JsonResponse
    {
        $log->delete();

        return response()->json(['success' => true, 'message' => 'Log dihapus.']);
    }

    // ── DELETE /admin/activity-log/purge ────────────────────────
    // Hapus manual semua log > 12 jam
    public function purge(): JsonResponse
    {
        $deleted = ActivityLog::where('created_at', '<', now()->subHours(12))->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deleted} log lama berhasil dihapus.",
            'deleted' => $deleted,
        ]);
    }
}