<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->markdown('emails.Send')
            ->from($this->data->from, 'SMS')
            ->cc($this->data->cc)
            ->bcc($this->data->bcc)
            ->subject($this->data->subject)
            ->with(['message' => $this->data->message]);
        if (!empty($this->data->file)) {
            $data->attach(
                $this->data->file->getRealPath(),
                [
                    'as' => $this->data->file->getClientOriginalName(),
                    'mime' => $this->data->file->getClientMimeType(),
                ]
            );
        }
        return  $data;
    }
}
