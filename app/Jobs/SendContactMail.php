<?php

namespace App\Jobs;

use App\Mail\ContactFormMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $to = config('site.developer.email', env('MAIL_TO_ADDRESS', null));
        if (! $to) {
            logger()->info('SendContactMail: no mail recipient configured', $this->data);
            return;
        }

        try {
            Mail::to($to)->send(new ContactFormMail($this->data));
        } catch (\Exception $e) {
            logger()->error('SendContactMail failed: ' . $e->getMessage(), ['data' => $this->data]);
            // Let the job fail silently; failed jobs will be recorded in failed_jobs
            throw $e;
        }
    }
}
