<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SalarySlipMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;

    /**
     * Create a new message instance.
     *
     * @param $pdf The PDF instance to attach
     * @return void
     */
    public function __construct($pdf)
    {
        $this->pdf = $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdfContent = $this->pdf->output();
        $pdfFileName = 'salary_slip.pdf';


        return $this->view('emails.salary_slip')
            ->attachData($pdfContent, $pdfFileName, [
                'mime' => 'application/pdf',
            ]);
    }
}

