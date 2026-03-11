<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'content', 'category', 'user_id', 'is_active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::created(function ($announcement) {
            try {
                // Fetch leadership emails
                $recipients = \App\Models\User::whereIn('role', ['President', 'Coordinator'])
                    ->whereNotNull('email')
                    ->pluck('email');

                foreach ($recipients as $email) {
                    // We use 'queue' to prevent the dashboard from freezing
                    \Illuminate\Support\Facades\Mail::to($email)
                        ->queue(new \App\Mail\AnnouncementMail($announcement));
                }
            } catch (\Exception $e) {
                // Log the error so the system doesn't crash for the user
                \Illuminate\Support\Facades\Log::error("Email failed for Announcement {$announcement->id}: " . $e->getMessage());
            }
        });
    }
}