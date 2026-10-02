<?php

namespace App\Observers;

use App\Models\JournalAudit;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->log('creation', $model);
    }

    public function updated(Model $model): void
    {
        $this->log('modification', $model, $model->getChanges());
    }

    public function deleted(Model $model): void
    {
        $this->log('suppression', $model);
    }

    private function log(string $action, Model $model, array $details = []): void
    {
        if (!auth()->check()) return;

        JournalAudit::create([
            'user_id'           => auth()->id(),
            'action'            => $action,
            'table_concernee'   => $model->getTable(),
            'enregistrement_id' => $model->id,
            'details_json'      => ($details),
            'ip_adresse'        => request()->ip(),
        ]);
    }
}