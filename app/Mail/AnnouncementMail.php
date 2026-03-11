<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class AnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;
    // The number of times the job may be attempted.
    public $tries = 3;

    // The number of seconds the job can run before timing out.
    public $timeout = 30;

    // Use the constructor to pass the data
    public function __construct(public Announcement $announcement)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New 4-H Announcement: ' . $this->announcement->title,
        );
    }

    /**
     * KEEP ONLY THIS ONE VERSION OF CONTENT
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.announcement', // Ensure this view exists
            with: ['announcement' => $this->announcement],
        );
    }

    public function attachments(): array
    {
        return $this->announcement->attachment
            ? [Attachment::fromStorageDisk('public', $this->announcement->attachment)]
            : [];
    }
}
