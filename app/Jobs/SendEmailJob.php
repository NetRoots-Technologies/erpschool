<?php

namespace App\Jobs;
use App\Mail\SalarySlipMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use PDF;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $pdfData;

    /**
     * Create a new job instance.
     *
     * @param string $email
     * @param mixed $pdf
     */
    public function __construct($email, $pdfData)
    {
        $this->email = $email;
        $this->pdfData = $pdfData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pdf = PDF::loadView($this->pdfData['view'], $this->pdfData['data']);
        Mail::to($this->email)->send(new SalarySlipMail($pdf));
    }
}

