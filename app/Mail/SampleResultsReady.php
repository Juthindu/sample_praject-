<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Attachment;
use Modules\ConsumerSample\Entities\ConsumerSample as EntitiesConsumerSample;

class SampleResultsReady extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EntitiesConsumerSample $sample,
        public string $pdfBytes
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Water Sample Test Results'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.sample_results',
            with: ['sample' => $this->sample]
        );
    }

    public function attachments(): array
    {
        $filename = 'Water_Sample_Results_' . ($this->sample->laboratory_no ?? $this->sample->id) . '.pdf';

        return [
            Attachment::fromData(fn () => $this->pdfBytes, $filename)
                ->withMime('application/pdf'),
        ];
    }
}
