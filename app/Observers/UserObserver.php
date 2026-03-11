<?php

namespace App\Observers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    public function created(User $user): void
    {
        $this->logActivity("Registered new member: {$user->full_name}", 'fa-user-plus');
    }

    public function updated(User $user): void
    {
        $this->logActivity("Updated details for: {$user->full_name}", 'fa-user-pen');
    }

    public function deleted(User $user): void
    {
        $this->logActivity("Removed member: {$user->full_name}", 'fa-user-minus');
    }

    private function logActivity($message, $icon)
    {
        if (Auth::check()) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'activity' => $message,
                'icon' => $icon
            ]);
        }
    }
}