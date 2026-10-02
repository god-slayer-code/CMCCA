<?php

namespace App\Http\Controllers;

use App\Models\JournalAudit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $journal = JournalAudit::with('user')
            ->when($request->action, fn($q) => $q->where('action', $request->action))
            ->when($request->table, fn($q) => $q->where('table_concernee', $request->table))
            ->when($request->utilisateur, fn($q) => $q->where('user_id', $request->utilisateur))
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        $actions = JournalAudit::select('action')->distinct()->pluck('action');
        $tables  = JournalAudit::select('table_concernee')->distinct()->pluck('table_concernee');
        $utilisateurs = \App\Models\User::orderBy('name')->get();

        return view('audit.index', compact('journal', 'actions', 'tables', 'utilisateurs'));
    }
}