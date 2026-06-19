<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');
        
        // Filter search
        if ($request->has('search') && $request->search) {
            $query->where('description', 'like', "%{$request->search}%")
                  ->orWhere('entity_type', 'like', "%{$request->search}%")
                  ->orWhere('action', 'like', "%{$request->search}%");
        }
        
        // Filter action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }
        
        // Filter entity
        if ($request->has('entity') && $request->entity) {
            $query->where('entity_type', $request->entity);
        }
        
        // Filter date
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Data untuk filter
        $actions = AuditLog::distinct()->pluck('action');
        $entities = AuditLog::distinct()->pluck('entity_type');
        
        return view('admin.audit-logs.index', compact('logs', 'actions', 'entities'));
    }
    
    public function show(AuditLog $auditLog)
    {
        return view('admin.audit-logs.show', compact('auditLog'));
    }
}