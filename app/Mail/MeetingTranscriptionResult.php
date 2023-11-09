<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MeetingTranscriptionResult extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $docxPath;

    public function __construct($docxPath)
    {
        $this->docxPath = $docxPath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Meeting Transcription Result',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'meeting_transcription.index',
        );
    }

    public function attachments(): array
    {
        return [
            $this->docxPath => [
                'as' => 'meeting_transcription.docx',
            ],
        ];
    }
}
